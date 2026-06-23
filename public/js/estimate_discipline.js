/**
 * Estimate Discipline — real-time collaborative editor
 *
 * Auto-saves every row on change (debounced 800 ms).
 * Broadcasts changes via Laravel Echo / Soketi so all discipline engineers
 * see live updates without refreshing.
 */
$(function () {

    // ─── Config ────────────────────────────────────────────────────────────────

    var AUTOSAVE_DELAY       = 800;   // ms after last keystroke before saving
    var $form                = $('.js-form-estimate-discipline');
    var projectId            = $form.data('project-id') || $form.data('id');
    var currentUserId        = parseInt($form.data('user-id')) || 0;
    var currentUserDiscipline = $form.data('user-discipline') || '';
    var saveQueue            = {};    // keyed by unique_identifier — pending debounce timers
    var pendingCount         = 0;     // rows currently being saved

    // ─── Helpers ───────────────────────────────────────────────────────────────

    function generateId() {
        if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
        return Math.random().toString(36).substring(2, 15) +
               Math.random().toString(36).substring(2, 15);
    }

    function toCurrency(val) {
        if (typeof val !== 'number' || isNaN(val)) return '';
        var parts       = val.toFixed(2).split('.');
        var integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return integerPart + ',' + parts[1];
    }

    function removeCurrency(val) {
        if (val == null || val === '') return 0;
        return val.toString().replaceAll('.', '').replaceAll(',', '.');
    }

    function removeBlankSpace(str) {
        return str.replace(/\s/g, '');
    }

    // ─── Autosave status ───────────────────────────────────────────────────────

    function showStatus(state, msg) {
        var icons  = { saving: 'fa-spinner fa-spin', saved: 'fa-check-circle', error: 'fa-exclamation-circle' };
        var labels = { saving: 'Saving…', saved: 'All changes saved', error: msg || 'Error saving' };
        var $els   = $('.js-autosave-status, .js-autosave-status-fs');
        $els.attr('class', function (_, c) {
            return c.replace(/autosave-\S+/g, '') + ' autosave-status autosave-' + state;
        });
        $els.html('<i class="fa ' + icons[state] + ' me-1"></i>' + labels[state]);
        // Disable publish while saving — be defensive about multiple button variants
        var disabled = state === 'saving';
        $('.js-btn-publish').each(function () {
            try { $(this).prop('disabled', disabled).attr('aria-disabled', disabled); } catch (e) {}
        });
    }

    function trackPending(delta) {
        pendingCount = Math.max(0, pendingCount + delta);
        showStatus(pendingCount > 0 ? 'saving' : 'saved');
    }

    // ─── Collect row payload ───────────────────────────────────────────────────

    function collectRowData($row) {
        var uid           = ($row.find('.js-unique-identifier').val() || '').trim();
        var $select       = $row.find('.js-select-work-items');
        var $textDiv      = $row.find('.js-work-item-text');
        // Read work item ID: try Select2/native select first, fall back to data-id on the text div
        var workItem      = $select.val() || ($select[0] && $select[0].value) || $textDiv.data('id') || '';
        var workItemText  = $select.find('option:selected').text().replace(/ - \(REVIEWED\)| - \(DRAFT\)/, '');
        if (!workItemText) {
            workItemText = $textDiv.find('span').text().replace(/ - \(REVIEWED\)| - \(DRAFT\)/, '');
        }
        var vol           = $row.find('.js-input-vol').val() || 1;
        var labourFac     = $row.find('.js-input-labor_factorial').val();
        var equipFac      = $row.find('.js-input-equipment_factorial').val();
        var matFac        = $row.find('.js-input-material_factorial').val();
        // Rates: select carries them after a fresh selection; text div carries them for existing rows
        var labourRate    = $select.attr('data-cost-man-power') || $textDiv.attr('data-cost-man-power') || 0;
        var equipRate     = $select.attr('data-cost-tools')     || $textDiv.attr('data-cost-tools')     || 0;
        var matRate       = $select.attr('data-cost-material')  || $textDiv.attr('data-cost-material')  || 0;
        var manPowerTotal = removeBlankSpace($row.find('.js-work-item-man-power-cost').text());
        var equipTotal    = removeBlankSpace($row.find('.js-work-item-equipment-cost').text());
        var matTotal      = removeBlankSpace($row.find('.js-work-item-material-cost').text());
        var wbs3          = $row.find('.js-wbs_level3_id').val();
        var workEl        = $row.find('.js-work_element_id').val();

        return {
            unique_identifier:   uid,
            workItem:            workItem,
            workItemText:        workItemText,
            vol:                 vol,
            labourFactorial:     labourFac,
            equipmentFactorial:  equipFac,
            materialFactorial:   matFac,
            labourUnitRate:      labourRate,
            equipmentUnitRate:   equipRate,
            materialUnitRate:    matRate,
            totalRateManPowers:  removeCurrency(manPowerTotal),
            totalRateEquipments: removeCurrency(equipTotal),
            totalRateMaterials:  removeCurrency(matTotal),
            wbs_level3:          wbs3,
            work_element:        workEl,
            _token:              $('meta[name="csrf-token"]').attr('content'),
        };
    }

    // ─── Single-row autosave ───────────────────────────────────────────────────

    function autosaveRow($row) {
        // Skip rows that belong to a different discipline (read-only from this user's POV)
        var rowScope = $row.attr('data-work-scope');
        if (rowScope && currentUserDiscipline && rowScope !== currentUserDiscipline) return;

        var uid = ($row.find('.js-unique-identifier').val() || '').trim();
        if (!uid) {
            // Old DB row without unique_identifier — assign one so it becomes broadcastable
            generateUniqueIdentifier($row);
            uid = $row.find('.js-unique-identifier').val();
            if (!uid) return;
        }

        clearTimeout(saveQueue[uid]);
        saveQueue[uid] = setTimeout(function () {
            var payload = collectRowData($row);
            if (!payload.workItem) {
                delete saveQueue[uid];
                return; // row has no work item selected yet
            }

            trackPending(+1);
            $row.find('.js-row-save-indicator').addClass('saving');

            var request = $.ajax({
                url:      '/project/' + projectId + '/estimate-discipline/autosave',
                type:     'POST',
                dataType: 'json',
                timeout:  10000,

                data:     payload,
                success: function (res) {
                    if (res.status === 200) {
                        $row.attr('data-persisted', 'true');
                        $row.find('.js-row-save-indicator').removeClass('saving').addClass('saved');
                        setTimeout(function () { $row.find('.js-row-save-indicator').removeClass('saved'); }, 1500);
                        // Broadcast to other users via Yjs CRDT
                        if (window.EstimateCollab && res.payload) {
                            window.EstimateCollab.setRow(res.uid, res.payload);
                        }
                    } else {
                        showStatus('error');
                    }
                },
                error: function () { showStatus('error'); }
            });
            request.always(function () {
                trackPending(-1);
                delete saveQueue[uid];
            });
        }, AUTOSAVE_DELAY);
    }

    // Flush all pending debounces immediately (called before Publish)
    function flushAllPending() {
        $form.find('.js-row-item-estimate').each(function () {
            var uid = ($(this).find('.js-unique-identifier').val() || '').trim();
            if (saveQueue[uid]) {
                clearTimeout(saveQueue[uid]);
                delete saveQueue[uid];
                autosaveRowNow($(this));
            }
        });
    }

    function autosaveRowNow($row) {
        var rowScope = $row.attr('data-work-scope');
        if (rowScope && currentUserDiscipline && rowScope !== currentUserDiscipline) return;

        var payload = collectRowData($row);
        if (!payload.workItem) return;
        trackPending(+1);
        $.ajax({
            url:      '/project/' + projectId + '/estimate-discipline/autosave',
            type:     'POST',
            dataType: 'json',
            timeout:  10000,
            data:     payload,
            success: function (res) {
                if (res.status === 200) $row.attr('data-persisted', 'true');
            }
        }).always(function () { trackPending(-1); });
    }

    // ─── Delete row ────────────────────────────────────────────────────────────

    $(document).on('click', '.js-delete-work-item', function () {
        var $row       = $(this).closest('tr');
        var uid        = ($row.find('.js-unique-identifier').val() || '').trim();
        var persisted  = $row.attr('data-persisted') === 'true';

        clearTimeout(saveQueue[uid]);
        delete saveQueue[uid];
        $row.remove();
        bindBeforeUnloadEvent();
        setContingencyTotal();

        if (persisted && uid) {
            $.ajax({
                url:     '/project/' + projectId + '/estimate-discipline/row/' + uid,
                type:    'DELETE',
                data:    { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    // Broadcast deletion to other users via Yjs
                    if (window.EstimateCollab) {
                        window.EstimateCollab.removeRow(uid);
                    }
                },
            });
        }
    });

    // ─── Add row ───────────────────────────────────────────────────────────────

    $(document).on('click', '.js-add-work-item-element', function () {
        var _this    = $(this);
        var template = $('#js-template-table-work_item_column').html();
        var uid      = generateId();
        var data     = {
            wbsLevel3:        _this.data('id'),
            workElement:      _this.data('work-element'),
            uniqueIdentifier: uid,
        };
        var $temp = $(Mustache.render(template, data));
        $temp.attr('data-uid', uid).attr('data-persisted', 'false');

        if (_this.hasClass('.js-button-work-element')) {
            $temp.insertAfter(_this.closest('.js-column-work-element'));
        } else {
            $temp.insertAfter(_this.closest('tr'));
        }
        workItemSelectInit($temp.find('.js-select-work-items'));
        setWhiteBackground(document.querySelector('.table-overflow'));
        bindBeforeUnloadEvent();
        setContingencyTotal();
        checkInputVol();
    });

    // ─── Work item select ──────────────────────────────────────────────────────

    var workItemSelected = null;
    $('.js-select-work-items').select2();

    $(document).on('select2:select', '.js-select-work-items', function (e) {
        var _this          = $(this);
        var $row           = _this.closest('tr');
        var selectedOption = e.params.data;
        var text           = selectedOption.text.replace(/ - \(REVIEWED\)| - \(DRAFT\)/, '');
        workItemSelected   = selectedOption;

        // Reposition dropdown if it overflows
        var $select2      = _this.data('select2').$container;
        var $tableRes     = $select2.closest('.table-responsive');
        var offset        = $select2.offset();
        if (offset.left + $select2.outerWidth() > $tableRes.width()) {
            $select2.addClass('select2-repositioned');
        } else {
            $select2.removeClass('select2-repositioned');
        }

        _this.attr('data-cost-man-power', selectedOption.manPowersTotalRateInt || 0);
        _this.attr('data-cost-tools',     selectedOption.equipmentToolsRateInt  || 0);
        _this.attr('data-cost-material',  selectedOption.materialsRateInt       || 0);

        countTotalWorkItem(_this, selectedOption);

        var $col = _this.closest('td');
        $col.find('.js-work-item-text').find('span').text(text).end().removeClass('d-none');
        _this.select2('destroy').hide();

        $row.find('.js-vol-result-ajax').text(selectedOption.unit || '');
        $row.find('.js-work-item-text').attr('data-total', selectedOption.totalWorkItemRate || 0);
        $row.find('.js-work-item-man-power-cost').text(selectedOption.manPowersTotalRate || '');
        $row.find('.js-work-item-equipment-cost').text(selectedOption.equipmentToolsRate  || '');
        $row.find('.js-work-item-material-cost').text(selectedOption.materialsRate        || '');
        $row.find('.js-input-vol').removeAttr('disabled').val('');
        $row.find('.js-input-labor_factorial, .js-input-equipment_factorial, .js-input-material_factorial').val('');

        toggleInfoIcon($row, '.js-work-item-man-power-cost-modal', selectedOption.manPowersTotalRateInt,   selectedOption.id);
        toggleInfoIcon($row, '.js-work-item-material-cost-modal',  selectedOption.materialsRateInt,         selectedOption.id);
        toggleInfoIcon($row, '.js-work-item-equipment-cost-modal', selectedOption.equipmentToolsRateInt,   selectedOption.id);

        bindBeforeUnloadEvent();
        setContingencyTotal();
        autosaveRow($row); // autosaveRow generates a UID if the row doesn't have one yet
    });

    function toggleInfoIcon($row, selector, rate, id) {
        var $icon = $row.find(selector);
        if (parseFloat(rate) > 0) {
            $icon.removeClass('d-none').data('id', id);
        } else {
            $icon.addClass('d-none');
        }
    }

    function workItemSelectInit(el) {
        var _this          = $(el);
        if (_this.data('select2')) _this.select2('destroy');
        var dropdownParent = document.fullscreenElement ? $('.js-fullscreen-element') : '';
        _this.select2({
            minimumInputLength: 3,
            dropdownParent:     dropdownParent,
            placeholder:        'Please Select Work Item',
            allowClear:         true,
            width:              '100%',
            ajax: {
                url:            _this.data('url') || '/getWorkItems',
                delay:          250,
                cache:          true,
                data:           function (p) { return { q: p.term }; },
                processResults: function (resp) { return { results: resp || [] }; },
                transport: function (params, success, failure) {
                    // Use jQuery AJAX but ensure failure resolves to empty results
                    var request = $.ajax(params);
                    request.then(function (data) { success(data); }).fail(function () { success([]); });
                    return request;
                }
            },
        });
    }

    $(document).on('click', '.js-work-item-text', function () {
        var $parent  = $(this).closest('td');
        var $select2 = $parent.find('.js-select-work-items');
        $select2.closest('span').removeClass('d-none');
        $select2.trigger('select2:open');
        workItemSelectInit($select2);
        $(this).addClass('d-none');
    });

    // ─── Input change → autosave ───────────────────────────────────────────────

    $(document).on('change keyup', '.js-input-vol', function () {
        var $row = $(this).closest('tr');
        countTotalWorkItem($(this), workItemSelected);
        bindBeforeUnloadEvent();
        setContingencyTotal();
        checkInputVol();
        autosaveRow($row);
    });

    $(document).on('change keyup', '.js-input-labor_factorial, .js-input-equipment_factorial, .js-input-material_factorial', function () {
        var $row = $(this).closest('tr');
        countTotalWorkItem($(this), workItemSelected);
        setContingencyTotal();
        bindBeforeUnloadEvent();
        autosaveRow($row);
    });

    // ─── Contingency auto-save ─────────────────────────────────────────────────

    var contingencyTimer;
    $('.js-input-contingency').on('keyup change', function () {
        setContingencyTotal();
        clearTimeout(contingencyTimer);
        var val = $(this).val();
        contingencyTimer = setTimeout(function () {
            $.post('/project/' + projectId + '/estimate-discipline/contingency', {
                contingency: val,
                _token: $('meta[name="csrf-token"]').attr('content'),
            });
        }, 1000);
    });

    // ─── Publish ───────────────────────────────────────────────────────────────

    $('.js-btn-publish').on('click', function (e) {
        e.preventDefault();
        if (pendingCount > 0) {
            // Wait for pending saves before opening modal
            var check = setInterval(function () {
                if (pendingCount === 0) {
                    clearInterval(check);
                    $('.js-modal-confirm-publish').modal('show');
                }
            }, 200);
            return;
        }
        $('.js-modal-confirm-publish').modal('show');
    });

    $(document).on('click', '.js-confirm-publish', function () {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Publishing…');

        $.ajax({
            url:  '/project/' + projectId + '/estimate-discipline/publish',
            type: 'POST',
            data: {
                contingency: $('.js-input-contingency').val(),
                _token:      $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                if (data.status === 200) {
                    $(window).off('beforeunload');
                    $('.js-modal-confirm-publish').modal('hide');
                    try { notification('success', data.message); } catch (e) {}
                    setTimeout(function () { window.location.href = '/project/' + projectId; }, 1000);
                } else {
                    try { notification('danger', data.message, 'fa fa-frown-o', 'Error'); } catch (e) {}
                    $btn.prop('disabled', false).html('<i class="fa fa-paper-plane me-1"></i>Publish');
                }
            },
            error: function () {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane me-1"></i>Publish');
            },
        });
    });

    // ─── Counting / display helpers ────────────────────────────────────────────

    function countTotalPrice() {
        var total = 0;
        $form.find('.js-row-item-estimate').each(function () {
            var $row  = $(this);
            var vol   = parseFloat($row.find('.js-input-vol').val()) || 1;
            var $sel  = $row.find('.js-select-work-items');
            var $txt  = $row.find('.js-work-item-text');
            var lf    = parseFloat($row.find('.js-input-labor_factorial').val())     || 1;
            var ef    = parseFloat($row.find('.js-input-equipment_factorial').val()) || 1;
            var mf    = parseFloat($row.find('.js-input-material_factorial').val())  || 1;
            var lRate = parseFloat($sel.attr('data-cost-man-power') || $txt.attr('data-cost-man-power')) || 0;
            var eRate = parseFloat($sel.attr('data-cost-tools')     || $txt.attr('data-cost-tools'))     || 0;
            var mRate = parseFloat($sel.attr('data-cost-material')  || $txt.attr('data-cost-material'))  || 0;
            total += (lRate * lf + eRate * ef + mRate * mf) * vol;
        });
        return total;
    }

    function setContingencyTotal() {
        var total      = countTotalPrice();
        var contingPct = parseFloat($('.js-input-contingency').val()) / 100 || 0;
        var conting    = total * contingPct;
        $('.js-work-item-total-contingency').text(toCurrency(conting));
        $('.js-total-cost-estimate').text(toCurrency(conting + total));
    }

    function countTotalWorkItem($el, obj) {
        var $row  = $el.closest('tr');
        var vol   = parseFloat($row.find('.js-input-vol').val()) || 1;
        var $sel  = $row.find('.js-select-work-items');
        var $txt  = $row.find('.js-work-item-text');
        var lRate = parseFloat($sel.attr('data-cost-man-power') || $txt.attr('data-cost-man-power')) || 0;
        var eRate = parseFloat($sel.attr('data-cost-tools')     || $txt.attr('data-cost-tools'))     || 0;
        var mRate = parseFloat($sel.attr('data-cost-material')  || $txt.attr('data-cost-material'))  || 0;
        var lf    = parseFloat($row.find('.js-input-labor_factorial').val())     || 1;
        var ef    = parseFloat($row.find('.js-input-equipment_factorial').val()) || 1;
        var mf    = parseFloat($row.find('.js-input-material_factorial').val())  || 1;

        $row.find('.js-total-work-item-rate span').text(toCurrency((lRate * lf + eRate * ef + mRate * mf) * vol));
        $row.find('.js-work-item-man-power-cost').text(toCurrency(lRate * lf));
        $row.find('.js-work-item-equipment-cost').text(toCurrency(eRate * ef));
        $row.find('.js-work-item-material-cost').text(toCurrency(mRate * mf));
    }

    function checkInputVol() {
        $('.js-input-vol').each(function () {
            $(this).css('background-color', $(this).val() === '' ? '#f3ca63' : 'transparent');
        });
    }

    function generateUniqueIdentifier($row) {
        var $uid = $row.find('.js-unique-identifier');
        if (!$uid.val().trim()) {
            var id = generateId();
            $uid.val(id);
            $row.attr('data-uid', id);
        }
    }

    // ─── Work item detail modal ────────────────────────────────────────────────

    $(document).on('click', '.js-open-modal-detail', function (e) {
        $('#modal-loading').modal('show');
        var id       = $(this).data('id');
        var type     = $(this).data('type');
        var template = $('#js-template-modal-detail-estimate').html();
        $.ajax({
            url:  '/getDetailWorkItem',
            data: { id: id, type: type },
            success: function (item) {
                if (item.status === 200) {
                    $('.js-modal-detail-estimate-template').append(Mustache.render(template, item.data));
                    $('#workItemDetailModal').modal('show');
                }
            },
            complete: function () { $('#modal-loading').modal('hide'); },
        });
    });

    $(document).on('hidden.bs.modal', '#workItemDetailModal', function () { $(this).remove(); });

    // ─── Collapse / Expand ────────────────────────────────────────────────────

    $(document).on('click', '.js-minimize', function () {
        var $parent = $(this).closest('tr');
        $(this).addClass('d-none').siblings('.js-maximize').removeClass('d-none');
        $parent.nextAll('tr').each(function () {
            if (shouldStop($parent, $(this))) return false;
            $(this).addClass('d-none');
        });
    });

    $(document).on('click', '.js-maximize', function () {
        var $parent = $(this).closest('tr');
        $(this).addClass('d-none').siblings('.js-minimize').removeClass('d-none');
        $parent.nextAll('tr').each(function () {
            if (shouldStop($parent, $(this))) return false;
            $(this).removeClass('d-none');
        });
    });

    function shouldStop($parent, $sibling) {
        if ($parent.hasClass('js-column-work-element')) {
            return $sibling.hasClass('js-column-work-element') || $sibling.hasClass('js-column-discipline') || $sibling.hasClass('js-column-location');
        }
        if ($parent.hasClass('js-column-discipline')) {
            return $sibling.hasClass('js-column-discipline') || $sibling.hasClass('js-column-location');
        }
        if ($parent.hasClass('js-column-location')) {
            return $sibling.hasClass('js-column-location');
        }
        return false;
    }

    $('.js-btn-collapse-all').on('click', function (e) {
        e.preventDefault();
        var $tbody = $('.js-table-body-work-item-item');
        $tbody.children('tr').removeClass('d-none');
        $tbody.find('.js-minimize').removeClass('d-none');
        $tbody.find('.js-maximize').addClass('d-none');
        $tbody.find('.js-column-location .js-minimize').each(function () { $(this).trigger('click'); });
        $(this).addClass('d-none');
        $('.js-btn-expand-all').removeClass('d-none');
    });

    $('.js-btn-expand-all').on('click', function (e) {
        e.preventDefault();
        var $tbody = $('.js-table-body-work-item-item');
        $tbody.find('.js-column-location .js-maximize:not(.d-none)').each(function () { $(this).trigger('click'); });
        $tbody.find('.js-column-discipline .js-minimize, .js-column-work-element .js-minimize').removeClass('d-none');
        $tbody.find('.js-column-discipline .js-maximize, .js-column-work-element .js-maximize').addClass('d-none');
        $(this).addClass('d-none');
        $('.js-btn-collapse-all').removeClass('d-none');
    });

    // ─── Fullscreen ────────────────────────────────────────────────────────────

    function enterFullscreen() {
        var table = document.querySelector('.js-fullscreen-element');
        $( table).find('.select2').each(function () {
            var val   = $(this).val();
            var label = $(this).closest('td').find('.js-work-item-text span').text();
            if ((val === null || val === '') && label.trim().length < 1) {
                this.closest('tr').remove();
            }
        });
        table.requestFullscreen();
        var $el = $('.js-fullscreen-element');
        $el.css({ 'background-color': '#f4f7fb', padding: '0' });
        $el.find('.table-overflow').css('height', 'calc(92vh - 38px)');
        setWhiteBackground(table);
        $('.js-fullscreen i').removeClass('fa-expand').addClass('fa-compress');
        $('.js-fullscreen-label').text('Exit Fullscreen');
        $('.js-fullscreen-indicator').removeClass('d-none').addClass('d-flex');
        $( table).find('.js-select-work-items').each(function () { workItemSelectInit(this); });
        table.scrollLeft = 0;
    }

    function exitFullscreen() {
        if (document.fullscreenElement) document.exitFullscreen();
    }

    $('.js-fullscreen').on('click', function () {
        document.fullscreenElement ? exitFullscreen() : enterFullscreen();
    });

    $(document).on('fullscreenchange', function () {
        if (!document.fullscreenElement) {
            var $el = $('.js-fullscreen-element');
            $el.css({ 'padding-left': '0.8em' });
            $el.find('.table-overflow').css('height', '60vh');
            $('.js-manual-notify').remove();
            $('.js-fullscreen i').removeClass('fa-compress').addClass('fa-expand');
            $('.js-fullscreen-label').text('Fullscreen');
            $('.js-fullscreen-indicator').addClass('d-none').removeClass('d-flex');
        }
    });

    $(document).on('keydown', function (e) {
        var tag = (e.target.tagName || '').toLowerCase();
        if (tag === 'input' || tag === 'textarea' || tag === 'select') return;
        if (e.key === 'f' || e.key === 'F') {
            document.fullscreenElement ? exitFullscreen() : enterFullscreen();
        }
    });

    setInterval(function () {
        if (document.fullscreenElement) $('.js-manual-notify').addClass('fadeOut');
    }, 6000);

    function setWhiteBackground(table) {
        (table || document).querySelectorAll('.js-row-item-estimate').forEach(function (el) {
            el.style.backgroundColor = 'white';
        });
    }

    // ─── Real-time: update a row in the DOM from a broadcast payload ──────────

    function updateRowInDOM(payload) {
        var uid    = (payload.uniqueIdentifier || '').trim();
        var $row   = $('[data-uid="' + uid + '"]');

        if ($row.length === 0) {
            // Row added by another user — find the correct work-element section
            var $section = $('.js-column-work-element[data-wbs-level3-id="' + payload.wbs_level3_id + '"]');
            if ($section.length) {
                var template = $('#js-template-table-work_item_column').html();
                var tplData  = buildTemplateData(payload);
                var $newRow  = $(Mustache.render(template, tplData));
                $newRow.attr('data-uid', uid).attr('data-persisted', 'true').attr('data-work-scope', payload.workScope);

                // Insert after the last work-item in this section, not after the header
                var $lastInSection = $section
                    .nextUntil('.js-column-work-element, .js-column-discipline, .js-column-location')
                    .filter('.js-row-item-estimate')
                    .last();
                ($lastInSection.length ? $lastInSection : $section).after($newRow);

                postInsertRow($newRow, payload);
                flashRow($newRow);
                setContingencyTotal();
            }
            return;
        }

        // Existing row — patch the cells that can change
        $row.find('.js-input-vol').val(payload.volume);
        $row.find('.js-vol-result-ajax').text(payload.unit || '');
        $row.find('.js-work-item-man-power-cost').text(payload.laborUnitRateTotalStr || '');
        $row.find('.js-work-item-equipment-cost').text(payload.toolUnitRateTotalStr  || '');
        $row.find('.js-work-item-material-cost').text(payload.materialUnitRateTotalStr || '');
        $row.find('.js-input-labor_factorial').val(payload.labourFactorial || '');
        $row.find('.js-input-equipment_factorial').val(payload.equipmentFactorial || '');
        $row.find('.js-input-material_factorial').val(payload.materialFactorial || '');
        $row.find('.js-total-work-item-rate span').text(payload.totalCostStr || '');
        $row.find('.js-work-item-text span').text(payload.workItemDescription || '');
        $row.attr('data-work-scope', payload.workScope);
        // Keep unit rates in sync so local recalculations remain accurate
        $row.find('.js-select-work-items')
            .attr('data-cost-man-power', payload.laborUnitRate)
            .attr('data-cost-tools',     payload.toolUnitRate)
            .attr('data-cost-material',  payload.materialUnitRate);
        $row.find('.js-work-item-text')
            .attr('data-cost-man-power', payload.laborUnitRate)
            .attr('data-cost-tools',     payload.toolUnitRate)
            .attr('data-cost-material',  payload.materialUnitRate);

        showEditedBy($row, payload.userName);
        flashRow($row);
        setContingencyTotal();
    }

    function removeRowFromDOM(uid) {
        var $row = $('[data-uid="' + uid + '"]');
        $row.addClass('row-removing');
        setTimeout(function () { $row.remove(); setContingencyTotal(); }, 300);
    }

    function flashRow($row) {
        $row.addClass('row-flash');
        setTimeout(function () { $row.removeClass('row-flash'); }, 1200);
    }

    function showEditedBy($row, name) {
        var $badge = $row.find('.js-edited-by-badge');
        if (!$badge.length) {
            $badge = $('<span class="edited-by-badge js-edited-by-badge"></span>');
            $row.find('td:first').append($badge);
        }
        $badge.text(name).fadeIn(150);
        clearTimeout($row.data('editedByTimer'));
        $row.data('editedByTimer', setTimeout(function () { $badge.fadeOut(400); }, 3000));
    }

    function buildTemplateData(payload) {
        var lf = payload.labourFactorial    || 1;
        var ef = payload.equipmentFactorial || 1;
        var mf = payload.materialFactorial  || 1;
        return {
            wbsLevel3:          payload.wbs_level3_id,
            workElement:        payload.work_element_id,
            uniqueIdentifier:   payload.uniqueIdentifier,
            workItemId:         payload.workItemId,
            workItemDescription: payload.workItemDescription,
            workItemVolume:     payload.volume,
            unit:               payload.unit,
            manPowerCostStr:    payload.laborUnitRateTotalStr,
            equipmentCostStr:   payload.toolUnitRateTotalStr,
            materialCostStr:    payload.materialUnitRateTotalStr,
            manPowerCost:       payload.laborUnitRate * lf,
            equipmentCost:      payload.toolUnitRate  * ef,
            materialCost:       payload.materialUnitRate * mf,
            manPowerCostRate:   payload.laborUnitRate,
            equipmentCostRate:  payload.toolUnitRate,
            materialCostRate:   payload.materialUnitRate,
            laborFactorial:     lf,
            equipmentFactorial: ef,
            materialFactorial:  mf,
            total:              payload.totalCostStr,
            isShowMaterial:     payload.materialUnitRate  > 0 ? 'd-block' : 'd-none',
            isShowEquipment:    payload.toolUnitRate      > 0 ? 'd-block' : 'd-none',
            itemVersion:        1,
        };
    }

    function postInsertRow($row, payload) {
        var $sel = $row.find('.js-select-work-items');
        $sel.closest('.js-select2-select-work-item-temp').addClass('d-none');
        $row.find('.js-work-item-text').removeClass('d-none');
        $row.find('.js-input-vol').removeAttr('disabled');
        workItemSelectInit($sel);
        setWhiteBackground(document.querySelector('.table-overflow'));
    }

    // ─── Real-time: connection + presence ─────────────────────────────────────

    function setConnectionDot(state) {
        var $dot = $('.js-connection-dot');
        $dot.attr('class', 'js-connection-dot connection-dot connection-' + state);
        var titles = { connected: 'Real-time connected', disconnected: 'Disconnected — changes may not sync', connecting: 'Connecting…' };
        $dot.attr('title', titles[state] || state);
    }

    // ─── Real-time: Yjs CRDT via y-websocket ─────────────────────────────────
    //
    // window.EstimateCollab is provided by collab.js (compiled bundle).
    // It uses Yjs for conflict-free real-time sync, replacing the old
    // Soketi / Laravel Echo broadcast layer.

    if (projectId && window.EstimateCollab) {
        var wsUrl = $form.data('ws-url') || 'ws://localhost:1234';

        window.EstimateCollab
            .connect(projectId, wsUrl)

        window.EstimateCollab.onStatus(function (status) {
            setConnectionDot(
                status === 'connected'   ? 'connected'   :
                status === 'connecting'  ? 'connecting'  : 'disconnected'
            );
        });

        window.EstimateCollab.onRowChange(function (type, uid, payload) {
            uid = (uid || '').trim();
            if (type === 'deleted') {
                removeRowFromDOM(uid);
            } else if (type === 'changed' && payload) {
                payload.uniqueIdentifier = (payload.uniqueIdentifier || uid).trim();
                updateRowInDOM(payload);
            }
        });

        setConnectionDot('connecting');
    } else {
        setConnectionDot('disconnected');
    }

    // ─── Detail table collapse/expand (project detail view) ──────────────────

    $('.js-btn-collapse-all-detail').on('click', function (e) {
        e.preventDefault();
        var $tbody = $('.js-table-body-detail');
        $tbody.children('tr').removeClass('d-none');
        $tbody.find('.js-minimize').removeClass('d-none');
        $tbody.find('.js-maximize').addClass('d-none');
        $tbody.find('.js-column-location .js-minimize').each(function () { $(this).trigger('click'); });
        $(this).addClass('d-none');
        $('.js-btn-expand-all-detail').removeClass('d-none');
    });

    $('.js-btn-expand-all-detail').on('click', function (e) {
        e.preventDefault();
        var $tbody = $('.js-table-body-detail');
        $tbody.find('.js-column-location .js-maximize:not(.d-none)').each(function () { $(this).trigger('click'); });
        $tbody.find('.js-column-discipline .js-minimize, .js-column-work-element .js-minimize').removeClass('d-none');
        $tbody.find('.js-column-discipline .js-maximize, .js-column-work-element .js-maximize').addClass('d-none');
        $(this).addClass('d-none');
        $('.js-btn-collapse-all-detail').removeClass('d-none');
    });

    $(document).on('click', '.js-fullscreen-detail', function () {
        var table = document.querySelector('.js-fullscreen-table');
        if (!table) return;
        if (document.fullscreenElement) {
            document.exitFullscreen();
            $(this).find('i').removeClass('fa-compress').addClass('fa-expand').end().find('span').text('Fullscreen');
        } else {
            table.requestFullscreen().then(function () {
                $('.js-fullscreen-table').css({ 'background-color': '#f4f7fb', 'overflow-y': 'auto', padding: '12px' });
            });
            $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
        }
    });

    // ─── Column-group toggle ───────────────────────────────────────────────────

    $(document).on('click', '.js-toggle-col-group', function () {
        var group  = $(this).data('group');
        var hidden = $(this).hasClass('active');
        $('.col-group-' + group).toggleClass('col-group-hidden', hidden);
        $(this).toggleClass('active', !hidden)
               .find('i').toggleClass('fa-eye-slash', !hidden).toggleClass('fa-eye', hidden);
        fixStickyHeaderOffset && fixStickyHeaderOffset();
    });

    // ─── JS sticky thead ──────────────────────────────────────────────────────

    function updateStickyThead() {
        var $wrap  = $('.js-detail-table-wrap');
        if (!$wrap.length) return;
        var $thead = $wrap.find('.js-full-estimate-table thead');
        if (!$thead.length) return;
        var navH     = $('.page-main-header').outerHeight() || 0;
        var wrapTop  = $wrap.offset().top;
        var wrapH    = $wrap.outerHeight();
        var scrollT  = $(window).scrollTop();
        var theadH   = $thead.outerHeight();
        var shift    = scrollT + navH - wrapTop;
        var frozen   = shift > 0 && shift + theadH < wrapH;
        $thead.css('transform', frozen ? 'translateY(' + shift + 'px)' : '');
        var headerBottom = theadH + (frozen ? shift : 0);
        $wrap.find('.annotation-bubble').each(function () {
            var top = parseFloat($(this).css('top')) || 0;
            $(this).css('visibility', (frozen && top < headerBottom) ? 'hidden' : '');
        });
    }

    updateStickyThead();
    $(window).on('scroll resize', updateStickyThead);

    // ─── Annotation overlay (unchanged) ───────────────────────────────────────

    var _annotateMode  = false;
    var _annotMarkColors = { note: '#6c757d', ok: '#198754', warning: '#ffc107', rejected: '#dc3545', question: '#0dcaf0' };

    function buildAnnotationBubble(noteId, text, markType, posX, posY, reviewerName, readOnly) {
        var mark    = markType || 'note';
        var $bubble = $('<div class="annotation-bubble" tabindex="0"></div>')
            .css({ left: posX + 'px', top: posY + 'px' })
            .attr({ 'data-note-id': noteId || '', 'data-mark': mark });
        var byHtml   = reviewerName ? '<span class="annot-by">by ' + reviewerName + '</span>' : '';
        var safeText = $('<div>').text(text || '').html();
        if (readOnly) {
            $bubble.addClass('annot-readonly').html(
                '<div class="annot-header"><span class="annot-dot-ro" style="background:' + (_annotMarkColors[mark] || '#6c757d') + '"></span>' + byHtml + '</div>' +
                '<div class="annot-body-ro">' + safeText + '</div>');
        } else {
            var dotsHtml = '';
            $.each(_annotMarkColors, function (m, c) {
                dotsHtml += '<span class="annot-dot' + (m === mark ? ' active' : '') + '" data-mark="' + m + '" style="background:' + c + '" title="' + m + '"></span>';
            });
            $bubble.html(
                '<div class="annot-header"><div class="annot-dots">' + dotsHtml + '</div>' + byHtml +
                '<button type="button" class="annot-close" title="Delete">&#x2715;</button></div>' +
                '<textarea class="annot-textarea" placeholder="Write your note here...">' + safeText + '</textarea>' +
                '<div class="annot-footer"><button type="button" class="annot-save-btn">Save</button></div>');
        }
        return $bubble;
    }

    function addBubbleToLayer($layer, noteId, text, markType, posX, posY, reviewerName) {
        var readOnly = $layer.data('readonly') == '1';
        var $bubble  = buildAnnotationBubble(noteId, text, markType, posX, posY, reviewerName, readOnly);
        $layer.append($bubble);
        if (readOnly) { $bubble.on('mousedown click', function (e) { e.stopPropagation(); }); return $bubble; }
        if (!noteId) setTimeout(function () { $bubble.find('.annot-textarea').focus(); }, 60);
        $bubble.find('.annot-header').on('mousedown', function (e) {
            if ($(e.target).hasClass('annot-close') || $(e.target).hasClass('annot-dot')) return;
            e.preventDefault(); e.stopPropagation();
            var sx = e.pageX, sy = e.pageY;
            var sl = parseFloat($bubble.css('left')) || 0, st = parseFloat($bubble.css('top')) || 0;
            $bubble.addClass('dragging');
            $(document).on('mousemove.annotDrag', function (ev) {
                $bubble.css({ left: sl + (ev.pageX - sx) + 'px', top: st + (ev.pageY - sy) + 'px' });
            }).on('mouseup.annotDrag', function () {
                $(document).off('mousemove.annotDrag mouseup.annotDrag');
                $bubble.removeClass('dragging');
            });
        });
        $bubble.on('mousedown click', function (e) { e.stopPropagation(); });
        $bubble.find('.annot-dot').on('click', function (e) {
            e.stopPropagation();
            var newMark = $(this).data('mark');
            $bubble.attr('data-mark', newMark);
            $bubble.find('.annot-dot').removeClass('active');
            $(this).addClass('active');
        });
        $bubble.find('.annot-save-btn').on('click', function (e) {
            e.stopPropagation();
            var $btn      = $(this);
            var noteText  = $bubble.find('.annot-textarea').val().trim();
            if (!noteText) { $bubble.find('.annot-textarea').addClass('is-invalid'); return; }
            $bubble.find('.annot-textarea').removeClass('is-invalid');
            var mark       = $bubble.attr('data-mark') || 'note';
            var existingId = $bubble.attr('data-note-id') || '';
            var pId        = $layer.data('project-id');
            var cx         = parseFloat($bubble.css('left')) || 0;
            var cy         = parseFloat($bubble.css('top'))  || 0;
            $btn.prop('disabled', true).text('Saving…');
            $.ajax({
                url: '/project/' + pId + '/review-note', type: 'POST',
                data: { id: existingId || null, note: noteText, mark_type: mark, position_x: cx, position_y: cy, _token: $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.status == 200) { $bubble.attr('data-note-id', res.note.id); $btn.text('Saved!'); setTimeout(function () { $btn.text('Save'); }, 1500); }
                    else { $btn.text('Failed!'); setTimeout(function () { $btn.text('Save'); }, 2000); }
                },
                error: function () { $btn.text('Error!'); setTimeout(function () { $btn.text('Save'); }, 2000); },
                complete: function () { $btn.prop('disabled', false); },
            });
        });
        $bubble.find('.annot-close').on('click', function (e) {
            e.stopPropagation();
            var existingId = $bubble.attr('data-note-id') || '';
            var pId        = $layer.data('project-id');
            if (existingId) {
                $.ajax({ url: '/project/' + pId + '/review-note/' + existingId, type: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (res) { if (res.status == 200) $bubble.remove(); } });
            } else { $bubble.remove(); }
        });
        return $bubble;
    }

    $(document).on('click', '.js-btn-annotate-toggle', function () {
        _annotateMode = !_annotateMode;
        var $btn   = $(this);
        var $layer = $('.js-annotation-layer');
        if (_annotateMode) {
            $btn.removeClass('btn-outline-warning').addClass('btn-warning');
            $layer.addClass('annotate-mode');
            $btn.html('<i class="fa fa-pencil-alt me-1"></i>Done Annotating');
        } else {
            $btn.removeClass('btn-warning').addClass('btn-outline-warning');
            $layer.removeClass('annotate-mode');
            $btn.html('<i class="fa fa-pencil-alt me-1"></i>Annotate');
        }
    });

    $(document).on('click', '.js-annotation-layer.annotate-mode', function (e) {
        var $layer = $(this);
        addBubbleToLayer($layer, null, '', 'note', e.pageX - $layer.offset().left, e.pageY - $layer.offset().top, null);
    });

    var $annotLayer = $('.js-annotation-layer');
    if ($annotLayer.length) {
        $.ajax({
            url:  '/project/' + $annotLayer.data('project-id') + '/annotations',
            type: 'GET',
            success: function (res) {
                if (res.status == 200) {
                    $.each(res.notes, function (i, note) {
                        addBubbleToLayer($annotLayer, note.id, note.note, note.mark_type,
                            parseFloat(note.position_x) || 0, parseFloat(note.position_y) || 0,
                            note.reviewer ? (note.reviewer.profiles ? note.reviewer.profiles.full_name : note.reviewer.user_name) : '');
                    });
                }
            },
        });
    }

});
