$(function () {
    $('.js-show-hide-password').on('click', function () {
        var $input = $('#password');
        $input.attr('type', $input.attr('type') === 'password' ? 'text' : 'password');
        $(this).toggleClass('fa-eye fa-eye-slash');
    });

    $('form').on('submit', function () {
        var $btn = $(this).find('.lp-btn');
        $btn.addClass('loading').find('.lp-spinner').show();
        $btn.contents().filter(function () { return this.nodeType === 3; }).last().replaceWith('Signing in…');
    });
});
