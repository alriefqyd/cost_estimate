$(function () {
    $('.js-modal-delete-user').on('shown.bs.modal', function (e) {
        var _id = $(e.relatedTarget).data('id');
        $(this).find('.js-delete-user').data('id', _id);
    });

    $('.js-delete-user').on('click', function () {
        var _id = $(this).data('id');
        $.ajax({
            url: '/user/' + _id,
            type: 'DELETE',
            success: function (data) {
                $('.js-modal-delete-user').modal('hide');
                if (data.status === 200) {
                    notification('success', data.message);
                    setTimeout(function () { location.reload(); }, 1000);
                } else {
                    notification('error', data.message);
                }
            },
            error: function () {
                notification('error', 'Something went wrong.');
            }
        });
    });
});
