$(function(){
    $('.js-download-summary-xlsx').on('click',function(e){
        e.preventDefault();
        var _this = $(this);
        var _idProject = _this.data('id');
        var _file_name = _this.data('name');
        _this.attr('disabled','disabled');
        _this.find('.loader-box').removeClass('d-none');
        $.ajax({
            url: '/cost-estimate-summary/export/'+ _idProject,
            method: 'GET',
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
                _this.removeAttr('disabled');
            }
        });
    });

    $('.js-btn-approve-cost-estimate').on('click',function(){
        var _this = $(this)
        var segment = window.location.pathname.split('/')[2];
        $.ajax({
            'url' : '/project/update-status/' + segment,
            'method' : 'post',
            success : function(result){
                if(result.status === 200){
                    $('#approveModal').hide();
                    $('.modal-backdrop').remove();
                    // $('.js-detail-status').text(result.data);
                    // $('.js-btn-approve-modal').remove();
                    notification('success', result.message, 'fa fa-check', 'success');
                    setTimeout(function (){
                        window.location.href = '/project/' + segment;
                    },2000);
                } else {
                    notification('danger', result.message, 'fa fa-light', 'Error');
                }
            }
        });
    });

    $(document).on('click change','.js-approve-discipline', function(e){
        var _this = $(this);
        var _modal = $('.js-modal-approve-discipline');
        var _id = _modal.data('id');
        var _parent = _modal.find('.js-btn-approve-discipline-cost-estimate');
        var _val = _this.val();

        // if(_val === 'PENDING'){
        //     _this.prop('checked',true);
        // _this.closest('.js-form-parent-approval').find('.js-remark-pending').removeClass('d-none');
        // } else {

        _modal.modal('show');
        _parent.attr('data-form',_this.attr('name'));
        _parent.attr('data-value',_val);
        // _this.closest('.js-form-parent-approval').find('.js-remark-pending').addClass('d-none');
        _parent.attr('data-discipline',_this.data('discipline'));
        // }
    });

    $(document).on('click','.js-btn-approve-discipline-cost-estimate', function(){
        var _this = $(this);
        var _parent = _this.closest('.js-modal-approve-discipline');
        var _id = _parent.data('id');
        var _discipline = _this.attr('data-discipline');
        var _val = _this.attr('data-value');
        var _name = _this.attr('data-form');

        $.ajax({
           url:'/project/update-status/' + _id,
           method:'post',
           data: {
               'discipline': _discipline,
               'status' : _val
           },
           success:function(result){
               $('.js-modal-approve-discipline').modal('hide');
               // $('input[name="'+_name+'"][value="' + _val + '"]').prop('checked', true);
               notification('success','Status is updated','','success');
               setTimeout(function (){
                   window.location.href = '/project/' + _id;
               },2000);
           }
        });
    });

    $(document).on('hide.bs.modal','.js-modal-approve-discipline', function(){
        var _this = $(this);
        _this.find('.js-btn-approve-discipline-cost-estimate').removeAttr('data-discipline');
    });

    $(document).on('click','.js-edit-remark-project-btn', function(){
        $('.js-edit-remark-project-form').removeClass('d-none');
    });

    $(document).on('click','.js-save-project-remark', function (){
        var _this = $(this);
        var _remark = $('.js-remark-project').val();
        var _id = _this.data('id');
       $.ajax({
           'url': '/project/' + _id + '/update-remark',
           'method' : 'POST',
           'data': {remark : _remark},
           success:function (result) {
               $('.js-modal-remark-project').modal('hide');
               if(result.status === 200){
                   notification('success',result.message,'','success');
                   $('.js-remark').text(_remark);
                   $('.js-remark-project').val(_remark);
               } else {
                   notification('danger',result.message,'','');
               }
           }
       });
    });

    $('.js-select-user-position').on('change',function (){
        var _this = $(this);
        var _val = _this.val();
        var _other_form = $('.js-other-position-form');
        if(_val == 'others'){
            _other_form.removeClass('d-none');
        } else {
            _other_form.addClass('d-none');
        }
    });

});
