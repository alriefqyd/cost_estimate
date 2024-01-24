
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
        designEngineerInit(this);
    });

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

    $('.js-order-sort').each(function(){
        var _this = $(this);
        _this.on('click',function(){
            var __this = $(this);
            var _form = $(this).closest('.row').find('form')
            var _data_order = $(this).data('sort');
            var _data_sort = _form.find('.js-filter-sort').val();
            if(_data_sort == ''){
                _data_sort = 'desc'
            } else if(_data_sort == 'asc'){
                _data_sort = 'desc'
            } else if (_data_sort == 'desc'){
                _data_sort = 'asc'
            }
            _form.find('.js-filter-order').val(_data_order);
            _form.find('.js-filter-sort').val(_data_sort);
            setTimeout(function(){
                _form.submit();
            },200);
        });
    });

    $('.js-show-hide-password').on('click',function(){
        var _this = $(this);
        var _passFill = _this.closest('.input-group').find('.js-password');
        if(_passFill.attr('type') == 'password'){
            _this.removeClass('fa-eye');
            _this.addClass('fa-eye-slash');
            _passFill.attr('type', 'text');
        } else {
            _this.removeClass('fa-eye-slash');
            _this.addClass('fa-eye');
            _passFill.attr('type', 'password');
        }
    });

    $('.dual-list').bootstrapDualListbox({
        nonSelectedListLabel: 'Available Roles',
        selectedListLabel: 'Selected Roles',
        preserveSelectionOnMove: 'moved',
        moveAllLabel: 'Move all',
        removeAllLabel: 'Remove all'
    });

    $('.js-search-form').on('change',function(){
        var _this = $(this);
        var _form = _this.closest('form');

        _form.submit();
    });

    $('.js-btn-status').on('click',function(){
       var _this = $(this);
       var _data_value = _this.data('value');
        _this.siblings('.js-status-filter').val(_data_value);
        _this.closest('form').submit();
    });

    var _count = 0;
    $('.js-select-project-to-review').each(function(){
        var _this = $(this);
        var arrId = [];
        _this.on('change',function(){
            if($(this).is(':checked')){
                _count+=1;
            } else {
                _count-=1;
            }

            $('.js-select-to-reviewed').text(_count);

            if(_count > 0) {
                $('.js-btn-to-review').removeAttr('disabled');
            } else {
                $('.js-btn-to-review').attr('disabled','disabled');
            }
        });
    });

    $('.js-select-all-project-to-review').on('change',function(){
        var _this = $(this);
        var _checkbox =  $('.js-select-project-to-review');
        if(_this.is(':checked')){
            $('.js-btn-to-review').removeAttr('disabled');
            _checkbox.prop('checked', true);
            var _countAll =_checkbox.length;
            $('.js-select-to-reviewed').text(_countAll);
        } else {
            $('.js-select-to-reviewed').text('0');
            _checkbox.prop('checked', false);
            $('.js-btn-to-review').attr('disabled','disabled');
        }
    });

    $('.dd .js-add-new-nestable-wbs').on('mousedown', function (event){
        event.preventDefault();
        return false;
    });

    $('.dd .js-delete-wbs-discipline').on('mousedown', function (event){
        event.preventDefault();
        return false;
    });

    $(document).on('click', '.js-check-review', function() {
        var _url = $(this).data('url') + '/update-list?ids=';

        // Get the collection of checked checkboxes
        var $checkedCheckboxes = $('.js-check-review').filter(':checked');
        var _length_check = $checkedCheckboxes.length;

        var ids = $checkedCheckboxes.map(function() {
            return $(this).val();
        }).get().join(',');

        _url += ids;
        $('.js-btn-to-review').attr('data-url',_url);
    });

    $(document).on('click','.js-check-review-all',function(){
        var _url = $(this).data('url') + '/update-list?ids=';
        setTimeout(function(){
            var $checkedCheckboxes = $('.js-check-review').filter(':checked');
            var ids = $checkedCheckboxes.map(function() {
                return $(this).val();
            }).get().join(',');
            _url += ids;
            $('.js-btn-to-review').attr('data-url',_url);
        },500);
    });

    $(document).on('change click keyup','.js-confirm-form',function(){
        bindBeforeUnloadEvent();
    });

    $(document).on('click','.js-save-confirm-form', function(e){
        e.preventDefault();
        $(window).off('beforeunload');
        $(this).closest('form').submit();
    });

    var quill = new Quill('.js-quill',{
        theme:'snow',
    });

    /*
    Export
     */
    $('.js-btn-export').on('click',function(){
        var _this = $(this);
        var _file_name = _this.data('file-name');
        var _url = _this.data('url');
        _this.addClass('disabled-div');
        _this.find('.loader-box').removeClass('d-none');
        $.ajax({
            url:_url,
            method:'GET',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = _file_name;
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
                _this.find('.loader-box').addClass('d-none');
                _this.removeClass('disabled-div');
            }
        })
    })

    $('.js-form-import').submit(function(e){
        e.preventDefault();
        $('.js-modal-import').modal('hide');
        $('.js-modal-loading-import').modal('show');
        const formData = new FormData(this);
        const progressBar = $('.progress-bar');
        const progress = $('.progress');
        const fileInput = $('input[type="file"]', this);

        // Check the file extension
        const allowedExtensions = ['xlsx', 'xls'];
        const fileName = fileInput.val();
        const fileExtension = fileName.split('.').pop();

        var _url = $(this).data('url');
        var _redirect_url = $(this).data('redirect');

        if (!allowedExtensions.includes(fileExtension.toLowerCase())) {
            $('.js-modal-import').modal('hide');
            $('.js-modal-loading-import').modal('hide');
            notification('danger','Please re check your file', 'fa fa-time','Error')
            return; // Prevent the request if the extension is not allowed
        }

        $.ajax({
            url: _url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                progress.addClass('d-none');
                $('.js-modal-loading-import').modal('hide');
                notification('success',response.message, 'fa fa-check','Success')
                setTimeout(function (){
                    window.location.href=_redirect_url;
                },2000)
            },
            error: function(xhr) {
                $('.js-modal-loading-import').modal('hide');
                notification('danger',xhr.responseJSON.message, 'fa fa-time','Error')
            }
        });
    });

    /**
     * User Form
     *
     */

    $(document).on('change', '#js-update_password_check', function (){
       var _this = $(this);
       if(_this.is(':checked')){
            $('.js-update-password').removeClass('d-none')
       } else {
           $('.js-update-password').addClass('d-none')
           $('.js-password').val('');
       }
    });

    $('.js-btn-approve').on("click", function(){
      var _this;
      console.log('aa')
      if(_this.hasClass('show')){
          _this.siblings('.dropdown-menu').removeClass('show')
      };
    })
});
