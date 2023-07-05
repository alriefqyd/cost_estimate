<!-- Page Header Start-->
<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
            <div class="logo-wrapper"><a href=""><img class="img-fluid" src="{{asset('/assets/images/logo/vale.png')}}" alt=""></a></div>
            <div class="dark-logo-wrapper"><a href=""><img class="img-fluid" src="{{asset('/assets/images/logo/vale.png')}}" alt=""></a></div>
            <div class="toggle-sidebar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-align-center status_toggle middle"
                                             id="sidebar-toggle"><line x1="18" y1="10" x2="6" y2="10">
                    </line>
                    <line x1="21" y1="6" x2="3" y2="6"></line>
                    <line x1="21" y1="14" x2="3" y2="14"></line>
                    <line x1="18" y1="18" x2="6" y2="18"></line>
                </svg></div>
        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">
                <li class="onhover-dropdown p-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-primary" type="button"><a :href="route('logout')"
                                                                               onclick="event.preventDefault();
                                this.closest('form').submit();">
                                <i data-feather="log-out"></i>Log out</a></button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
    </div>
</div>
<!-- Page Header Ends                              -->
