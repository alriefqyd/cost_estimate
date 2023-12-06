$(function(){
    //Work Breakdown Structure (WBS)
    var _table_no = 0;

    var workElementSelectInit = function (el) {
        var _this = $(el);
        if (_this.data("select2")) _this.select2("destroy");
        _this.select2({
            placeholder: "Please Select Work Element",
            allowClear: true,
            width: '100%',
            ajax: {
                url: '/getWorkElement',
                data: function (params) {
                    var owner = _this.closest('.js-project-form').find('.js-select-owner').val();
                    return {
                        discipline: _this.closest('.js-form-row-discipline').find('.js-select-discipline').val()
                    }
                },
                processResults: function (resp) {
                    return {
                        results: resp
                    }
                }
            }
        });
    }

    var _selectWorkElement = $('.js-select-work-element');
    _selectWorkElement.each(function () {
        workElementSelectInit(this);
    });

    $(document).on('click','.js-hide-location',function (){
        $(this).closest('.card').find('fieldset').addClass('d-none')
        $(this).addClass('d-none')
        $(this).siblings('.js-show-location').removeClass('d-none')
    })

    $(document).on('click','.js-show-location',function (){
        $(this).closest('.card').find('fieldset').removeClass('d-none')
        $(this).addClass('d-none')
        $(this).siblings('.js-hide-location').removeClass('d-none')
    })

    $(document).on('click','.js-delete-location',function (){
        $(this).closest('.card').remove();
    })

    $(document).on('click','.js-add-location_equipment', function (){
        var _this = $(this)
        var template = $('#js-template-table-location_equipment').html();
        Mustache.parse(template);
        var data = {
            "no": _table_no += 1
        }
        var _temp = Mustache.render(template, data)
        $(this).siblings('.js-form-list-location').append(_temp)
        $('.select2').select2()
        var _work_element = $('.js-select-work-element');
        _work_element.each(function () {
            workElementSelectInit(this)
        })
    });

    $(document).on('click','.js-add-new-discipline-work-element',function(e){
        var _this = $(this)
        var template = $('#js-template-table-discipline_work-element').html();
        Mustache.parse(template);
        var data = {
            "no": _table_no += 1
        }
        var _temp = Mustache.render(template, data)
        var _loc = $(this).closest('fieldset').find('.js-row-work-element')
        _loc.append(_temp)
        var _work_element = $('.js-select-work-element');
        $('.select2').select2()
        _work_element.each(function () {
            workElementSelectInit(this)
        })
    })

    $(document).on('change','.js-select-discipline',function (){
        var _this = $(this)
        var _val = _this.val();
    })

    $(document).on('click','.js-remove-form-row-discipline',function(){
        $(this).closest('.js-form-row-discipline').remove();
    })

    function isJson(str){
        try {
            JSON.parse(str);
            return true;
        } catch (e) {
            return false;
        }
    }

    $(document).on('click', '.js-save-wbs', function(e){
        e.preventDefault();
        $('.js-modal-save-wbs').modal('show');
    });

    $(document).on('click','.js-form-list-location-submit',function (e){
        $('.js-modal-save-wbs').modal('hide');
        $('.js-modal-loading-wbs').modal('show');
        e.preventDefault();
        var _isValid = true;
        var _js_wbs_element = $('.js-mustache-wbs-element');
        $.each(_js_wbs_element ,function(index, item){
            if($(this).find('.dd-list').length < 1){
                _isValid = false;
                return false;
            }
        });

        if(!_isValid){
            $('.js-modal-loading-wbs').modal('hide');
            notification('danger','Error! Make sure all wbs data is filled until work element','fa fa-cross','Error');
            return false;
        }

        var _nestable = $('.dd');
        _nestable.nestable({
            data: function (item, source){
                var additionalData = {
                    identifier:source.data('identifier')
                };
                return additionalData;
            }
        });
        var _data = _nestable.nestable('serialize');
        var url = $('.js-form-wbs-estimate-discipline').data('url');
        var id = $('.js-form-wbs-estimate-discipline').data('id');

        $.ajax({
            type:'post',
            url: url,
            data : {wbs:_data},
            success:function(result){
                if(result.status === 200){
                    $('.js-modal-loading-wbs').modal('hide');
                    notification('success',result.message);
                    // return false
                    $(window).off('beforeunload');
                    setTimeout(function (){
                        window.location.href = '/project/' + id;
                    },2000);
                } else {
                    $('.js-modal-loading-wbs').modal('hide');
                    notification('danger','Error! Make sure all wbs data is filled until work element','fa fa-cross','Error')
                    _nestable.nestable('destroy');
                    _nestable.removeClass('nestable-initialized');
                    _nestable.removeAttr('data-action');
                }
            }
        })
    })

    $('.js-chev-hide-content').on('click',function(){
        $(this).addClass('d-none');
        $(this).siblings('.js-chev-show-content').removeClass('d-none');
        $(this).closest('.card').find('.card-body').addClass('d-none');
    })

    $('.js-chev-show-content').on('click',function(){
        $(this).addClass('d-none');
        $(this).siblings('.js-chev-hide-content').removeClass('d-none');
        $(this).closest('.card').find('.card-body').removeClass('d-none');
    })

    //add work item
    var _table_item = 0;
    var _array_estimate_discipline = [];
    var _array_manPowers = null;
    var _array_equipments = null;
    var _array_materials = null;
    var _totalRateManPower = null;
    var _totalRateEquipment = null;
    var _totalRateMaterial = null;
    var _workItemId = null;
    var _existingWorkItems = $(".js-existing-work-items");

    function generateId(){
        return Math.random().toString(36).substring(2,9);
    }

    var _table_no = 0;
    $(document).on('click', '.js-add-work-element', function () {
        var template = $('#js-template-work-element').html();
        Mustache.parse(template);
        var data = {
            "no": _table_no += 1
        }
        var _temp = Mustache.render(template, data)
        $('.js-work-element-table').find('tbody').append(_temp)
    });

    $(document).on('click','.js-add-work-item', function (){
        var _this = $(this);
        var _parent = _this.closest('.row');
        var _work_element = _parent.find('.js-select-work-element-item').val();
        var _work_element_text = $('.js-level3-checkbox').find('.form-group');
        var _work_item = _parent.find('.js-select-work-items').val();
        var _work_item_text = _parent.find('.js-select-work-items option:selected').text();
        var _vol = _parent.find('.js-input-vol').val();
        var _unit = _parent.find('.js-vol-result-ajax').text();
        var template = $('#js-template-work-item').html();
        var _labour_factorial = $('.js-input-labor_factorial').val();
        var _equipment_factorial = $('.js-input-equipment_factorial').val();
        var _material_factorial = $('.js-input-material_factorial').val();
        var templateModal = $('#js-template-modal-work-item').html();
        var _labour_unit_price = _totalRateManPower;
        var _equipment_unit_price = _totalRateEquipment;
        var _materials_unit_price = _totalRateMaterial;

       if( !_work_item || !_vol){
            notification('danger', 'Please complete all required fields', 'fa fa-time', 'Error');
            return false;
        }

        if(_labour_factorial !== '' && _labour_factorial != null && _labour_factorial != 0){
           _totalRateManPower = parseFloat(removeCurrency(_totalRateManPower)) * _labour_factorial
           _totalRateManPower = toCurrency(_totalRateManPower)
        }

        if(_equipment_factorial !== '' && _equipment_factorial != null && _equipment_factorial != 0){
            _totalRateEquipment = parseFloat(removeCurrency(_totalRateEquipment)) * _equipment_factorial
            _totalRateEquipment = toCurrency(_totalRateEquipment)
        }

        if(_material_factorial !== '' && _material_factorial != null && _material_factorial != 0){
            _totalRateMaterial = parseFloat(removeCurrency(_totalRateMaterial)) * _material_factorial
            _totalRateMaterial = toCurrency(_totalRateMaterial)
        }

        Mustache.parse(template);
        var data = {
            "idx": generateId(),
            "workItem":_work_item,
            "workItemText":_work_item_text,
            "vol":_vol ? _vol : 0,
            "unit":_unit,
            "manPowers":_array_manPowers,
            "totalRateManPowers":_totalRateManPower,
            "equipmentTools":_array_equipments,
            "totalRateEquipments":_totalRateEquipment,
            "materials":_array_materials,
            "totalRateMaterials":_totalRateMaterial,
            "workItemId":_workItemId,
            "labourFactorial": _labour_factorial,
            "equipmentFactorial":_equipment_factorial,
            "materialFactorial":_material_factorial,
            "labourUnitRate":_labour_unit_price,
            "materialUnitRate":_materials_unit_price,
            "equipmentUnitRate":_equipment_unit_price,
        };

        _array_estimate_discipline.push(data);
        if(_array_estimate_discipline.length > 0){
            $('.js-work-item-table').removeClass('d-none');
        }

        $(".js-select-work-items").val('').trigger('change');
        $(".js-input-vol").val('');
        $(".js-input-labor_factorial").val('');
        $(".js-input-equipment_factorial").val('');
        $(".js-input-material_factorial").val('');
        var _temp = Mustache.render(template, data);
        var _modalTemp = Mustache.render(templateModal, data);
        $('.js-work-item-table').find('.js-body-work-item-table').append(_temp);
        $('.modal-detail').append(_modalTemp);
    });

    $(document).on('click', '.js-delete-work-element', function () {
        var _this = $(this);
        var _idx = _this.data('idx');

        _this.closest('.js-work-element-input-column').remove();
    });

    $(document).on('click','.js-delete-item',function (){
        var _this = $(this);
        _this.closest('.js-item-parent').remove();
    })

    /**
     * Delete Item Work Item
     */
    $(document).on('click', '.js-delete-work-item', function () {
        var _this = $(this);
        var _idx = _this.data('idx');
        var _item_id = _this.closest('.js-work-item-input-column').data('id-item');
        var _selector = '.js-modal-detail-work-item-'+_item_id
        $('.modal-detail').find(_selector).remove()
        _this.closest('.js-work-item-input-column').remove();
        _array_estimate_discipline.splice(_array_estimate_discipline.findIndex(({idx}) => idx == _idx), 1);
    });

    var _rateManPower = 0;
    $(document).on('change','.js-select-item-additional',function (){
        var _originalValue = $(this).select2('data')[0]?.rate
        _rateManPower = _originalValue;
        $(this).closest('tr').find('.js-additional-man-power-rate').text(toCurrency(_originalValue))
    })

    $(document).on('change keyup','.js-additional-man-power-coef',function (){
        var _parent = $(this).closest('tr')
        var _rate = _rateManPower
        var _coef = $(this).val();
        var _amount = _parent.find('.js-additional-man-power-amount')
        var _totalAmount = _rate * _coef
        _amount.text(toCurrency(_totalAmount));
    })

    $('.js-form-location').on('change keyup keydown', function(){
        var _this = $(this);
        if(_this.val() != ''){
            _this.siblings('.js-add-btn-wbs').removeAttr('disabled')
        } else {
            _this.siblings('.js-add-btn-wbs').attr('disabled','disabled')
        }
    })

    $('.js-add-btn-wbs').on('click', function(e){
        e.preventDefault()
        var _modal_loading = $('.js-modal-loading-wbs');
        var _data_discipline = ''
        var _template_discipline = $('#js-template-nestable-wbs').html();
        var _template_location = $('#js-template-location-nestable-wbs').html();
        var _location_form = $('.js-form-location');

        _modal_loading.modal('show');
        bindBeforeUnloadEvent();

        Mustache.parse(_template_location);

        $.ajax({
            url : '/getDisciplineList',
            type : 'GET',
            data : {'isMustache':true},
            success : function(result){
                if(result.status === 200){
                    _data_discipline = result.data
                    var _data = {
                        'text' : _location_form.val(),
                        'child' : 'js-add-discipline',
                        'dataList':_data_discipline
                    }
                    $('.dd-empty').remove();
                    var _temp_location = Mustache.render(_template_location,_data)
                    $('.js-nestable-wbs').append(_temp_location)


                    feather.replace()
                    $('.select2').select2()
                    _location_form.val('')
                } else {
                    notification('danger','cannot get data discipline','fa fa-exclamation','Error')
                }
            },
            complete: function() {
                // Hide loading modal after AJAX request is complete
                $('#modal-loading').modal('hide');
            }
        })
    })

    $(document).on('click','.js-add-new-nestable-wbs',function(){
        var _this = $(this);
        var _template = $('#js-template-sub-nestable').html();
        var _parent = _this.closest('.dd-item');
        var _url = '/getDisciplineList';
        var _text = 'Select Discipline'
        var _discipline = '';
        var _showButton = true
        var _modal_loading = $('.js-modal-loading-wbs');
        var _isSelect = true;

        _modal_loading.modal('show');

        if(_this.attr('data-is-element') === 'true'){
            _discipline = _parent.attr('data-id');
            _showButton = false;
            _isSelect = false;
        }

        Mustache.parse(_template);
        $.ajax({
            url : _url,
            type : 'GET',
            data : {
                'isMustache':true,
                'discipline': _discipline
            },
            success : function(result){
                if(result.status === 200){
                    var _data = {
                        'dataList':result.data,
                        'text' : _text,
                        'showButton':_showButton,
                        'isSelect':_isSelect
                    }
                    var _temp = Mustache.render(_template,_data)
                    _parent.append(_temp)
                    bindBeforeUnloadEvent();
                    $('.select2').select2();
                    feather.replace()
                } else {
                    notification('danger','cannot get data discipline','fa fa-exclamation','Error')
                }
            },
            complete: function() {
                // Hide loading modal after AJAX request is complete
                $('.js-modal-loading-wbs').modal('hide');
            }
        })
    });

    $(document).on('click','.js-add-discipline', function(){
        var _this = $(this)
        var _el = $(this).closest('.js-nestable-wbs')


        var _template_location = $('#js-template-location-nestable-wbs').html();
        Mustache.parse(_template_location);


        var _data = {
            'text' : 'New',
            'child' : 'js-add-discipline',
            'disciplines' : _data_discipline,
        }

        var _temp_location = Mustache.render(_template_location,_data)
        _el.append(_temp_location)

        feather.replace()

    });

    $(document).on('click','.js-delete-wbs-discipline',function(){
        var _this = $(this);
        var _parent = _this.closest('ol')
        _parent.remove();
        bindBeforeUnloadEvent();
    });

    $(document).on('change','.js-select-update-wbs',function(){
        var _this = $(this);
    })

    $(document).on('click','.js-dd-title',function(){
        var _this = $(this);
        var _parent = _this.closest('.dd-item');
        _parent.find('.dd-handle').first().addClass('d-none');
        _parent.find('.js-dd-select').first().removeClass('d-none')
    })

    $(document).on('focusout','.js-dd-title-text',function(){
       var _this = $(this);
       if(_this.text().length < 1) _this.text('Work Element');
    });

    $(document).on('keyup keydown change','.js-dd-title-text', function(){
        var _this = $(this);
        _this.closest('.dd-item').attr('data-id',_this.text());
    })

    $(document).on('click','.js-dd-title-element',function(e){
        var _this = $(this);
        var _select2 = _this.closest('li').find('.js-select-element')
        var _val = _this.closest('.js-get-idx').find('.js-select-update-element').val()
        bindBeforeUnloadEvent();
        _select2.select2({
            placeholder: "Please Select Work Element",
            allowClear: true,
            width: '100%',
            ajax: {
                url: '/getWorkElement',
                data: {discipline:_val},
                processResults: function (resp) {
                    return {
                        results: resp
                    }
                }
            }
        });
    });

    $(document).on('keyup keydown','.js-dd-title-text', function(e){
        if((e.which != 8 && $(this).text().length > 80) || (e.which == 13))
        {
            // $('#'+content_id).text($('#'+content_id).text().substring(0, max));
            e.preventDefault();
        }
    })

    $(document).on('select2:select','.js-select-update-discipline', function(e){
        var _this = $(this);
        var _parent = _this.closest('.dd-item');
        var _val = _this.val();
        var selectedData = e.params.data;
        var selectedText = selectedData.text;
        var _child = _parent.find('ol')

        bindBeforeUnloadEvent();
        _parent.find('.dd-handle').removeClass('d-none');
        _parent.find('.js-dd-select').addClass('d-none');
        _parent.find('.js-dd-title').text(selectedText);
        _parent.attr('data-id',_val);
        if(_child.length > 0){
            _child.remove();
        }
    });

    function bindBeforeUnloadEvent(){
        var _confirm_page = $('.js-confirm-row')
        if(_confirm_page.length > 0){
            $(window).on('beforeunload', function(e) {
                e.preventDefault()
                // Return a confirmation message to prompt the user
                return 'Are you sure you want to leave this page?';
            });
        }
    }

    function showButtonSubmit(){
        $('.js-save-estimate-discipline').removeClass('d-none')
    }

    function toCurrency($val){
        if($val == null || $val == 0) return '';
        return new Intl.NumberFormat('en-US').format($val);
    }

    function removeCurrency($val){
        if($val == null) return '';
        $val = $val.toString().replaceAll(",", "")
        return $val
    }
})
