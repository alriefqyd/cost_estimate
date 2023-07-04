<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.assets')
</head>
<body>
<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="theme-loader">
        <div class="loader-p"></div>
    </div>
</div>
<!-- Loader ends-->
<!-- page-wrapper Start       -->
<div class="page-wrapper compact-wrapper" id="pageWrapper">
@include('layouts.header')
<!-- Page Body Start-->
    <div class="page-body-wrapper sidebar-icon">
        @include('layouts.sidebar')
        <div class="page-body">
            @yield('main')
        </div>
        @include('layouts.footer')
        @include('mustache')
    </div>
</div>
<!-- latest jquery-->
<script src="{{asset('/assets/js/jquery-3.6.0.min.js')}}"></script>
<!-- feather icon js-->
{{--<script src="{{'/assets/js/icons/feather-icon/feather.min.js'}}"></script>--}}
{{--<script src="{{'/assets/js/icons/feather-icon/feather-icon.js'}}"></script>--}}
<!-- Sidebar jquery-->
<script src="{{'/assets/js/sidebar-menu.js'}}"></script>
<script src="{{'/assets/js/config.js'}}"></script>
<!-- Bootstrap js-->
<script src="{{'/assets/js/bootstrap/popper.min.js'}}"></script>
<script src="{{'/assets/js/bootstrap/bootstrap.min.js'}}"></script>
<!-- Plugins JS start-->
<script src="{{asset('/assets/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
<script src="{{'/assets/js/chart/chartist/chartist.js'}}"></script>
<script src="{{'/assets/js/chart/chartist/chartist-plugin-tooltip.js'}}"></script>
{{--<script src="{{'/assets/js/chart/knob/knob.min.js'}}"></script>--}}
{{--<script src="{{'/assets/js/chart/knob/knob-chart.js'}}"></script>--}}
{{--<script src="{{'/assets/js/chart/apex-chart/apex-chart.js'}}"></script>--}}
{{--<script src="{{'/assets/js/chart/apex-chart/stock-prices.js'}}"></script>--}}
<script src="{{'/assets/js/prism/prism.min.js'}}"></script>
<script src="{{'/assets/js/clipboard/clipboard.min.js'}}"></script>
<script src="{{'/assets/js/counter/jquery.waypoints.min.js'}}"></script>
<script src="{{'/assets/js/counter/jquery.counterup.min.js'}}"></script>
<script src="{{'/assets/js/counter/counter-custom.js'}}"></script>
<script src="{{'/assets/js/custom-card/custom-card.js'}}"></script>
<script src="{{'/assets/js/notify/bootstrap-notify.min.js'}}"></script>
<script src="{{'/assets/js/vector-map/jquery-jvectormap-2.0.2.min.js'}}"></script>
{{--<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-world-mill-en.js'}}"></script>--}}
{{--<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-us-aea-en.js'}}"></script>--}}
{{--<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-uk-mill-en.js'}}"></script>--}}
{{--<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-au-mill.js'}}"></script>--}}
{{--<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js'}}"></script>--}}
{{--<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-in-mill.js'}}"></script>--}}
{{--<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-asia-mill.js'}}"></script>--}}
<script src="{{'/assets/js/dashboard/default.js'}}"></script>
{{--<script src="{{'/assets/js/notify/index.js'}}"></script>--}}
<script src="{{'/assets/js/datepicker/date-picker/datepicker.js'}}"></script>
<script src="{{'/assets/js/datepicker/date-picker/datepicker.en.js'}}"></script>
<script src="{{'/assets/js/datepicker/date-picker/datepicker.custom.js'}}"></script>
<script src="{{'/assets/js/product-tab.js'}}"></script>
<script src="{{'/assets/js/uikit/uikit.js'}}"></script>
<script src="{{'/assets/js/uikit/components/sortable.js'}}"></script>
<script src="{{'/assets/js/uikit/components/nestable.js'}}"></script>
<script src="{{'/assets/js/mustache.min.js'}}"></script>
<script src="{{'/js/jquery.formatCurrency-1.4.0.min.js'}}"></script>
<script src="{{'/js/jquery.formatCurrency-1.4.0.js'}}"></script>
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{'/assets/js/script.js'}}"></script>
<script src="{{'/assets/js/theme-customizer/customizer.js'}}"></script>

<script src="{{'/assets/js/select2/select2.full.min.js'}}"></script>
<script src="{{'/assets/js/select2/select2-custom.js'}}"></script>
<script src="{{'/assets/js/jquery-validation/dist/jquery.validate.min.js'}}"></script>
<script src="{{'/assets/js/form-wizard/form-wizard-three.js'}}"></script>
<script src="{{'/assets/js/form-wizard/jquery.backstretch.min.js'}}"></script>

<script src="{{'/js/config.js'}}"></script>
<script src="{{'/assets/select2/dist/js/select2.min.js'}}"></script>
<script src="{{'/js/notification.js'}}"></script>
<script src="{{'/js/application.js'}}"></script>
<script src="{{'/js/project.js'}}"></script>
<script src="{{'/js/estimate_discipline.js'}}"></script>
<script src="{{'/js/work_breakdown_structure.js'}}"></script>
<script src="{{'/js/man_power.js'}}"></script>
<script src="{{'/js/tool_equipment.js'}}"></script>
<script src="{{'/js/tool_equipment_category.js'}}"></script>
<script src="{{'/js/material.js'}}"></script>
<script src="{{'/js/material_category.js'}}"></script>
<script src="{{'/js/work_item.js'}}"></script>
<script src="{{'/js/setting_wbs.js'}}"></script>
<!-- login js-->
<!-- Plugin used-->
</body>
</html>
