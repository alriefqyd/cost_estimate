/**
 * Save Estimate All Discipline in Work Breakdown Structure Js in work item section (Java doc with work items)
 */
$(function(){
    var workItemSelected = null;
    $('.js-select-work-items').select2();
    $(document).on('select2:select','.js-select-work-items', function(e){
        var _this = $(this);
        var _parent_row = _this.closest('tr');
        var selectedOption = e.params.data;
        var _text = selectedOption.text;
        var _unitVol = selectedOption.vol;
        var _totalRateManPowerStr = selectedOption.manPowersTotalRate;
        var _totalRateEquipmentStr = selectedOption.equipmentToolsRate;
        var _totalRateMaterialStr = selectedOption.materialsRate;
        var _totalRateWorkItem = selectedOption.totalWorkItemRate;
        var _totalRateWorkItemStr = selectedOption.totalWorkItemRateStr;
        var _totalRateManPower = selectedOption.manPowersTotalRateInt;
        var _totalRateEquipment = selectedOption.equipmentToolsRateInt;
        var _totalRateMaterial = selectedOption.materialsRateInt;
        workItemSelected = selectedOption;
        var _column = _this.closest('td');
        var _text_column = _column.find('.js-work-item-text');
        var $select2 = _this.data('select2').$container;
        var $tableResponsive = $select2.closest('.table-responsive');
        var tableResponsiveWidth = $tableResponsive.width();
        var select2Offset = $select2.offset();
        var select2Width = $select2.outerWidth();

        // Calculate if the dropdown is going to go beyond the right edge of the container
        if (select2Offset.left + select2Width > tableResponsiveWidth) {
            $select2.addClass('select2-repositioned');
        } else {
            $select2.removeClass('select2-repositioned');
        }

        _this.attr('data-cost-man-power', _totalRateManPower);
        _this.attr('data-cost-tools', _totalRateEquipment);
        _this.attr('data-cost-material', _totalRateMaterial);

        countTotalWorkItem(_this,selectedOption);
        _text_column.find('span').text(_text);
        _text_column.removeClass('d-none');
        _this.select2('destroy'); // Destroy the Select2 instance
        _this.hide(); // Hide the element
        _parent_row.find('.js-vol-result-ajax').text(_unitVol);
        _parent_row.find('.js-work-item-text').attr('data-total',_totalRateWorkItem);
        _parent_row.find('.js-work-item-man-power-cost').text(_totalRateManPowerStr);
        _parent_row.find('.js-work-item-equipment-cost').text(_totalRateEquipmentStr);
        _parent_row.find('.js-work-item-material-cost').text(_totalRateMaterialStr);
        _parent_row.find('.js-input-vol').removeAttr('disabled');
        _parent_row.find('.js-input-vol').val('');
        _parent_row.find('.js-input-labor_factorial').val('');
        _parent_row.find('.js-input-equipment_factorial').val('');
        _parent_row.find('.js-input-material_factorial').val('');

        if(_totalRateManPower > 0) {
            _parent_row.find('.js-work-item-man-power-cost-modal').removeClass('d-none');
            _parent_row.find('.js-work-item-man-power-cost-modal').data('id',selectedOption.id);
        } else {
            _parent_row.find('.js-work-item-man-power-cost-modal').addClass('d-none');
        }
        if(_totalRateMaterial > 0) {
            _parent_row.find('.js-work-item-material-cost-modal').removeClass('d-none');
            _parent_row.find('.js-work-item-material-cost-modal').data('id',selectedOption.id);
        } else {
            _parent_row.find('.js-work-item-material-cost-modal').addClass('d-none');
        }
        if(_totalRateEquipment > 0) {
            _parent_row.find('.js-work-item-equipment-cost-modal').removeClass('d-none');
            _parent_row.find('.js-work-item-equipment-cost-modal').data('id',selectedOption.id);
        } else {
            _parent_row.find('.js-work-item-equipment-cost-modal').addClass('d-none');
        }
        bindBeforeUnloadEvent();
    });

    $(document).on('click','.js-work-item-text',function(){
        var _this = $(this);
        var _parent = _this.closest('td');
        var _select2 = _parent.find('.js-select-work-items');
        _select2.closest('span').removeClass('d-none');
        _select2.trigger('select2:open');
        workItemSelectInit(_select2);
        _this.addClass('d-none');
    });

    $(document).on('click','.js-delete-work-item', function(){
        var _this = $(this);
        var _parent = _this.closest('tr');
        _parent.remove();
        bindBeforeUnloadEvent();
    });

    var workItemSelectInit = function (el) {
        var _this = $(el);
        if (_this.data("select2")) _this.select2("destroy");
        _this.closest('table').siblings();
        var dropdown_parent = ''
        if (document.fullscreenElement) {
            dropdown_parent = $('.js-card-parent');
        }

        _this.select2({
            minimumInputLength: 3,
            dropdownParent: dropdown_parent,
            placeholder: "Please Select Work Item",
            allowClear: true,
            width: '100%',
            ajax: {
                url: _this.data('url'),
                data: function (params) {
                    return {
                        q: params.term
                    }
                },
                processResults: function (resp) {
                    // _dataAdvanceWorkItem = resp;
                    return {
                        results: resp
                    }
                },
            }
        });
    }


    $('.js-save-estimate-discipline').on('click',function (e) {
        e.preventDefault();
        var _this = $(this);
        var _body_work_item = $('.js-body-work-item-table');
        var _array_estimate_disciplines = []
        var _form = $('.js-form-estimate-discipline');
        var _project_id = _form.data('id');
        var _url = '/project/' + _project_id + '/estimate-discipline/store'


        var _work_items = _form.find('.js-select-work-items');

        $.each(_work_items, function (){
            var _parent = $(this).closest('tr');
            var _work_item = $(this).val();
            var _work_item_text = _parent.find('.js-select-work-items option:selected').text();
            var _vol = _parent.find('.js-input-vol').val();
            var _unit = _parent.find('.js-vol-result-ajax').text();
            var template = $('#js-template-work-item').html();
            var _labour_factorial = _parent.find('.js-input-labor_factorial').val();
            var _equipment_factorial = _parent.find('.js-input-equipment_factorial').val();
            var _material_factorial = _parent.find('.js-input-material_factorial').val();
            var templateModal = $('#js-template-modal-work-item').html();
            var _labor_cost_total_rate = _parent.find('.js-work-item-man-power-cost').text();
            var _labor_unit_rate = _parent.find('.js-select-work-items').attr('data-cost-man-power');
            var _tool_unit_rate_total = _parent.find('.js-work-item-equipment-cost').text();
            var _tool_unit_rate = _parent.find('.js-select-work-items').attr('data-cost-tools');
            var _material_unit_rate = _parent.find('.js-select-work-items').attr('data-cost-material');
            var _material_unit_rate_total = _parent.find('.js-work-item-material-cost').text();
            _labor_cost_total_rate = removeBlankSpace(_labor_cost_total_rate)
            _tool_unit_rate_total = removeBlankSpace(_tool_unit_rate_total)
            _material_unit_rate_total = removeBlankSpace(_material_unit_rate_total)
            var _wbs_level3_id = _parent.find('.js-wbs_level3_id').val();
            var _work_element_id = _parent.find('.js-work_element_id').val();
            _labor_cost_total_rate = removeCurrency(_labor_cost_total_rate)
            _tool_unit_rate_total = removeCurrency(_tool_unit_rate_total)
            _material_unit_rate_total = removeCurrency(_material_unit_rate_total)

            var _data = {
                "idx": generateId(),
                "workItem":_work_item,
                "workItemText":_work_item_text,
                "vol":_vol ? _vol : 0,
                "unit":_unit,
                "totalRateManPowers":_labor_cost_total_rate,
                "totalRateEquipments":_tool_unit_rate_total,
                "totalRateMaterials":_material_unit_rate_total,
                "workItemId":_work_item,
                "labourFactorial": _labour_factorial,
                "equipmentFactorial":_equipment_factorial,
                "materialFactorial":_material_factorial,
                "labourUnitRate":_labor_unit_rate,
                "materialUnitRate":_material_unit_rate,
                "equipmentUnitRate":_tool_unit_rate,
                "wbs_level3":_wbs_level3_id,
                "work_element":_work_element_id,


            }
            _array_estimate_disciplines.push(_data);
        })

        var _data = {
            'work_items' : _array_estimate_disciplines,
            'project_id' : _project_id,
        }

        $.ajax({
            url: _url,
            data : _data,
            type : 'POST',
            success : function(data){
                if(data.status === 200){
                    if (document.fullscreenElement) {
                        showNotification('.js-fullscreen-element', 'Your data was successfully saved');
                    } else {
                        notification('success',data.message)
                    }
                    $(window).off('beforeunload');

                } else {
                    notification('danger',data.message,'fa fa-frown-o','Error')
                }
            }
        })

    });

    function showNotification(element, message) {
        var notificationElement = $('<div data-notify="container" class="col-xs-11 col-sm-4 js-manual-notify alert alert-success notify-alert animated fadeIn" role="alert" data-notify-position="top-right" style="display: inline-block; margin: 0px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1031; top: 20px; right: 20px;" data-closing="true">')
            .text(message)
            .appendTo($(element));

        notificationElement.notify({
            type: 'info',
            placement: {
                from: 'top',
                align: 'center'
            },
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            showDuration: 10000, // Set the show duration to 10 seconds (10,000 milliseconds)
            hideDuration: 1000   // Set the hide duration to 1 second (1,000 milliseconds)
        });

    }

    $(document).on('click','.js-add-work-item-element',function(){
        var _this = $(this)
        var _template = $('#js-template-table-work_item_column').html()
        var _data = {
            'wbsLevel3' : _this.data('id'),
            'workElement' : _this.data('work-element')
        }
        var _temp =  $(Mustache.render(_template,_data));
        if(_this.hasClass('.js-button-work-element')){
            _temp.insertAfter(_this.closest('.js-column-work-element'));
        } else {
            _temp.insertAfter(_this.closest('tr'));
        }
        var _select2 = _temp.find('.js-select-work-items');
        workItemSelectInit(_select2)
        setWhiteBackground(document.querySelector('.table-overflow'));
        bindBeforeUnloadEvent()
    });

    $(document).on('change keyup','.js-input-vol', function(){
        var _this = $(this);
        var _parent_row = _this.closest('tr');
        countTotalWorkItem(_this, workItemSelected);
        bindBeforeUnloadEvent();
    });

    $(document).on('change keyup','.js-input-labor_factorial, .js-input-equipment_factorial, .js-input-material_factorial', function(){
        var _this = $(this);
        countTotalWorkItem(_this, workItemSelected);
        bindBeforeUnloadEvent();
    });

    $(document).on('click','.js-minimize',function(){
        var _this = $(this);
        var _parent = _this.closest('tr')
        var _next_parent = _this.closest('tr').nextAll('tr')

        _this.addClass('d-none')
        _this.siblings('.js-maximize').removeClass('d-none')
        // generateStrippedTable(_this.closest('table'))
        _next_parent.each(function(){
            if(_parent.hasClass('js-column-work-element')){
                if($(this).hasClass('js-column-work-element')
                || $(this).hasClass('js-column-discipline')
                || $(this).hasClass('js-column-location')){
                    return false;
                }
            }
            if(_parent.hasClass('js-column-discipline')){
                if($(this).hasClass('js-column-discipline')
                || $(this).hasClass('js-column-location')){
                    return false;
                }
            }
            if(_parent.hasClass('js-column-location')){
                if($(this).hasClass('js-column-location')){
                    return false;
                }
            }
            $(this).addClass('d-none')
        })
    })

    $(document).on('click','.js-maximize',function(){
        var _this = $(this);
        var _parent = _this.closest('tr')
        var _next_parent = _this.closest('tr').nextAll('tr')

        _this.addClass('d-none')
        _this.siblings('.js-minimize').removeClass('d-none')

        // generateStrippedTable(_this.closest('table'))
        _next_parent.each(function(){

            if(_parent.hasClass('js-column-work-element')){
                if($(this).hasClass('js-column-work-element')
                    || $(this).hasClass('js-column-discipline')
                    || $(this).hasClass('js-column-location')){
                    return false;
                }
            }
            if(_parent.hasClass('js-column-discipline')){
                if($(this).hasClass('js-column-discipline')
                    || $(this).hasClass('js-column-location')){
                    return false;
                }
            }
            if(_parent.hasClass('js-column-location')){
                if($(this).hasClass('js-column-location')){
                    return false;
                }
            }

            $(this).removeClass('d-none')
        })
    })

    function countTotalWorkItem(_this, obj){
        var _parent = _this.closest('tr');
        var _vol = _parent.find('.js-input-vol').val();
        var _select_work_item = _parent.find('.js-select-work-items');
        var _man_power_rate = _select_work_item.attr('data-cost-man-power');
        var _equipment_rate = _select_work_item.attr('data-cost-tools');
        var _material_rate = _select_work_item.attr('data-cost-material');
        _man_power_rate = _man_power_rate ? _man_power_rate : 0 ;
        _equipment_rate = _equipment_rate ? _equipment_rate : 0;
        _material_rate = _material_rate ? _material_rate : 0;

        var _man_power_factorial = _parent.find('.js-input-labor_factorial').val();
        var _equipment_factorial = _parent.find('.js-input-equipment_factorial').val();
        var _material_factorial = _parent.find('.js-input-material_factorial').val();

        if(_vol == '') _vol = 1;
        if(_man_power_factorial == '') _man_power_factorial = 1;
        if(_equipment_factorial == '') _equipment_factorial = 1;
        if(_material_factorial == '') _material_factorial = 1;

        var _total_man_power_rate = (_man_power_rate * _man_power_factorial);
        var _total_equipment_rate = (_equipment_rate * _equipment_factorial);
        var _total_material_rate = (_material_rate * _material_factorial);

        var _total_man_power_rate_vol = _total_man_power_rate * _vol;
        var _total_equipment_rate_vol = _total_equipment_rate * _vol;
        var _total_material_rate_vol = _total_material_rate * _vol;

        var _total = _total_man_power_rate_vol + _total_equipment_rate_vol + _total_material_rate_vol;

        _parent.find('.js-total-work-item-rate').find('span').text(toCurrency(_total));
        _parent.find('.js-work-item-man-power-cost').text(toCurrency(_total_man_power_rate))
        _parent.find('.js-work-item-equipment-cost').text(toCurrency(_total_equipment_rate))
        _parent.find('.js-work-item-material-cost').text(toCurrency(_total_material_rate))
    }

    $(document).on('click','.js-open-modal-detail', function(e){
        $('#modal-loading').modal('show');
        var _this = $(this);
        var _id = _this.data('id');
        var _template = $('#js-template-modal-detail-estimate').html();

        $.ajax({
            url:'/getDetailWorkItem',
            data: {
                'id' : _id
            },
            success:function (item){
                if(item.status === 200){
                   var _temp = Mustache.render(_template,item.data)
                   $('.js-modal-detail-estimate-template').append(_temp)
                   $('#workItemDetailModal').modal('show');
                }
            },
            complete: function() {
                // Hide loading modal after AJAX request is complete
                $('#modal-loading').modal('hide');
            }
        })
    })

    $(document).on('hidden.bs.modal','#workItemDetailModal',function(){
        $(this).remove();
    });


    $('.js-fullscreen').on('click', function () {
        var _table = document.querySelector('.js-fullscreen-element');
        var scrollLeft = _table.scrollLeft;

        // Remove rows with empty Select2 dropdowns
        $(_table).find('.select2').each(function () {
            var _val = $(this).val();
            var _label = $(this).closest('td').find('.js-work-item-text').find('span').text();
            if (_val === null || _val === '') {
                if (_label.trim().length < 1) {
                    this.closest('tr').remove();
                }
            }
        });

        // Go full screen
        _table.requestFullscreen();
        var _element_full_screen = $('.js-fullscreen-element');
        _element_full_screen.css('background-color','#f4f7fb')
        _element_full_screen.css('padding','0')
        _element_full_screen.find('.table-overflow').css('height','92vh')
        _element_full_screen.find('.js-btn-cancel-estimate-form').css('margin-right','0.8em')
        _element_full_screen.find('.js-save-estimate-discipline').css('margin-right','0.8em')
        setWhiteBackground(_table);

        // Select2 repositioning code
        $(_table).find('.select2').each(function () {
            workItemSelectInit(this);
        });

        // Scroll the table to the left
        _table.scrollLeft = 0;
    });

    $(document).on('fullscreenchange', function () {
        // Check if the document is currently in fullscreen mode
        if (!document.fullscreenElement) {
            var _element_full_screen = $('.js-fullscreen-element');
            _element_full_screen.css('padding-left','0.8em')
            _element_full_screen.find('.js-btn-cancel-estimate-form').css('margin-right','0')
            _element_full_screen.find('.js-save-estimate-discipline').css('margin-right','0')
            $('.js-manual-notify').remove();
        }
    });

    setInterval(function(){
        if(document.fullscreenElement){
            $(document).find('.js-manual-notify').addClass('fadeOut');
        }
    },6000)

    function setWhiteBackground(_table){
        // Find all elements with the class "js-row-item-estimate" that are descendants of _table
        const elements = _table.querySelectorAll('.js-row-item-estimate');
        // Loop through the matched elements and set the background color for each of them
        elements.forEach(element => {
            element.style.backgroundColor = 'white';
        });
    }

    function removeCurrency($val){
        if($val == null || $val == '') return 0;
        $val = $val.toString().replaceAll(",", "")
        return $val
    }
    function toCurrency($val){
        if($val == null || $val == 0) return '';
        return new Intl.NumberFormat('en-US',{ minimumFractionDigits: 2, maximumFractionDigits: 2 }).format($val);
    }

    function generateId(){
        return Math.random().toString(36).substring(2,9);
    }

    function removeBlankSpace(str){
        return str.replace(/\s/g, "")
    }

});
