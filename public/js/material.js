/**
 * Delete Man Power
 */
$(function() {
    var _idDelete = '';
    $('.js-delete-material').on('click', function () {
        _idDelete = $(this).data('id');
    });

    $('.js-modal-delete-material').on('hide.bs.modal', function (event) {
        _idDelete = '';
    });

    $(document).on('click', '.js-delete-confirmation-material', function (e) {
        var url = '/material/' + _idDelete
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function (data) {
                $('.js-modal-delete-material').hide();
                if (data.status === 200) {
                    notification('success', data.message)
                    setTimeout(function () {
                        window.location.href = 'material';
                    }, 1000)
                } else {
                    notification('error', data.message)
                }
            }
        })
    })

    $(document).on('change','.js-select-category-material',function(){
        var _this = $(this);
        var _code = _this.find('option:selected').attr('data-code');
        var _sufix = parseInt(_this.find('option:selected').attr('data-num-count')) + 1;
        var _newCode = _code + '.' + _sufix.toString().padStart(3,'0');
        $('.js-material-code').val(_newCode);
    });
})
