<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="">
    <meta name="keywords"
          content="admin template">
    <meta name="author" content="Al Riefqy">
    <meta name="csrf-token" content="{{ csrf_token() }}">
{{--    <link rel="icon" href="{{asset('/assets/images/favicon.png')}}" type="image/x-icon">--}}
{{--    <link rel="shortcut icon" href="{{asset('/assets/images/favicon.png')}}" type="image/x-icon">--}}
    <title>Cost Estimate Management</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/fontawesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/feather-icon.css')}}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/chartist.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/prism.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/vector-map.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/uikit/uikit.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/uikit/components/nestable.css')}}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{asset('/assets/css/color-1.css')}}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/responsive.css')}}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/select2.css')}}">
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
<script src="{{asset('/assets/js/jquery-3.5.1.min.js')}}"></script>
<!-- feather icon js-->
<script src="{{'/assets/js/icons/feather-icon/feather.min.js'}}"></script>
<script src="{{'/assets/js/icons/feather-icon/feather-icon.js'}}"></script>
<!-- Sidebar jquery-->
<script src="{{'/assets/js/sidebar-menu.js'}}"></script>
<script src="{{'/assets/js/config.js'}}"></script>
<!-- Bootstrap js-->
<script src="{{'/assets/js/bootstrap/popper.min.js'}}"></script>
<script src="{{'/assets/js/bootstrap/bootstrap.min.js'}}"></script>
<!-- Plugins JS start-->
<script src="{{'/assets/js/chart/chartist/chartist.js'}}"></script>
<script src="{{'/assets/js/chart/chartist/chartist-plugin-tooltip.js'}}"></script>
<script src="{{'/assets/js/chart/knob/knob.min.js'}}"></script>
<script src="{{'/assets/js/chart/knob/knob-chart.js'}}"></script>
<script src="{{'/assets/js/chart/apex-chart/apex-chart.js'}}"></script>
<script src="{{'/assets/js/chart/apex-chart/stock-prices.js'}}"></script>
<script src="{{'/assets/js/prism/prism.min.js'}}"></script>
<script src="{{'/assets/js/clipboard/clipboard.min.js'}}"></script>
<script src="{{'/assets/js/counter/jquery.waypoints.min.js'}}"></script>
<script src="{{'/assets/js/counter/jquery.counterup.min.js'}}"></script>
<script src="{{'/assets/js/counter/counter-custom.js'}}"></script>
<script src="{{'/assets/js/custom-card/custom-card.js'}}"></script>
<script src="{{'/assets/js/notify/bootstrap-notify.min.js'}}"></script>
<script src="{{'/assets/js/vector-map/jquery-jvectormap-2.0.2.min.js'}}"></script>
<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-world-mill-en.js'}}"></script>
<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-us-aea-en.js'}}"></script>
<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-uk-mill-en.js'}}"></script>
<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-au-mill.js'}}"></script>
<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js'}}"></script>
<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-in-mill.js'}}"></script>
<script src="{{'/assets/js/vector-map/map/jquery-jvectormap-asia-mill.js'}}"></script>
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
<script src="{{'/js/estimate_discipline.js'}}"></script>
<script src="{{'/js/work_breakdown_structure.js'}}"></script>
<script src="{{'/js/man_power.js'}}"></script>
<script src="{{'/js/tool_equipment.js'}}"></script>
<script src="{{'/js/tool_equipment_category.js'}}"></script>
<script src="{{'/js/material.js'}}"></script>
<script src="{{'/js/material_category.js'}}"></script>
<script src="{{'/js/work_item.js'}}"></script>
<!-- login js-->
<!-- Plugin used-->
</body>
</html>
