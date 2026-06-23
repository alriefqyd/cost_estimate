(function ($) {
    'use strict';

    var $startBtn = $('#js-start-est-tour');
    if (!$startBtn.length) return;

    var tourKey = 'ce_est_discipline_tour_done';

    function buildSteps() {
        return [
            {
                element: document.querySelector('.est-page-header-card'),
                title: '📋 Project Context',
                intro: '<p>This page shows which project you are currently estimating. The <b>Work Breakdown Structure</b> link lets you view the WBS locations, disciplines, and work elements assigned to this project.</p>'
            },
            {
                element: document.getElementById('tour-est-toolbar'),
                title: '🛠 Toolbar',
                intro: '<p>The toolbar gives you real-time visibility into the editing session:</p>'
                     + '<ul style="padding-left:18px;margin:8px 0">'
                     + '<li><b>Discipline badge</b> — shows which discipline you are estimating (Civil, Mechanical, etc.)</li>'
                     + '<li><b>Connection dot</b> — green means you are connected and changes sync instantly; grey means offline</li>'
                     + '<li><b>Save status</b> — every cell change is autosaved automatically</li>'
                     + '<li><b>Online users</b> — coloured avatars show who else is editing right now</li>'
                     + '</ul>'
            },
            {
                element: document.getElementById('tour-est-disc-stats'),
                title: '📊 Discipline Cost Breakdown',
                intro: '<p>These cards show the <b>total estimated cost per discipline</b>.</p>'
                     + '<p>The progress bar shows each discipline\'s share of the overall estimate subtotal, so you can see at a glance which discipline drives the most cost.</p>'
            },
            {
                element: document.getElementById('tour-est-grid'),
                title: '📝 Estimate Grid',
                intro: '<p>The main estimate table. Rows are grouped by <b>Location → Discipline → Work Element → Work Item</b>.</p>'
                     + '<ul style="padding-left:18px;margin:8px 0">'
                     + '<li>Click a <b>Work Item</b> cell to search and select a work item from the catalogue</li>'
                     + '<li>Edit <b>Volume</b> and <b>Factorial</b> values directly in the cell</li>'
                     + '<li>Totals update automatically as you type</li>'
                     + '<li>Click the <b>+</b> button on a Work Element row to add a new item in that group</li>'
                     + '</ul>'
            },
            {
                element: document.getElementById('tour-est-add-btn'),
                title: '➕ Add Row',
                intro: '<p>Click <b>Add Row</b> to add a new work item to the estimate.</p>'
                     + '<p>A dialog will open where you select the <b>WBS location</b> (work element) first, then choose the <b>work item</b> from the catalogue. The unit rates for labour, equipment, and material are filled automatically.</p>'
            },
            {
                element: document.getElementById('tour-est-publish-btn'),
                title: '🚀 Publish for Review',
                intro: '<p>When your estimate is complete and ready for review, click <b>Publish</b>.</p>'
                     + '<p>This submits the estimate to the assigned reviewer and <b>locks the table</b> while awaiting approval. You will be able to edit again once the reviewer sends feedback.</p>'
                     + '<p style="margin-top:8px;padding:6px 10px;background:#fff3cd;border-left:3px solid #ffc107;border-radius:4px;color:#5a3e00;font-size:12px">'
                     + '<i class="fa fa-lightbulb"></i> <b>Tip:</b> Double-check your volumes and factorials before publishing — changes after submission require the reviewer to send the estimate back.</p>'
            },
            {
                element: document.getElementById('tour-est-totals'),
                title: '💰 Cost Summary',
                intro: '<p>The totals section at the bottom summarises the entire estimate:</p>'
                     + '<ul style="padding-left:18px;margin:8px 0">'
                     + '<li><b>Subtotal</b> — sum of all work item costs across all disciplines</li>'
                     + '<li><b>Contingency</b> — a percentage allowance for unforeseen costs. Adjust the percentage in the input field and it recalculates instantly.</li>'
                     + '<li><b>Grand Total</b> — subtotal plus contingency</li>'
                     + '</ul>'
            }
        ].filter(function (s) { return !!s.element; });
    }

    function startTour() {
        var steps = buildSteps();
        if (!steps.length) return;

        var lastStepIndex   = steps.length - 1;
        var lastStepReached = false;

        introJs()
            .setOptions({
                steps             : steps,
                nextLabel         : 'Next &rarr;',
                prevLabel         : '&larr; Back',
                doneLabel         : 'Finish',
                showBullets       : true,
                showProgress      : true,
                exitOnEsc         : false,
                exitOnOverlayClick: false,
                scrollToElement   : true,
                tooltipClass      : 'customTooltip'
            })
            .onchange(function () {
                if (this._currentStep >= lastStepIndex) {
                    lastStepReached = true;
                }
            })
            .onbeforeexit(function () {
                if (!lastStepReached) {
                    return false;
                }
                localStorage.setItem(tourKey, '1');
            })
            .start();
    }

    $startBtn.on('click', function () {
        startTour();
    });

    // Auto-start after React has mounted (React mount takes ~200–400 ms)
    if (!localStorage.getItem(tourKey)) {
        setTimeout(startTour, 1200);
    }

})(jQuery);
