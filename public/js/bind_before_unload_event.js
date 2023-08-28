function bindBeforeUnloadEvent(){
    var _confirm_page = $('.js-confirm-row')
    if(_confirm_page.length > 0){
        $(window).on('beforeunload', function(e) {
            e.preventDefault()
            // Return a confirmation message to prompt the user
            return 'Are you sure you want to leave this page?';
        });
    }
}
