$(function(){
    var manPowerSelectInit = function (el){
        var _this = $(el);
        if (_this.data("select2")) _this.select2("destroy");
        _this.select2({
            minimumInputLength: 3,
            placeholder: "Please Select Man Power",
            allowClear: true,
            width: '100%',
            ajax: {
                url: '/getManPower',
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
       manPowerSelectInit(element);
   });

    $(document).on('select2:select','.js-select-man-power',function(e) {
        var selectedOption = $(e.currentTarget).find('option:selected');
        var selectedText = selectedOption.text();
        var rate = $(this).select2('data')[0].rate;
        var parent = $(this).closest('.js-row-work-item-man-power');
        parent.find('.js-work-item-man-power-rate').text(toCurrency(rate))
        parent.find('.js-work-item-man-power-rate').attr('data-rate',rate)
        parent.find('.js-work-item-man-power-description').text(selectedText);
        parent.find('.js-coef-work-item-man-power').removeAttr('disabled');
        parent.find('.js-unit-work-item-man-power').removeAttr('disabled');
    })

    $(document).on('change keyup keypress','.js-coef-work-item-man-power',function(e){
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

        var _row = _this.closest('.js-row-work-item-man-power');
        var _rate = _row.find('.js-work-item-man-power-rate').attr('data-rate');
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
            _row.find('.js-work-item-man-power-amount').text(toCurrency(decimalNumber));
        } else {
            _row.find('.js-work-item-man-power-amount').text(toCurrency(_amount));
        }
        countTotalAmount(0);
    });

    if($('.js-edit-work-item-man-power').length > 0){
        countTotalAmount(0);
    }

    $(document).on('click','.js-add-new-man-power',function(){
        var _this = $(this);
        var template = $('#js-template-table-work_item_man_power').html();
        Mustache.parse(template);
        var _temp = Mustache.render(template);
        $(this).siblings('.js-table-work-item-man-power').find('.js-table-body-work-item-man-power').append(_temp)
        $('.select2').select2()
        $(this).siblings('.js-table-work-item-man-power').find('.js-select-man-power').each(function(index,element){
            manPowerSelectInit(element);
        });
    });

    function countTotalAmount(totalAmount){
        $('.js-work-item-man-power-amount').each(function(){
            var _val = $(this).text();
            _val = removeCurrency(_val);
            totalAmount += parseFloat(_val);
        });

        $('.js-work-item-man-power-total').text(toCurrency(totalAmount));
    }

    $(document).on('click','.js-delete-work-item-man-power',function(){
        var _parent = $(this).closest('.js-row-work-item-man-power');
        setTimeout(function(){
            countTotalAmount(0);
        },100);
        countTotalAmount(0);
        _parent.remove();
    });

    $(document).on('click','.js-save-work-item-man-power',function(e){
        e.preventDefault();

        var _this = $(this);
        var _form = _this.closest('form');
        var _man_power = _form.find('.js-select-man-power');
        var _man_power_row = $('.js-row-work-item-man-power');
        var _data_length = _man_power_row.length;
        var _array = [];
        var _url = _form.attr('action');

        _this.attr('disabled','disabled');
        _this.find('.loader-34').removeClass('d-none');

        _man_power_row.each(function(){
            var __this = $(this);
            var __man_power = __this.find('.js-select-man-power').val();
            var __unit = __this.find('.js-unit-work-item-man-power').val();
            var __coef = __this.find('.js-coef-work-item-man-power').val();
            var __rate = __this.find('.js-work-item-man-power-rate').text();
            var __amount = __this.find('.js-work-item-man-power-amount').text();

            if(_data_length < 2
                && __man_power == null){
                notification('danger','Empty Data','fa fa-frown-o','Error');
                _this.removeAttr('disabled','disabled');
                _this.find('.loader-34').addClass('d-none');
                return false;
            }

            var _data = {
                'man_power': __man_power,
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
