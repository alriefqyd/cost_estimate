(function ($) {
    'use strict';

    var SIDEBAR_STEPS = 10; // first N steps target sidebar elements

    var steps = [
        {
            element: document.getElementById('tour-sidebar-toggle'),
            title: 'Sidebar Toggle',
            intro: 'Click this icon to <b>expand or collapse the sidebar</b> at any time. The sidebar gives you quick access to all modules in this application.'
        },
        {
            element: document.getElementById('tour-sidebar-nav'),
            title: 'Navigation Menu',
            intro: 'Here is a list of menus and features available in the Cost Estimate web application. Explore these options to make the most out of our services and streamline your cost estimation process.'
        },
        {
            element: document.getElementById('tour-nav-dashboard'),
            title: 'Dashboard',
            intro: 'Return to your <b>personalised dashboard</b> at any time by clicking here. View KPIs, recent projects, and quick access shortcuts.'
        },
        {
            element: document.getElementById('tour-nav-estimate'),
            title: 'Cost Estimate',
            intro: 'View the complete list of cost estimate projects and easily create new estimates by clicking this menu.'
        },
        {
            element: document.getElementById('tour-nav-workitem'),
            title: 'Work Item',
            intro: 'View all work items and work item categories. You can create new work items or manage existing categories from this menu.'
        },
        {
            element: document.getElementById('tour-nav-manpower'),
            title: 'Man Power',
            intro: 'View the complete list of manpower and create new entries by clicking this menu.'
        },
        {
            element: document.getElementById('tour-nav-tools'),
            title: 'Tools & Equipment',
            intro: 'View the complete list of tools & equipment and easily create new entries by clicking this menu.'
        },
        {
            element: document.getElementById('tour-nav-material'),
            title: 'Materials',
            intro: 'View the complete list of materials and easily create new entries by clicking this menu.'
        },
        {
            element: document.getElementById('tour-nav-wbs'),
            title: 'WBS Setting',
            intro: 'Manage the <b>Work Breakdown Structure (WBS)</b> through this menu. Define locations, disciplines, and work elements used across all estimates.'
        },
        {
            element: document.getElementById('tour-nav-user'),
            title: 'User Setting',
            intro: 'Manage all users, authorities, and permissions by clicking this menu.'
        },
        {
            element: document.getElementById('tour-profile'),
            title: 'Your Profile',
            intro: 'To change your profile or log out, please click this icon.'
        },
        {
            element: document.getElementById('tour-home-greeting'),
            title: 'Your Dashboard',
            intro: 'Welcome to your personalised dashboard. The greeting reflects your local time and today\'s date.'
        },
        {
            element: document.getElementById('tour-home-kpi'),
            title: 'KPI Summary',
            intro: '<p>Each card shows live counts:</p>'
                 + '<ul style="padding-left:18px;margin:8px 0">'
                 + '<li><b>Projects</b> — projects you own or are assigned to</li>'
                 + '<li><b>Work Items / Man Power / Materials / Equipment</b> — all records in the system</li>'
                 + '</ul>'
                 + '<p>The progress bar shows the <b>approved ÷ total</b> ratio at a glance.</p>'
        },
        {
            element: document.getElementById('tour-home-recent'),
            title: 'My Recent Projects',
            intro: '<p>Your <b>6 most recent projects</b> appear here. Click any row to open the full project detail page.</p>'
                 + '<p>The coloured badge shows the current status — Draft, Pending Review, Approved, or Rejected.</p>'
        },
        {
            element: document.getElementById('tour-home-qa'),
            title: 'Quick Access',
            intro: 'Shortcuts to all main modules. Jump directly to Cost Estimates, Work Items, Man Power, Tools & Equipment, Materials, WBS Settings, and User Management from here.'
        },
        {
            element: document.getElementById('tour-home-calendar'),
            title: 'Production Calendar',
            intro: 'A reference calendar for tracking and planning project activities. Use it to align your estimate timelines with site schedules.'
        }
    ].filter(function (s) { return !!s.element; });

    function expandSidebar() {
        $('.main-nav').removeClass('close_icon');
    }

    function collapseSidebar() {
        $('.main-nav').addClass('close_icon');
    }

    function startTour() {
        expandSidebar();

        introJs()
            .oncomplete(function () {
                collapseSidebar();
                localStorage.setItem('ce_home_tour_done', '1');
            })
            .onexit(function () {
                collapseSidebar();
                localStorage.setItem('ce_home_tour_done', '1');
            })
            .onbeforechange(function (targetEl) {
                var idx = this._currentStep;
                if (idx < SIDEBAR_STEPS) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            })
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
