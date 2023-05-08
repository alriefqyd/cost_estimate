$(function (){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.select2').each(function (){
        var _this = $(this);
        _this.select2({
            allowClear:true,
            placeholder:_this.data('placeholder'),
        });
    });


    $('.select2-ajax').select2({
        allowClear:$(this).data('allowClear'),
        placeholder:$(this).data('placeholder'),
        minimumInputLength:$(this).data('minimumInputLength'),
        ajax:{
            url:'/getUserEmployee',
            data:function (params) {
                return {
                    search: params.term,
                    subject: $(this).data('subject')
                }
            },
            processResults: function (resp) {
                console.log(resp)
                return {
                    results : resp
                }
            }
        }
    })
})
