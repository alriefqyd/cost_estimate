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
})
