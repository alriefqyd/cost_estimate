<!-- Page Sidebar Start-->
<header class="main-nav">
    <div class="sidebar-user text-center">
        <img class="img-90 rounded-circle" src="{{asset('assets/images/dashboard/1.png')}}" alt="">
        <a href="user-profile.html">
            <h6 class="mt-3 f-14 f-w-600">{{auth()->user()->name}}</h6></a>
        <p class="mb-0 font-roboto">Software Engineer</p>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-main-title">

                    </li>
                    <li><a class="nav-link menu-title" href="/project"><i data-feather=""></i><span>Cost Estimate</span></a></li>
                    <li><a class="nav-link menu-title" href="/work-item"><i data-feather=""></i><span>Work Item</span></a></li>
                    <li><a class="nav-link menu-title" href="/man-power"><i data-feather=""></i><span>Man Power List</span></a></li>
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)"><i data-feather=""></i><span>Tool Equipment </span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="/tool-equipment">Tool Equipment List</a></li>
                            <li><a href="/tool-equipment-category">Tool Equipment Category </a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)"><i data-feather=""></i><span>Material </span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="/material">Material</a></li>
                            <li><a href="/material-category">Material Category </a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link menu-title" href="/work-breakdown-structure"><i data-feather=""></i><span>WBS</span></a></li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
<!-- Page Sidebar Ends-->
