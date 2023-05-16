/**
 * Delete Man Power
 */
$(function() {
    var _idDelete = '';
    $('.js-delete-tool-equipment').on('click', function () {
        _idDelete = $(this).data('id');
    });

    $('.js-modal-delete-tool-equipment').on('hide.bs.modal', function (event) {
        _idDelete = '';
    });

    $(document).on('click', '.js-delete-confirmation-tool-equipment', function (e) {
        var url = '/tool-equipment/' + _idDelete
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function (data) {
                $('.js-modal-delete-tool-equipment').hide();
                if (data.status === 200) {
                    notification('success', data.message)
                    setTimeout(function () {
                        window.location.href = 'tool-equipment';
                    }, 1000)
                } else {
                    notification('error', data.message)
                }
            }
        })
    })
})
