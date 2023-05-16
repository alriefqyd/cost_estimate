$(function(){
    /**
     * Project Form
     */
    $('.js-add-project-form').validate({
        rules:{
            project_no : {
                required:true,
            },
            project_title : {
                required:true
            },
            project_sponsor:{
                required:true
            },
            project_manager:{
                required:true
            },
            project_engineer:{
                required:true
            },
        },
        messages:{
            project_no: {
                remote:"Project no already taken"
            }
        }
    });

    var designEngineerInit = function (e){
        var _this = $(e);
        if(_this.data("select2")) _this.select2("destroy")
        _this.select2({
            allowClear:true,
            width:'100%',
            ajax:{
                url : _this.data('url'),
                data:function (params) {
                    return {
                        subject : _this.data('subject'),
                        q: params.term
                    };
                },
                processResults: function (resp) {
                    return {
                        results: resp
                    };
                }
            }
        })
    }

    $('.js-design-engineer').each(function (){
        designEngineerInit(this)
    })

    /**
     * Project List
     */

    $('.btn-search-project').on('click',function(e){
        e.preventDefault();
        $('.js-form-project-search').submit();
    });

    /**
     * Currency Format
     */

    $('.js-currency').each(function(){
        var _this = $(this);
        var _val = _this.val();
        _val = _val.replaceAll('.',',');
        _val = currencyFormat(_val);
        _this.val(_val);
    });

    $(document).on('keypress keyup blur','.js-currency',function(e){
        // $('.js-currency-format').on('keypress keyup blur',function(e){
        var _this = $(this)
        var _val = currencyFormat(_this.val());

        _this.val(_val)
        if(e.which === 44 || e.which === 45) return true
        // if(!_val.match(/[\d,]+\.\d+/)){
        //     console.log('false')
        // }
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
        _val = _val.toString()
        var _split = _val.split('.')
        var _join =  _split.join('');
        var _split_comma = _join.split(',')
        return  _split_comma[0]
    }

})
