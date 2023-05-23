$(function(){
    var selectItemInit = function (el){
        var _this = $(el);
        if (_this.data("select2")) _this.select2("destroy");
        _this.select2({
            minimumInputLength: 3,
            placeholder: _this.data('placeholder'),
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
                    return {
                        results: resp
                    }
                },
            }
        });
    }

    $('.js-select-man-power').each(function(index,element){
        selectItemInit(element);
    });

    $('.js-select-tools-equipment').each(function(index,element){
        selectItemInit(element);
    });

    $('.js-select-material').each(function(index,element){
        selectItemInit(element);
    });

    $(document).on('select2:select','.js-select-item',function(e) {
        var selectedOption = $(e.currentTarget).find('option:selected');
        var selectedText = selectedOption.text();
        var rate = $(this).select2('data')[0].rate;
        var parent = $(this).closest('.js-row-column');
        parent.find('.js-item-amount').text(toCurrency(rate));
        parent.find('.js-item-rate').text(toCurrency(rate));
        parent.find('.js-item-rate').attr('data-rate',rate);
        parent.find('.js-item-coef').removeAttr('disabled');
        parent.find('.js-item-unit').removeAttr('disabled');
    });

    $(document).on('change keyup keypress','.js-item-coef',function(e){
       var _this = $(this);
       var keyCode = e.which;

        // Allow 1-9 and comma (,)c
        if (keyCode === 44 && this.selectionStart === 0) {
            e.preventDefault();
        }

        if (keyCode < 48 || keyCode > 57) {
            if (keyCode !== 44) { // 44 represents the key code for comma (,)
                e.preventDefault();
            }
        }

        var _row = _this.closest('.js-row-column');
        var _rate = _row.find('.js-item-rate').attr('data-rate');
        _rate = parseFloat(_rate);
        var _coef = _this.val();

        if(_coef == '') _coef = "0";

        _coef = _coef.replaceAll(',','.');
        _coef = parseFloat(_coef);

        var _amount = _rate * _coef;
        var _amountStr = _amount.toString();

        var decimalPattern = /(\d+\.\d{2})/;
        var match = _amountStr.match(decimalPattern);

        if (match) {
            var decimalNumber = match[1];
            _row.find('.js-item-amount').text(toCurrency(decimalNumber));
        } else {
            _row.find('.js-item-amount').text(toCurrency(_amount));
        }
        countTotalAmount(0);
    });

    if($('.js-edit-work-item-man-power').length > 0){
        countTotalAmount(0);
    }

    $(document).on('click','.js-add-new-item',function(){
        var _this = $(this);
        var _template = _this.data('template');
        var template = $(_template).html();
        Mustache.parse(template);
        var _temp = Mustache.render(template);
        _this.closest('.table-responsive').find('.js-table-body-work-item-item').append(_temp);
        $('.select2').select2()
        _this.closest('.table-responsive').find('.js-select-item').each(function(index,element){
            selectItemInit(element);
        });
    });

    function countTotalAmount(totalAmount){
        $('.js-item-amount').each(function(){
            var _val = $(this).text();
            _val = removeCurrency(_val);
            totalAmount += parseFloat(_val);
        });

        $('.js-item-total').text(toCurrency(totalAmount));
    }

    $(document).on('click','.js-delete-item',function(){
        var _parent = $(this).closest('.js-row-column');
        setTimeout(function(){
            countTotalAmount(0);
        },100);
        countTotalAmount(0);
        _parent.remove();
    });

    $(document).on('click','.js-save-item',function(e){
        e.preventDefault();

        var _this = $(this);
        var _form = _this.closest('form');
        var _item_row = _form.find('.js-row-column');
        var _data_length = _item_row.length;
        var _array = [];
        var _url = _form.attr('action');

        _this.attr('disabled','disabled');
        _this.find('.loader-34').removeClass('d-none');


        if(_data_length < 1) {
            errorSave(_this);
            return false;
        }

        _item_row.each(function(){
            var __this = $(this);
            var __item = __this.find('.js-select-item').val();
            var __unit = __this.find('.js-item-unit').val();
            var __coef = __this.find('.js-item-coef').val();
            var __rate = __this.find('.js-item-rate').text();
            var __amount = __this.find('.js-item-amount').text();

            if(_data_length < 2
                && __item == null){
                errorSave(_this);
                return false;
            }

            var _data = {
                'item': __item,
                'unit' : __unit,
                'coef' : __coef,
                'rate' : __rate,
                'amount' : __amount
            };

            _array.push(_data);
        });

       $.ajax({
           method : 'post',
           url : _url,
           data : { 'data' : _array },
           success : function(result){
               if(result.status === 200) {
                   notification('success',result.message,'','Success');
                   setTimeout(function(){
                      window.location.href = '/work-item/' + _form.data('id');
                       _this.find('.loader-34').addClass('d-none');
                   },2000);
               } else {
                   _this.removeAttr('disabled','disabled');
                   _this.find('.loader-34').addClass('d-none');
                   notification('danger',result.message,'fa fa-frown-o','Error');
               }
           }
       });
    });

    function errorSave(_this){
        notification('danger','Empty Data','fa fa-frown-o','Error');
        _this.removeAttr('disabled','disabled');
        _this.find('.loader-34').addClass('d-none');
    }

    function toCurrency($val){
        if($val == null) return '';
        return new Intl.NumberFormat('en-US').format($val);
    }

    function removeCurrency($val){
        if($val == null) return '';
        $val = $val.toString().replaceAll(",", "")
        return $val
    }
})
