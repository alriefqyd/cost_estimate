$(function(){

    // ── WBS Work Element drag-to-reorder (UIkit2 sortable) ────
    var $sortableBody = $('#js-wbs-sortable-body');
    if ($sortableBody.length && typeof UIkit2 !== 'undefined') {
        var sortable = UIkit2.sortable($sortableBody[0], {
            animation  : 150,
            handleClass: 'js-sort-handle'
        });

        function renumberRows() {
            $sortableBody.find('.js-sortable-row').each(function (i) {
                $(this).find('.js-order-num').text(i + 1);
            });
        }

        $sortableBody.on('change.uk.sortable', function () {
            renumberRows();
            $('.js-save-wbs-order').removeClass('d-none');
        });
    }

    $('.js-save-wbs-order').on('click', function () {
        var $btn = $(this);
        var ids  = [];
        $('#js-wbs-sortable-body').find('.js-sortable-row').each(function () {
            ids.push($(this).data('id'));
        });
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving…');
        $.ajax({
            url  : $btn.data('url'),
            type : 'POST',
            data : { ids: ids, _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.status === 200) {
                    notification('success', 'Order saved successfully');
                    $btn.addClass('d-none').prop('disabled', false)
                        .html('<i class="fa fa-check me-1"></i> Save Order');
                } else {
                    notification('danger', 'Failed to save order');
                    $btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Save Order');
                }
            },
            error: function () {
                notification('danger', 'Failed to save order');
                $btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Save Order');
            }
        });
    });
    // ── end drag-to-reorder ───────────────────────────────────

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
