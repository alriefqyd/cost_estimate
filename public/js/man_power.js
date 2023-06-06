$(function() {
    $('.js-basic-rate-monthly').on('change keyup',function(){
        var _basic_rate_month_val= removeFormatCurrency($(this).val());
        var _dividend_basic_rate_month = 173;
        var _multiplier_general_allowance = 0 / 100;
        var _multiplier_bpjs = 7.74 / 100; // 7,74%
        var _multiplier_bpjs_kesehatan = 4 / 100; // 4%
        var _multiplier_thr = 8.33 / 100; // 8,33%
        var _multiplier_public_holiday = 0 / 100;
        var _multiplier_leave = 0 / 100;
        var _multiplier_pesangon = 18.33 / 100;
        var _multiplier_asuransi = 0 / 36/ 173;
        var _multiplier_safety = $(this).data('safety-rate');
        var _multiplier_factor = 1.75;

        var _basic_rate_hourly = $('.js-basic-rate-hourly');
        var _general_allowance = $('.js-general-allowance');
        var _bpjs = $('.js-bpjs');
        var _bpjs_kesehatan = $('.js-bpjs-kesehatan');
        var _thr = $('.js-thr');
        var _public_holiday = $('.js-public-holiday');
        var _leave = $('.js-leave');
        var _pesangon = $('.js-pesangon');
        var _asuransi = $('.js-asuransi');
        var _safety = $('.js-safety');

        var _basic_rate_hourly_total = _basic_rate_month_val / _dividend_basic_rate_month ;
        var _basic_rate_hourly_total_format = processFormat(_basic_rate_hourly_total);
        _basic_rate_hourly.val(_basic_rate_hourly_total_format);

        var general_allowance_total = _basic_rate_hourly_total * _multiplier_general_allowance;
        var general_allowance_total_format = processFormat(general_allowance_total);
        _general_allowance.val(general_allowance_total_format);

        var bpjs_total = _basic_rate_hourly_total * _multiplier_bpjs;
        var bpjs_total_format = processFormat(bpjs_total);
        _bpjs.val(bpjs_total_format);

        var bpjs_kesehatan_total = _basic_rate_hourly_total * _multiplier_bpjs_kesehatan;
        var bpjs_kesehatan_total_format = processFormat(bpjs_kesehatan_total);
        _bpjs_kesehatan.val(bpjs_kesehatan_total_format);

        var _thr_total = _basic_rate_hourly_total * _multiplier_thr;
        var _thr_total_format = processFormat(_thr_total);
        _thr.val(_thr_total_format);

        var _public_holiday_total = _basic_rate_hourly_total * _multiplier_public_holiday;
        var _public_holiday_format = processFormat(_public_holiday_total);
        _public_holiday.val(_public_holiday_format);

        var _leave_total = _basic_rate_hourly_total * _multiplier_leave;
        var _leave_format = processFormat(_leave_total);
        _leave.val(_leave_format);

        var _pesangon_total = _basic_rate_hourly_total * _multiplier_pesangon;
        var _pesangon_format = processFormat(_pesangon_total);
        _pesangon.val(_pesangon_format);

        var _asuransi_total = _basic_rate_hourly_total * _multiplier_asuransi;
        var _asuransi_format = processFormat(_asuransi_total);
        _asuransi.val(_asuransi_format);

        var _safety_format = processFormat(_multiplier_safety);
        _safety.val(_safety_format);

        var _total_benefit_hourly = general_allowance_total + bpjs_total + bpjs_kesehatan_total
                                    + _thr_total + _public_holiday_total + _leave_total + _pesangon_total
                                    + _asuransi_total + _multiplier_safety;
        $('.js-total-benefit-hourly').val(processFormat(_total_benefit_hourly))

        var _overall_rate_hourly = _total_benefit_hourly + _basic_rate_hourly_total
        $('.js-overall-rate-hourly').val(processFormat(_overall_rate_hourly))

        var _factor = _overall_rate_hourly * _multiplier_factor;
        $('.js-factor-hourly').val(processFormat(_factor))

    });


    /**
     * Currency Format
     */

    $('.js-currency').each(function(){
        $(this).attr('disabled','disabled');
    })

    function processFormat(val){
        if(!val) return 0;
        var _val_fixed = val.toFixed(2);
        var _val_comma = _val_fixed.replaceAll('.',',');
        var _val_format = currencyFormat(_val_comma);
        return _val_format;
    }

    $(document).on('keypress keyup blur','.js-currency-idr',function(e){
        var _this = $(this)
        var _val = currencyFormat(_this.val());

        _this.val(_val);
        if(e.which === 44 || e.which === 45) return true
    })

    function currencyFormat(_value){

        var number_string = _value.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            rest = split[0].length % 3,
            currency = split[0].substr(0,rest),
            currency_value = split[0].substr(rest).match(/\d{3}/gi)

        if(currency_value){
            var separator = rest ? '.' : '';
            currency += separator + currency_value.join('.');
        }

        var sign = _value.charAt(0);

        currency = split[1] != undefined ? currency + ',' + split[1] : currency;
        if(sign == '-'){
            return '-' + currency;
        }
        return currency;
    }

    function removeFormatCurrency(_val){
       _val = _val.replaceAll('.','');
       var _val2 = _val.replaceAll(',','.');
       return parseFloat(_val2);
    }

    $('.js-btn-save-man-power').on('click',function(e){
        $('.js-currency').removeAttr('disabled','disabled');
    })

    /**
     * Delete Man Power
     */
    var _idDelete = '';
    $('.js-delete-man-power').on('click', function(){
        _idDelete = $(this).data('id');
    });

    $('.js-modal-delete-man-power').on('hide.bs.modal', function(event) {
        _idDelete = '';
    });

    $(document).on('click','.js-delete-confirmation-man-power', function(e){
        var url = '/man-power/' + _idDelete
        $.ajax({
            url:url,
            type : 'DELETE',
            success : function(data){
                $('.js-modal-delete-man-power').hide();
                if(data.status === 200){
                    notification('success',data.message)
                    setTimeout(function(){
                        window.location.href = 'man-power';
                    },1000)
                } else {
                    notification('error',data.message)
                }
            }
        })
    })
})
