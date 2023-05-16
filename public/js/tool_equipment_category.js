/**
 * Delete Man Power
 */
$(function() {
    var _idDelete = '';
    $('.js-delete-tool-equipment-category').on('click', function () {
        _idDelete = $(this).data('id');
    });

    $('.js-modal-delete-tool-equipment-category').on('hide.bs.modal', function (event) {
        _idDelete = '';
    });

    $(document).on('click', '.js-delete-confirmation-tool-equipment-category', function (e) {
        var url = '/tool-equipment-category/' + _idDelete
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function (data) {
                $('.js-modal-delete-tool-equipment-category').hide();
                if (data.status === 200) {
                    notification('success', data.message)
                    setTimeout(function () {
                        window.location.href = 'tool-equipment-category';
                    }, 1000)
                } else {
                    notification('error', data.message)
                }
            }
        })
    })
})
