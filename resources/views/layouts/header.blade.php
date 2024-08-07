<!-- Page Header Start-->
<div class="page-main-header {{request()->segment(1) == "" ? "close_icon" : ""}}">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
            <div class="float-start">
                <a href="/">
                    <img class="img-fluid onhover-dropdown" style src="{{asset('/assets/images/logo-vale-256.png')}}" alt="">
                    <div class="dark-logo-wrapper"><a href=""><img class="img-fluid" src="{{asset('/assets/images/logo-vale-256.png')}}" alt=""></a></div>
                </a>
            </div>
            <div class="toggle-sidebar m-t-15"
                 data-tg-scroll-margin="0"
                 data-tg-fixed
                 data-tg-order="2"
                 @if(Request::segment(1) == '')
                 data-tg-tour="To view all menus and features of the Cost Estimate web application, click the icon next to the Vale logo."
                 @endif
            ><span style=""> <i class="status_toggle" data-feather="align-center"></i></span></div>
        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <div class="float-end mr-6">
                <li class="onhover-dropdown"
                    data-tg-scroll-margin="0"
                    data-tg-fixed
                    data-tg-order="3"
                    @if(Request::segment(1) == '')
                        data-tg-tour="To change your profile or log out, please click this icon."
                    @endif
                >
                    <div class="setting-primary">
                        @customDirective()
                    </div>
                    <ul class="chat-dropdown onhover-show-div">
                        <li>
                            <div class="media">
                                <div style="width: 20%">
                                    @customDirective()
                                </div>
                                <div class="media-body"><a href="/user/{{auth()->user()->id}}"> <span>{{auth()->user()->profiles?->full_name}}</span></a>
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
