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
            <div class="toggle-sidebar m-t-15" id="tour-sidebar-toggle"
                 data-tg-scroll-margin="0"
                 data-tg-fixed
                 data-tg-order="2"
                 data-tg-tour="To view all menus and features of the Cost Estimate web application, click the icon next to the Vale logo."><span style=""> <i class="status_toggle" data-feather="align-center"></i></span></div>
        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <div class="float-end mr-6" style="display:flex; align-items:center; gap:8px;">

                {{-- Notification Bell --}}
                <div class="notif-bell-wrap" id="js-notif-wrap">
                    <button class="notif-bell-btn" id="js-notif-btn" type="button" aria-label="Notifications">
                        <i class="fa fa-bell-o"></i>
                        <span class="notif-badge d-none" id="js-notif-badge">0</span>
                    </button>
                    <div class="notif-dropdown" id="js-notif-dropdown">
                        <div class="notif-header">
                            <span class="notif-title">Notifications</span>
                            <button class="notif-mark-all" id="js-notif-mark-all" type="button">Mark all read</button>
                        </div>
                        <ul class="notif-list" id="js-notif-list">
                            <li class="notif-empty">Loading…</li>
                        </ul>
                    </div>
                </div>

                <div class="hdr-profile-wrap onhover-dropdown" id="tour-profile"
                    data-tg-scroll-margin="0"
                    data-tg-fixed
                    data-tg-order="3"
                    data-tg-tour="To change your profile or log out, please click this icon.">

                    {{-- Trigger --}}
                    <button class="hdr-profile-trigger" type="button" aria-label="Account menu">
                        <div class="hdr-avatar hdr-avatar--sm">@customDirective()</div>
                        <span class="hdr-profile-name">{{ auth()->user()->profiles?->full_name ?? auth()->user()->name }}</span>
                        <i class="fa fa-chevron-down hdr-profile-chevron"></i>
                    </button>

                    {{-- Dropdown --}}
                    <div class="hdr-profile-dropdown onhover-show-div">

                        {{-- Identity block --}}
                        <div class="hdr-pd-identity">
                            <div class="hdr-avatar hdr-avatar--md">@customDirective()</div>
                            <div class="hdr-pd-info">
                                <span class="hdr-pd-fullname">{{ auth()->user()->profiles?->full_name ?? auth()->user()->name }}</span>
                                <span class="hdr-pd-role">{{ auth()->user()->profiles?->getPosition() ?? '—' }}</span>
                            </div>
                        </div>

                        <div class="hdr-pd-divider"></div>

                        {{-- Nav items --}}
                        <a class="hdr-pd-item" href="{{ route('profile.show') }}">
                            <i class="fa fa-user-circle hdr-pd-item-icon"></i>
                            My Profile
                        </a>

                        <div class="hdr-pd-divider"></div>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="hdr-pd-item hdr-pd-logout" type="button"
                                    onclick="this.closest('form').submit()">
                                <i class="fa fa-sign-out hdr-pd-item-icon"></i>
                                Sign out
                            </button>
                        </form>
                    </div>

                    <style>
                        /* ── Profile trigger ──────────────────────────── */
                        .hdr-profile-wrap { position: relative; list-style: none; }

                        .hdr-profile-trigger {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            background: none;
                            border: none;
                            cursor: pointer;
                            padding: 5px 8px;
                            border-radius: 8px;
                            transition: background 0.18s;
                        }
                        .hdr-profile-trigger:hover { background: rgba(36,105,92,0.07); }

                        /* avatar container — clips img to perfect circle regardless of inline styles */
                        .hdr-avatar {
                            border-radius: 50%;
                            overflow: hidden;
                            flex-shrink: 0;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }
                        .hdr-avatar--sm { width: 32px; height: 32px; }
                        .hdr-avatar--md { width: 40px; height: 40px; }
                        .hdr-avatar img {
                            width: 100% !important;
                            height: 100% !important;
                            object-fit: cover;
                            display: block;
                            border-radius: 0 !important;
                        }

                        .hdr-profile-name {
                            font-size: 0.82rem;
                            font-weight: 600;
                            color: #374151;
                            max-width: 130px;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            white-space: nowrap;
                        }

                        .hdr-profile-chevron {
                            font-size: 0.65rem;
                            color: #9ca3af;
                            transition: transform 0.2s;
                        }
                        .hdr-profile-wrap:hover .hdr-profile-chevron { transform: rotate(180deg); }

                        /* ── Dropdown ─────────────────────────────────── */
                        .hdr-profile-dropdown {
                            position: absolute !important;
                            top: calc(100% + 10px) !important;
                            right: 0 !important;
                            left: auto !important;
                            width: 240px;
                            background: #fff;
                            border-radius: 14px;
                            box-shadow: 0 8px 30px rgba(0,0,0,0.12), 0 1px 4px rgba(0,0,0,0.06);
                            padding: 6px;
                            z-index: 9999;
                            border: 1px solid #f1f3f5;
                            animation: hdr-dd-in 0.18s cubic-bezier(.22,.68,0,1.2) both;
                        }

                        @keyframes hdr-dd-in {
                            from { opacity: 0; transform: translateY(-8px) scale(0.97); }
                            to   { opacity: 1; transform: translateY(0)   scale(1); }
                        }

                        /* Identity block */
                        .hdr-pd-identity {
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            padding: 10px 10px 12px;
                        }

                        .hdr-pd-avatar { flex-shrink: 0; }

                        .hdr-pd-info {
                            display: flex;
                            flex-direction: column;
                            overflow: hidden;
                        }

                        .hdr-pd-fullname {
                            font-size: 0.85rem;
                            font-weight: 700;
                            color: #111827;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }

                        .hdr-pd-role {
                            font-size: 0.75rem;
                            color: #6b7280;
                            margin-top: 2px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }

                        /* Divider */
                        .hdr-pd-divider {
                            height: 1px;
                            background: #f3f4f6;
                            margin: 2px 4px;
                        }

                        /* Nav items */
                        .hdr-pd-item {
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            width: 100%;
                            padding: 9px 10px;
                            border-radius: 8px;
                            font-size: 0.84rem;
                            font-weight: 500;
                            color: #374151;
                            text-decoration: none;
                            background: none;
                            border: none;
                            cursor: pointer;
                            transition: background 0.15s, color 0.15s;
                            text-align: left;
                        }

                        .hdr-pd-item:hover {
                            background: #f3f9f8;
                            color: #24695c;
                            text-decoration: none;
                        }

                        .hdr-pd-item-icon {
                            width: 16px;
                            text-align: center;
                            color: #9ca3af;
                            font-size: 0.85rem;
                            transition: color 0.15s;
                        }

                        .hdr-pd-item:hover .hdr-pd-item-icon { color: #24695c; }

                        /* Logout tint */
                        .hdr-pd-logout:hover {
                            background: #fff5f5;
                            color: #dc2626;
                        }
                        .hdr-pd-logout:hover .hdr-pd-item-icon { color: #dc2626; }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Header Ends                              -->
