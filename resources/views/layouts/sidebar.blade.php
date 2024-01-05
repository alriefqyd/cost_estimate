<!-- Page Sidebar Start-->
<header class="main-nav">
    <nav>
        <div class="main-navbar mt-1">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-main-title">

                    </li>
                    @canAny(['viewAny','update','create','delete','view'], App\Models\Project::class)
                        <li class="margin-sm">
                            <a class="nav-link menu-title" data-bs-toggle="tooltip" data-bs-placement="right" title="Project Cost Estimate" href="/project">
                                <i data-feather="layers"></i>
                                <label class="text-small">Cost Estimate</label>
                            </a>
                        </li>
                    @endCan
                    @php
                        $workItemPermissions = ['viewAny','update','create','delete','view'];
                    @endphp
                    @if (auth()->user()->canAny($workItemPermissions, App\Models\WorkItem::class) || auth()->user()->canAny($workItemPermissions, App\Models\WorkItemType::class))
                        <li class="dropdown margin-sm">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="briefcase"></i>
                                <label class="text-small">Work Item</label>
                            </a>
                            <ul class="nav-submenu menu-content">
                                @canAny($workItemPermissions, App\Models\WorkItem::class)
                                    <li class="p-0 m-0 cursor-pointer">
                                        <a class="cursor-pointer" href="/work-item">
                                            <label class="text-small">Work Item List <i class="fa fa-chevron-right"></i></label>
                                        </a>
                                    </li>
                                @endcan
                                @canAny($workItemPermissions, App\Models\WorkItemType::class)
                                    <li class="p-0 m-0 cursor-pointer">
                                        <a class="cursor-pointer" href="/work-item-category">
                                            <label class="text-small">Work Item Category <i class="fa fa-chevron-right"></i></label>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                    @canAny(['viewAny','update','create','delete','view'], App\Models\ManPower::class)
                        <li class="margin-sm">
                            <a class="nav-link menu-title" href="/man-power">
                               <img class="text-center" src="{{'/assets/icons/helmet-safety-solid.svg'}}" style="width: 25%" alt="Custom Icon"></i>
                               <label class="text-small">Man Power</label>
                            </a>
                        </li>
                    @endCan
                    @canAny(['viewAny','update','create','delete','view'], App\Models\EquipmentTools::class)
                    <li class="dropdown margin-sm">
                        <a class="nav-link menu-title" href="javascript:void(0)"><i class="fa fa-wrench"></i>
                           <label class="text-small">Tools & Equipments</label>
                        </a>
                        <ul class="nav-submenu menu-content">
                           <li class="p-0 m-0"><a class="cursor-pointer" href="/tool-equipment"><label class="text-small">
                                       Tools Equipment List <i class="fa fa-chevron-right"></i>
                                   </label></a></li>
                           <li class="p-0 m-0"><a class="cursor-pointer" href="/tool-equipment-category"><label class="text-small">Tool Equipment Category
                                       <i class="fa fa-chevron-right"></i> </label></a></li>
                        </ul>
                    </li>
                    @endcan
                    @canAny(['viewAny','update','create','delete','view'], App\Models\Material::class)
                    <li class="dropdown margin-sm">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                           <i data-feather="truck"></i>
                           <label class="text-small">Materials</label>
                        </a>
                    <ul class="nav-submenu menu-content">
                       <li class="p-0 m-0"><a href="/material"><label class="text-small">Material List</label></a></li>
                       <li><a href="/material-category"><label class="text-small">Material Category</label></a></li>
                    </ul>
                    </li>
                    @endcan
                    @canAny(['viewAny','update','create','delete','view'], App\Models\WorkBreakdownStructure::class)
                    <li class="margin-sm">
                        <a class="nav-link menu-title" href="/work-breakdown-structure">
                           <i data-feather="align-right"></i>
                           <label class="text-small">WBS Setting</label>
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', App\Models\User::class)
                    <li class="margin-sm"><a class="nav-link menu-title" href="/user">
                           <i data-feather="users"></i>
                        <label class="text-small">User Setting</label></a>
                    </li>
                    @endcan
                </ul>
            </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
<!-- Page Sidebar Ends-->
