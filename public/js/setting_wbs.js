$(function(){
    var _idDelete = '';
    $('.js-delete-wbs').on('click', function(){
        _idDelete = $(this).data('id');
    });

    $('.js-modal-delete-wbs').on('hide.bs.modal', function(event) {
        _idDelete = '';
    });

    $('.js-delete-confirmation-wbs').on('click',function(){
        var url = '/work-breakdown-structure/' + _idDelete;
        $.ajax({
            url : url,
            type : 'DELETE',
            success : function(data){
                $('.js-modal-delete-wbs').hide();
                if(data.status === 200){
                    notification('success',data.message)
                    setTimeout(function(){
                        window.location.href = 'work-breakdown-structure';
                    },1000)
                } else {
                    notification('error',data.message);
                }
            }
        })
    });

    var _idDeleteWorkElement = '';
    $('.js-delete-work-element').on('click', function(){
        _idDeleteWorkElement = $(this).data('id');
    });

    $('.js-modal-work-element').on('hide.bs.modal', function(event) {
        _idDeleteWorkElement = '';
    });
    $('.js-delete-confirmation-wbs-work-element').on('click',function(){
        var url = '/work-breakdown-structure/' + _idDeleteWorkElement;
        $.ajax({
            url : url,
            type : 'DELETE',
            success : function(data){
                $('.js-modal-work-element').hide();
                if(data.status === 200){
                    notification('success',data.message)
                    setTimeout(function(){
                        window.location.href = '/work-breakdown-structure';
                    },1000)
                } else {
                    notification('error',data.message);
                }
            }
        })
    });
})
