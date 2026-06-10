(function ($) {
    'use strict';

    var steps = [
        {
            element: document.getElementById('tour-home-greeting'),
            title: '👋 Your Dashboard',
            intro: 'Welcome to your personalised dashboard. The greeting reflects your local time and today\'s date. Numbers on all KPI cards are scoped to <b>your role and assignments only</b>.'
        },
        {
            element: document.getElementById('tour-home-kpi'),
            title: '📊 KPI Summary',
            intro: '<p>Each card shows live counts scoped to your role:</p>'
                 + '<ul style="padding-left:18px;margin:8px 0">'
                 + '<li><b>Projects</b> — projects you own or are assigned to</li>'
                 + '<li><b>Work Items / Man Power / Materials / Equipment</b> — items you created (or all, if you are a reviewer)</li>'
                 + '</ul>'
                 + '<p>The progress bar shows the <b>approved ÷ total</b> ratio at a glance.</p>'
        },
        {
            element: document.getElementById('tour-home-recent'),
            title: '📁 My Recent Projects',
            intro: '<p>Your <b>6 most recent projects</b> appear here. Click any row to open the full project detail page.</p>'
                 + '<p>The coloured badge shows the current status — Draft, Pending Review, Approved, or Rejected.</p>'
        },
        {
            element: document.getElementById('tour-home-qa'),
            title: '⚡ Quick Access',
            intro: 'Shortcuts to all main modules. Jump directly to Cost Estimates, Work Items, Man Power, Tools & Equipment, Materials, WBS Settings, and User Management from here.'
        },
        {
            element: document.getElementById('tour-home-calendar'),
            title: '📅 Production Calendar',
            intro: 'A reference calendar for tracking and planning project activities. Use it to align your estimate timelines with site schedules.'
        }
    ].filter(function (s) { return !!s.element; });

    function startTour() {
        introJs()
            .oncomplete(function () { localStorage.setItem('ce_home_tour_done', '1'); })
            .onexit(function ()     { localStorage.setItem('ce_home_tour_done', '1'); })
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

    if (!localStorage.getItem('ce_home_tour_done')) {
        setTimeout(startTour, 700);
    }

})(jQuery);
