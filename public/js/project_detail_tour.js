(function ($) {
    'use strict';

    var $startBtn = $('#js-start-tour');
    if (!$startBtn.length) return;

    var tourKey     = 'ce_detail_tour_done';
    var isReviewer  = $startBtn.data('is-reviewer') == 1;

    var steps = [
        {
            element: document.getElementById('tour-stepper'),
            title: '📊 Project Progress Bar',
            intro: '<p>This bar at the top of the page shows <b>where your project currently stands</b>. There are 4 stages:</p>'
                 + '<ul style="padding-left:18px;margin:8px 0">'
                 + '<li><b>Project Info</b> — basic details filled in ✔</li>'
                 + '<li><b>WBS Structure</b> — the work plan / scope has been set up</li>'
                 + '<li><b>Estimate</b> — the cost estimate has been created and published</li>'
                 + '<li><b>Approval</b> — the estimate has been reviewed and signed off</li>'
                 + '</ul>'
                 + '<p style="margin-top:8px">'
                 + '<span style="color:#28a745;font-weight:bold">● Green</span> = done &nbsp;'
                 + '<span style="color:#ffc107;font-weight:bold">● Orange</span> = in progress &nbsp;'
                 + '<span style="color:#aaa;font-weight:bold">● Grey</span> = not started yet'
                 + '</p>'
        },
        {
            element: document.getElementById('tour-col-toggles'),
            title: '👁 Show or Hide Columns',
            intro: '<p>These two buttons let you <b>simplify the table</b> by hiding columns you do not need right now.</p>'
                 + '<ul style="padding-left:18px;margin:8px 0">'
                 + '<li><b>Unit Rates</b> — the price per unit for each cost item (e.g. IDR per man-hour). Hide this if you only care about totals.</li>'
                 + '<li><b>Factorials</b> — multiplier values that adjust the base cost for real-world site conditions. Hide this for a cleaner view.</li>'
                 + '</ul>'
                 + '<p>Click a button once to <b>hide</b> those columns; click again to <b>bring them back</b>.</p>'
        }
    ];

    var $annotateBtn = $('.js-btn-annotate-toggle');
    if (isReviewer && $annotateBtn.length) {
        steps.push({
            element: $annotateBtn[0],
            title: '📝 Leave Notes Directly on the Table',
            intro: '<p>As a <b>reviewer</b>, you can pin notes directly onto the cost estimate table.</p>'
                 + '<p>We have <b>activated it for you</b> so you can see how it works:</p>'
                 + '<ol style="padding-left:18px;margin:8px 0">'
                 + '<li>The button above turns <b>orange</b> and the label changes to <i>Done Annotating</i></li>'
                 + '<li>Click anywhere on the table to <b>drop a pin</b> at that spot</li>'
                 + '<li>A note box appears — type your comment, pick a pin type, then click <b>Save</b></li>'
                 + '<li>When finished, click <b>Done Annotating</b> to exit annotation mode</li>'
                 + '</ol>'
                 + '<p style="margin-top:8px;padding:6px 10px;background:#fff3cd;border-left:3px solid #ffc107;border-radius:4px;color:#5a3e00;font-size:12px">'
                 + '<i class="fa fa-lightbulb"></i> <b>Tip:</b> All team members assigned to this project can see your notes.</p>'
        });
    }

    steps = steps.filter(function (s) { return !!s.element; });

    function startTour() {
        var lastStepIndex        = steps.length - 1;
        var lastStepReached      = false;
        var annotateActivatedByTour = false;

        introJs()
            .setOptions({
                steps            : steps,
                nextLabel        : 'Next &rarr;',
                prevLabel        : '&larr; Back',
                doneLabel        : 'Finish',
                showBullets      : true,
                showProgress     : true,
                exitOnEsc        : false,
                exitOnOverlayClick: false,
                scrollToElement  : true,
                tooltipClass     : 'customTooltip'
            })
            .onchange(function () {
                var idx = this._currentStep;

                if (isReviewer && $annotateBtn.length) {
                    if (idx === lastStepIndex && $annotateBtn.hasClass('btn-outline-warning')) {
                        $annotateBtn.trigger('click');
                        annotateActivatedByTour = true;
                    }
                    if (idx < lastStepIndex && annotateActivatedByTour && $annotateBtn.hasClass('btn-warning')) {
                        $annotateBtn.trigger('click');
                        annotateActivatedByTour = false;
                    }
                }

                if (idx >= lastStepIndex) {
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

    if (!localStorage.getItem(tourKey)) {
        setTimeout(startTour, 700);
    }

})(jQuery);
