$(function(){
    $('.js-download-summary-xlsx').on('click',function(e){
        e.preventDefault();
        var _this = $(this);
        var _idProject = _this.data('id');
        var _file_name = _this.data('name');
        var _isDetail = _this.attr('data-isDetail');
        _this.attr('disabled','disabled');
         _this.closest('.btn-group').find('.loader-box').removeClass('d-none');
        $.ajax({
            url: '/cost-estimate-summary/export/'+ _idProject,
            method: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            data: {
                    'isDetail': _isDetail,
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
                _this.closest('.btn-group').find('.loader-box').addClass('d-none');
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
        _modal.modal('show');
        _parent.attr('data-form',_this.attr('name'));
        _parent.attr('data-value',_val);
        // _this.closest('.js-form-parent-approval').find('.js-remark-pending').addClass('d-none');
        _parent.attr('data-discipline',_this.data('discipline'));
        // }
    });

    $('.js-modal-approve-discipline').on('shown.bs.modal', function (e){
        var _source = $(e.relatedTarget);
        var _discipline = _source.data('discipline');
        var _template = $('#js-template-modal-update-status-project');
        var template = $(_template).html();
        var _id = $('.js-hidden-id-project').val();
        Mustache.parse(template);
        $.ajax({
            url:"/project/getProjectDisciplineStatus/" + _id,
            method: "GET",
            data: {
                'discipline': _discipline,
            },
            success: function (result){
                var _data = {
                    'discipline' : _discipline,
                    'isPending' : result.data.result == "PENDING" ? true : false,
                    'isApprove' : result.data.result == "APPROVE" ? true : false,
                    'isRejected' : result.data.result == 'REJECTED' ? true : false,
                    'remark' : result.data.remark
                };
                var _temp = Mustache.render(template,_data);
                $('.js-modal-approve-discipline').find('.loading-spinner').addClass('d-none');
                $('.js-form-approval').append(_temp);
            }

        });
    });

    $('.js-modal-approve-discipline').on('hide.bs.modal', function (){
        $('.js-modal-approve-discipline').find('.loading-spinner').removeClass('d-none');
        $('.js-form-approval').find('.js-row-form-status').remove();
    });

    $(document).on('change','.js-checkbox-discipline-status',function(){
       var _this = $(this);
       var _val = _this.val();
       if(_val === "REJECTED"){
           _this.closest('.js-row-form-status').find('.js-form-remark-rejected').removeClass('d-none');
       } else {
           _this.closest('.js-row-form-status').find('.js-form-remark-rejected').addClass('d-none');
       }
    });

    $(document).on('click','.js-btn-approve-discipline-cost-estimate', function(e){
        var _this = $(this);
        var _parent = _this.closest('.js-modal-approve-discipline');
        var _id = $('.js-hidden-id-project').val();
        var _discipline = $('.js-modal-discipline').val();
        var _val = $('input[name="checkbox-discipline"]:checked').val();
        var _remark = _this.closest('.js-modal-approve-discipline').find('.js-remark-project').val();

        $.ajax({
           url:'/project/update-status/' + _id,
           method:'post',
           data: {
               'discipline': _discipline,
               'remark' : _remark,
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

    $('.js-modal-delete-project').on('shown.bs.modal', function (e){
        var _button = $(e.relatedTarget);
        var _project_id = _button.data('id');
        $(this).find('.js-delete-project').data('id', _project_id);
    });

    $('.js-delete-project').on('click', function(e){
        e.preventDefault();
        var _id = $(this).data('id');

        $('.js-modal-delete-project').modal('hide');
        $('.js-modal-loading-project').modal('show');

        $.ajax({
            url:'/project/' + _id,
            type: 'DELETE',
            success: function (data) {
                $('.js-modal-loading-project').modal('hide');
                if (data.status === 200) {
                    notification('success', data.message)
                    setTimeout(function () {
                        window.location.href = 'project';
                    }, 1000);
                } else {
                    notification('error', data.message)
                }
            }
        });
    })

    $(document).on('click','.js-full-text', function(e){
        e.preventDefault();
        var _this = $(this);
        var _text = _this.closest('p').data('text');
        _this.siblings('.js-text-full-remark').text(_text);
        _this.remove();
    });

    $('.js-modal-duplicate-project').on('shown.bs.modal', function (e){
        var _button = $(e.relatedTarget);
        var _project_id = _button.data('id');
        $(this).find('.js-duplicate_project_id').val(_project_id);
    });

    $(document).on('click', '.js-duplicate-project', function(e) {
        e.preventDefault();
        var _this = $(this);
        var _id = $('.js-duplicate_project_id').val();


        $('.js-modal-duplicate-project').modal('hide');
        $('.js-modal-loading-project').modal('show');

        $.ajax({
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/duplicate-project/' + _id,
            data: JSON.stringify({ project_id: _id }),
            success: function(response) {
                $('.js-modal-loading-project').modal('hide');
                if (response.status === 200) {
                    notification('success', response.message);
                    setTimeout(function() {
                        window.location.href = '/project/' + response.data.project_id;
                    }, 1000);
                } else {
                    notification('error', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    var getReviewerForm = function(_formEngineer, _formReviewer){
        var _this = $(this);

        if(_formEngineer.val() !== ""){
            console.log(_formReviewer);
            _formReviewer.removeClass('d-none');
        } else {
            _formReviewer.addClass('d-none');
        }
    }

    var setOptionReviewer = function (e){
        var _this = $(e);
        var _subject = _this.closest('.row').find('.js-design-engineer');
        if(_this.data("select2")) _this.select2("destroy")
        _this.select2({
            allowClear:true,
            width:'100%',
            ajax:{
                url : '/getReviewer',
                data:function (params) {
                    return {
                        discipline : _this.data('subject'),
                        q: params.term
                    };
                },
                processResults: function (resp) {
                    return {
                        results: resp
                    };
                }
            }
        });
    }

    $('.js-reviewer-engineer-select').each(function(){
        setOptionReviewer(this);
    });

    $('.js-design-engineer').on('change',function(){
        var _this = $(this);
        var _formEngineer = _this;
        var _formReviewer = _this.closest('.row').find('.js-reviewer-engineer');
        getReviewerForm(_formEngineer, _formReviewer);
    });


});
