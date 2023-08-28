<!-- Page Header Start-->
<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
            <div class="float-start">
                <img class="img-fluid onhover-dropdown" style src="{{asset('/assets/images/logo-vale-256.png')}}" alt="">
                <div class="dark-logo-wrapper"><a href=""><img class="img-fluid" src="{{asset('/assets/images/logo-vale-256.png')}}" alt=""></a></div>

            </div>
            <div class="float-end">
                <div class="toggle-sidebar mt-2"><i class="status_toggle middle fa fa-navicon"></i></div>
            </div>
        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <div class="float-end mr-6">
                <li class="onhover-dropdown">
                    <div class="setting-primary">
                        @customDirective()
                    </div>
                    <ul class="chat-dropdown onhover-show-div">
                        <li>
                            <div class="media">
                                <div style="width: 20%">
                                    @customDirective()
                                </div>
                                <div class="media-body"><span>{{auth()->user()->profiles?->full_name}}</span>
                                    <p class="f-12 light-font">{{auth()->user()->profiles?->getPosition()}}</p>
                                </div>
                            </div>
                        </li>
                        <li class="text-center">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-outline-primary" style="font-size: 12px" type="button"><a :href="route('logout')"
                                                                                       onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        <i data-feather="log-out"></i>Log out</a></button>
                            </form>
                        </li>
                    </ul>
                </li>
            </div>
        </div>
    </div>
</div>
<!-- Page Header Ends                              -->
