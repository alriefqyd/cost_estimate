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

    $(document).on('click','.js-form-list-location-submit',function (e){
        e.preventDefault()
        var _this = $(this)
        var _item_parent = $('.js-card-items-wbs-level-3')
        var jsonObj = [];
        var url = $('.js-form-wbs-estimate-discipline').data('url')
        var id = $('.js-form-wbs-estimate-discipline').data('id')

        _this.find('.loader-34').removeClass('d-none');
        _this.attr('disabled','disabled')
        $.each(_item_parent,function (index,value){
            var __this = $(this);
            var __type = __this.find('.js-wbs-l3-type')
            var __title = __this.find('.js-wbs-l3-location_equipment-title')
            var __identifier = __this.find('.js-wbs-l3-identifier')
            var __discipline = __this.find('.js-wbs-l3-discipline')
            var arrDiscipline = []
            var arrWorkElement = []

            $.each(__discipline,function (index,value){
                var ___this = $(this)
                var ___work_element = ___this.closest('.js-form-row-discipline').find('.js-wbs-l3-work_element')
                var itemDiscipline = {}
                itemDiscipline['id'] = ___this.val();
                itemDiscipline['work_element'] = ___work_element.val().length > 0 ? ___work_element.val() : ['null'];
                arrDiscipline.push(itemDiscipline)
            })

            var item = {}
            item['title'] = __title.val();
            item['identifier'] = __identifier.val();
            item['type'] = __type.val();
            item['discipline'] = arrDiscipline

            jsonObj.push(item)

        })

        $.ajax({
            type:'post',
            url: url,
            data : {wbs:jsonObj},
            success:function(result){
                if(result.status === 200){
                    notification('success',result.message)
                    // return false
                    setTimeout(function (){
                        window.location.href = '/project/' + id
                    },2000)
                } else {
                    notification('danger',result.message,'fa fa-cross','Error')
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
    /** Deprecated */

    /** /Deprecated */

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
