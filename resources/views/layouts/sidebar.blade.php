<!-- Page Sidebar Start-->
<header class="main-nav">
    <div class="sidebar-user text-center">
        @customDirective()
        <a href="">
            <h6 class="mt-3 f-14 f-w-600">{{auth()->user()->profiles?->full_name}}</h6></a>
        <p class="mb-0 font-roboto">{{auth()->user()->profiles?->getPosition()}}</p>
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
                    @canAny(['viewAny','update','create','delete','view'], App\Models\Project::class)
                        <li><a class="nav-link menu-title" href="/project"><i data-feather=""></i><span>Cost Estimate Project</span></a></li>
                    @endCan
                    @canAny(['viewAny','update','create','delete','view'], App\Models\WorkItem::class)
                        <li><a class="nav-link menu-title" href="/work-item"><i data-feather=""></i><span>Work Item</span></a></li>
                    @endcan
                    @canAny(['viewAny','update','create','delete','view'], App\Models\ManPower::class)
                        <li><a class="nav-link menu-title" href="/man-power"><i data-feather=""></i><span>Man Power List</span></a></li>
                    @endCan
                    @canAny(['viewAny','update','create','delete','view'], App\Models\EquipmentTools::class)
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)"><i data-feather=""></i><span>Tool Equipment </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="/tool-equipment">Tool Equipment List</a></li>
                                <li><a href="/tool-equipment-category">Tool Equipment Category </a></li>
                            </ul>
                        </li>
                    @endcan
                    @canAny(['viewAny','update','create','delete','view'], App\Models\Material::class)
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)"><i data-feather=""></i><span>Material </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="/material">Material</a></li>
                                <li><a href="/material-category">Material Category </a></li>
                            </ul>
                        </li>
                    @endcan
                    @canAny(['viewAny','update','create','delete','view'], App\Models\WorkBreakdownStructure::class)
                        <li><a class="nav-link menu-title" href="/work-breakdown-structure"><i data-feather=""></i><span>WBS</span></a></li>
                    @endcan
                    @can('viewAny', App\Models\User::class)
                        <li><a class="nav-link menu-title" href="/user"><i data-feather=""></i><span>User</span></a></li>
                    @endcan
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
<!-- Page Sidebar Ends-->
