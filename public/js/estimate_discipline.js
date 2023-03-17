$(function() {
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

    if(_existingWorkItems.length > 0){
        $.each(_existingWorkItems,function(index,value){
            var data = {
                "idx" : index,
                "workItem" : $(this).data('work-item'),
                "workElement": $(this).data('work-element'),
                "vol" : $(this).data('volume'),
                "estimate_discipline_id" : $(this).val(),
            }
           _array_estimate_discipline.push(data);
        });
    }

    $(document).on('change','.js-select-discipline',function (){
        var _parent = $('.js-select-discipline-card');
        var _id = _parent.data('id');
        window.location.href = '/project/' + _id + '/estimate-discipline/create/' + $(this).val();
    });

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
       var _parent = _this.closest('.js-table-input-work-item');
       var _work_element = _parent.find('.js-select-work-element').val();
       var _work_element_text = _parent.find('.js-select-work-element option:selected').text();
       var _work_item = _parent.find('.js-select-work-items').val();
       var _work_item_text = _parent.find('.js-select-work-items option:selected').text();
       var _vol = _parent.find('.js-input-vol').val();
       var _unit = _parent.find('.js-vol-result-ajax').text();
       var template = $('#js-template-work-item').html();
       var templateModal = $('#js-template-modal-work-item').html();

       if(!_work_element || !_work_item || !_vol){
           notification('danger', 'Please complete all required fields', 'fa fa-time', 'Error');
           return false;
       }

       Mustache.parse(template);
       var data = {
           "idx": generateId(),
           "workElement":_work_element,
           "workElementText":_work_element_text,
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
       };
       _array_estimate_discipline.push(data);
       if(_array_estimate_discipline.length > 0){
           $('.js-work-item-table').removeClass('d-none');
       }

       $(".js-select-work-items").val('').trigger('change');
       $(".js-input-vol").val('');
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

    $(document).on('click', '.js-delete-work-item', function () {
        var _this = $(this);
        var _idx = _this.data('idx');
        var _item_id = _this.closest('.js-work-item-input-column').data('id-item');
        var _selector = '.js-modal-detail-work-item-'+_item_id
        $('.modal-detail').find(_selector).remove()
        _this.closest('.js-work-item-input-column').remove();
        _array_estimate_discipline.splice(_array_estimate_discipline.findIndex(({idx}) => idx == _idx), 1);
        console.log(_array_estimate_discipline)
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

    var workElementSelectInit = function (el) {
        var _this = $(el);
        if (_this.data("select2")) _this.select2("destroy");
        _this.select2({
            minimumInputLength: 3,
            placeholder: "Please Select Work Element",
            allowClear: true,
            width: '100%',
            ajax: {
                url: '/getWorkElement',
                data: function (params) {
                    return {
                        q: params.term,
                        project_id:_this.data('id'),
                        discipline:_this.data('discipline')
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

    var _selectWorkItems = $('.js-select-work-items');
    _selectWorkItems.each(function () {
        workItemSelectInit(this);
    });

    var _selectWorkElement = $('.js-select-work-element');
    _selectWorkElement.each(function () {
        workElementSelectInit(this);
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
        _this.closest('tr').find('.js-vol-result-ajax').text(_vol);
    });

    /**
     * Work Items
     */
    $('.js-save-estimate-discipline').on('click',function (e){
        e.preventDefault();
        var _this = $(this);

        var _id = $('.js-select-discipline-card').data('id');
        var _discipline =  $('.js-select-work-element').data('discipline')
        var _url = '/project/'+_id+'/estimate-discipline/store'
        if(_this.data('update') == true){
            _url = '/project/'+_id+'/estimate-discipline/update'
        }

        var _data = {
            'work_items' : _array_estimate_discipline,
            'project_id' : _id,
            'discipline' : _discipline
        }

        $.ajax({
            url: _url,
            data : _data,
            type : 'POST',
            success : function(data){
                if(data.status === 200){
                    notification('success',data.message)
                    window.location.href = '/project/'+_id+'/estimate-discipline/create?discipline='+_discipline
                } else {
                    notification('error',data.message)
                }
            }
        })
    })



})
