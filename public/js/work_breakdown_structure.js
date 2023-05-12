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
            console.log(jsonObj)

        })

        $.ajax({
            type:'post',
            url: url,
            data : {wbs:jsonObj},
            success:function(result){
                console.log(result)
                if(result.status === 200){
                    notification('success',result.message)
                    // return false
                    setTimeout(function (){
                        window.location.href = '/project/' + id
                    },2000)
                } else {
                    console.log(result.message);
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

    var level2DisciplineByProject = function (el) {
        var _this = $(el);
        var _level1 = _this.closest('.js-row-work-breakdown-work-item').find('.js-select-level1');
        if (_this.data("select2")) _this.select2("destroy");

        _this.select2({
            placeholder:_this.data('placeholder'),
            allowClear:true,
            ajax:{
                url:'/getWbsLevel2',
                dataType: 'json',
                data:function(params){
                    return {'project_id':_level1.data('id'),'level1':_level1.val()}
                },
                processResults:function (resp) {
                    if(resp.status === 200){
                        return {
                            results: resp.data
                        }
                    }
                }
            }
        })
    }

    var level3DisciplineByProject = function (el) {
        var _this = $(el);
        var _level2 = _this.closest('.js-row-work-breakdown-work-item').find('.js-select-level2');
        var _level1 = _this.closest('.js-row-work-breakdown-work-item').find('.js-select-level1');
        if (_this.data("select2")) _this.select2("destroy");

        _this.select2({
            placeholder:_this.data('placeholder'),
            allowClear:true,
            ajax:{
                url:'/getWbsLevel3',
                dataType: 'json',
                data:function(params){
                    return {'project_id':_level2.data('id'),'level2':_level2.val(),'level1':_level1.val()}
                },
                processResults:function (resp) {
                    if(resp.status === 200){
                        return {
                            results: resp.data
                        }
                    }
                }
            }
        })
    }

    var _selectLevel1 = $('.js-select-level1');
    var _selectLevel2 = $('.js-select-level2');
    _selectLevel2.each(function(){
        level2DisciplineByProject($(this))
    })

    var _selectLevel3 = $('.js-select-level3');
    _selectLevel3.each(function(){
        level3DisciplineByProject($(this));
    })

    _selectLevel2.on('change',function(){
        if(_selectLevel3.val() !== "") _selectLevel3.val(null).trigger('change');
    })

    _selectLevel1.on('change',function(){
        if(_selectLevel3.val() !== "") _selectLevel3.val(null).trigger('change');
        _selectLevel2.val(null).trigger('change');
    })

    $(document).on('change','.js-select-level3',function(){
        var _this = $(this)
        var _level2 = _this.closest('.js-row-work-breakdown-work-item').find('.js-select-level2');
        var _level1 = _this.closest('.js-row-work-breakdown-work-item').find('.js-select-level1');
        var _identifier = _this.closest('.js-row-work-breakdown-work-item').find('.js-select-level1');

        if(_this.val() !== null){
            $.ajax({
                url:'/getExistingWorkItemByWbs',
                data:{
                    'project_id':_this.data('id'),
                    'level3':_this.val(),
                    'level2':_level2.val(),
                    'level1':_level1.val()},
                success:function(resp){
                    if(resp.status === 200){
                        $('.js-card-section-work-item').removeClass('d-none')
                        if(resp.data.length > 0) $('.js-work-item-table').removeClass('d-none')
                        else $('.js-work-item-table').addClass('d-none')
                        renderExistingWorkItem(resp.data)
                    }
                }
            })
            showButtonSubmit()
        }
    })

    function renderExistingWorkItem(value){
        var _existing_work_item_table = $('.js-body-work-item-table');
        var _existing_work_item_table_row = _existing_work_item_table.find('.js-work-item-input-column')
        var _template = $('#js-template-work-item').html();
        var templateModal = $('#js-template-modal-work-item').html();

        if(_existing_work_item_table_row.length > 0){
            _existing_work_item_table_row.remove();
        }

        _array_estimate_discipline = [];
        $.each(value,function(){
            var _data = {
                "idx": generateId(),
                "workItem":this.work_item_id,
                "project_id" : this.project_id,
                'equipment_location_id': this.equipment_location_id,
                "workItemText":this.work_items.description,
                "vol":this.volume,
                "unit":this.work_items.unit,
                "totalRateManPowers": toCurrency(this.labor_cost_total_rate),
                "equipmentTools": this.work_items.equipment_tools,
                "totalRateEquipments":toCurrency(this.tool_unit_rate_total),
                "materials":this.work_items.materials,
                "totalRateMaterials":toCurrency(this.material_unit_rate_total),
                "workItemId":this.work_item_id,
                "manPowers" : this.work_items.man_powers,
                "labourFactorial":this.labour_factorial,
                "equipmentFactorial":this.equipment_factorial,
                "materialFactorial":this.material_factorial,
                "labourUnitRate":this.labor_unit_rate,
                "equipmentUnitRate":this.tool_unit_rate,
                "materialUnitRate":this.material_unit_rate
            }

            var _temp = Mustache.render(_template, _data);
            var _modalTemp = Mustache.render(templateModal, _data);
            $('.js-work-item-table').find('.js-body-work-item-table').append(_temp);
            $('.modal-detail').append(_modalTemp);
            _array_estimate_discipline.push(_data)
        })

    }


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

    var workItemSelectInit = function (el) {
        var _this = $(el);
        if (_this.data("select2")) _this.select2("destroy");
        _this.select2({
            minimumInputLength: 3,
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
                    _dataAdvanceWorkItem = resp;
                    return {
                        results: resp
                    }
                },
            }
        })
    }

    var _selectWorkItems = $('.js-select-work-items');
    _selectWorkItems.each(function () {
        workItemSelectInit(this);
    });

    $(document).on('change keyup','.js-input-labor_factorial',function(){
        var _val = $(this).val();
        if(_val == '' || _val == null) _val = 0;
        var _labour_unit_price = _selectWorkItems.select2('data')[0]?.manPowersTotalRate;
        var _new_labour_unit_price = parseFloat(removeCurrency(_labour_unit_price)) * parseInt(_val)
        $('.js-labour-unit-price-preview').val(toCurrency(_new_labour_unit_price))
    });

    $(document).on('change keyup','.js-input-equipment_factorial',function(){
        var _val = $(this).val();
        if(_val == '' || _val == null) _val = 0;
        var _equipment_unit_price = _selectWorkItems.select2('data')[0]?.equipmentToolsRate;
        var _new_equipment_unit_price = parseFloat(removeCurrency(_equipment_unit_price)) * parseInt(_val)
        $('.js-equipment-unit-price-preview').val(toCurrency(_new_equipment_unit_price))
    });

    $(document).on('change keyup','.js-input-material_factorial',function(){
        var _val = $(this).val();
        if(_val == '' || _val == null) _val = 0;
        var _material_unit_price = _selectWorkItems.select2('data')[0]?.materialsRate;

        var _new_material_unit_price = parseFloat(removeCurrency(_material_unit_price)) * parseInt(_val)
        $('.js-material-unit-price-preview').val(toCurrency(_new_material_unit_price))
    });

    _selectWorkItems.on("change", function () {
        var _this = $(this);
        var _vol = _this.select2('data')[0]?.vol;
        _array_manPowers = _this.select2('data')[0]?.manPowers;
        _array_equipments = _this.select2('data')[0]?.equipmentTools;
        _array_materials = _this.select2('data')[0]?.materials;
        _totalRateManPower = _this.select2('data')[0]?.manPowersTotalRate;
        _totalRateEquipment = _this.select2('data')[0]?.equipmentToolsRate;
        _totalRateMaterial = _this.select2('data')[0]?.materialsRate;
        _workItemId = _this.val();
        $('.js-labour-unit-price-preview').val(_totalRateManPower)
        $('.js-equipment-unit-price-preview').val(_totalRateEquipment)
        $('.js-material-unit-price-preview').val(_totalRateMaterial)
        _this.closest('.row').find('.js-vol-result-ajax').text(_vol);
    });

    /**
     * Work Items
     */
    $('.js-save-estimate-discipline').on('click',function (e){
        e.preventDefault();
        var _this = $(this);
        var _body_work_item = $('.js-body-work-item-table');

        if(_body_work_item.length < 1) notification('error','Empty Value Work Item')
        var _id = $('.js-card-section-work-item').data('id');
        var _work_element =  $('.js-select-level3').val();

        var _url = '/project/'+_id+'/estimate-discipline/store'
        if(_this.data('update') == true){
            _url = '/project/'+_id+'/estimate-discipline/update'
        }

        var _level2 = $('.js-select-level2').val();
        var _level1 = $('.js-select-level1').val();

        var _data = {
            'work_items' : _array_estimate_discipline,
            'project_id' : _id,
            'work_element' : _work_element,
            'level3' : _work_element,
            'level2' : _level2,
            'level1' : _level1
        }

        $.ajax({
            url: _url,
            data : _data,
            type : 'POST',
            success : function(data){
                if(data.status === 200){
                    notification('success',data.message)
                } else {
                    notification('error',data.message)
                }
            }
        })
    })

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
