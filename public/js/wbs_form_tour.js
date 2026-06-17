(function ($) {
    'use strict';

    var steps = [
        {
            element: document.getElementById('tour-wbs-input'),
            title: '1 — Add a Location / Equipment',
            intro: '<p>Start by typing a <b>location or equipment name</b> — for example <i>"Pump Station 1"</i> or <i>"Workshop Area"</i>.</p>'
                 + '<p>Then click the <b>+ button</b> to add it as the first level of the tree below.</p>'
        },
        {
            element: document.getElementById('tour-wbs-headers'),
            title: '2 — Three-Level Hierarchy',
            intro: '<p>The WBS is organised into <b>3 levels</b>:</p>'
                 + '<ol style="padding-left:18px;margin:8px 0">'
                 + '<li><b>Location / Equipment</b> — the physical site or asset</li>'
                 + '<li><b>Discipline</b> — the engineering discipline (Civil, Mechanical, Electrical…)</li>'
                 + '<li><b>Work Element</b> — the specific scope of work within that discipline</li>'
                 + '</ol>'
                 + '<p>All three levels must be filled before you can save.</p>'
        },
        {
            element: document.getElementById('tour-wbs-tree'),
            title: '3 — Build the Tree',
            intro: '<p>Use the <b>+ circle icon</b> next to each row to add child items:</p>'
                 + '<ul style="padding-left:18px;margin:8px 0">'
                 + '<li>Click <b>+</b> on a Location row → adds a <b>Discipline</b></li>'
                 + '<li>Click <b>+</b> on a Discipline row → adds a <b>Work Element</b></li>'
                 + '</ul>'
                 + '<p>Drag the <b>≡ grip handle</b> to reorder rows. The numbered badge on each row updates automatically.</p>'
                 + '<p>Click the <b>× icon</b> to remove any row.</p>'
        },
        {
            element: document.getElementById('tour-wbs-save'),
            title: '4 — Save the WBS',
            intro: '<p>When the full hierarchy is complete, click <b>Save</b>. A confirmation prompt will appear before anything is stored.</p>'
                 + '<p style="margin-top:8px;padding:6px 10px;background:#fff3cd;border-left:3px solid #ffc107;border-radius:4px;color:#5a3e00;font-size:12px">'
                 + '<b>Note:</b> Changing the WBS after an estimate has been created will affect your existing estimate discipline data. Plan the structure carefully before saving.</p>'
        }
    ].filter(function (s) { return !!s.element; });

    function startTour() {
        introJs()
            .oncomplete(function () { localStorage.setItem('ce_wbs_form_tour_done', '1'); })
            .onexit(function ()     { localStorage.setItem('ce_wbs_form_tour_done', '1'); })
            .setOptions({
                steps             : steps,
                nextLabel         : 'Next &rarr;',
                prevLabel         : '&larr; Back',
                doneLabel         : 'Got it!',
                showBullets       : true,
                showProgress      : true,
                exitOnEsc         : true,
                exitOnOverlayClick: false,
                scrollToElement   : true,
                tooltipClass      : 'customTooltip'
            })
            .start();
    }

    if (!localStorage.getItem('ce_wbs_form_tour_done')) {
        setTimeout(startTour, 700);
    }

})(jQuery);
