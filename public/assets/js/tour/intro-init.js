(function($) {
    "use strict";
    // Only auto-start when the page has explicit data-intro elements.
    // Pages with custom introJs tours (home, WBS form, project detail) manage
    // their own introJs instances and must not be pre-empted by this global init.
    if (document.querySelectorAll('[data-intro]').length > 0) {
        introJs().start();
    }
})(jQuery);
