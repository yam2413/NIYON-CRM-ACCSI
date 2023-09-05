
<!--begin::Header-->
<div id="kt_header" class="header flex-column header-fixed">

    <!--begin::Top-->
    <div class="header-top bg-dark" style="{{AppHelper::instance()->getColorPalettes(Auth::user()->group)}}">

        <!--begin::Container-->
        <div class="container">

            <!--begin::Left-->
            <div class="d-none d-lg-flex align-items-center mr-3">

                <!--begin::Logo-->
                <a href="{{ route('pages.dashboard.index') }}" class="mr-20">
                    <img alt="Logo" src="{{ asset('media/logos/NIYON CRM LOGO.png') }}" class="max-h-70px" />
                </a>

                <!--end::Logo-->

                <!--begin::Tab Navs(for desktop mode)-->
                <ul class="header-tabs nav align-self-end font-size-lg" role="tablist">

                   <!--begin::Item-->
                   <li class="nav-item">
                        <a href="#" class="nav-link py-4 px-6 {{ Request::is('dashboard') || Request::is('dashboard/*') || Request::is('/') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_1" role="tab">Home</a>
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="nav-item mr-3">
                        <a href="#" class="nav-link py-4 px-6 {{ Request::is('leads') || Request::is('leads/*') || Request::is('reassign') || Request::is('reassign/*') || Request::is('auto_dialer') || Request::is('auto_dialer/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_2" role="tab">Leads</a>
                    </li>
                    <!--end::Item-->

                    
                </ul>

                <!--begin::Tab Navs-->
            </div>

            <!--end::Left-->

            <!--begin::Topbar-->
            <div class="topbar bg-dark" style="{{AppHelper::instance()->getColorPalettes(Auth::user()->group)}}">

                <!--begin::Search-->
                <div class="dropdown">

                    <!--begin::Toggle-->
                    @if(env('FEATURE_AUTO_DIALER') == 'true')
                        @if(Auth::user()->level == '3')
                        <div class="topbar-item" data-offset="10px,0px">
                            <a href="#" class="btn btn-danger btn-sm mr-3" data-toggle="modal" data-target="#modal_auto_dialer_agents">
                                <i class="fas fa-phone-volume"></i> Auto Dialer
                            </a>
                        </div>
                        @endif
                    @endif
                    

                    <!--begin::Toggle-->
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                        @if(env('DEMO_STATUS') == 'true')
                            @php
                                $date_demo = new DateTime(env('DEMO_DATE'));
                                $now_demo = new DateTime();

                            @endphp
                            @if($date_demo < $now_demo)
                                <a href="#" class="btn btn-danger btn-sm mr-3" data-toggle="tooltip" data-theme="dark" title="{{env('DEMO_MESSAGE')}}">Trial Expired</a>
                            @else
                                <a href="#" class="btn btn-success btn-sm mr-3" data-toggle="tooltip" data-theme="dark" title="This platform is on trial version until {{env('DEMO_DATE')}} ">Trial</a>
                            @endif
                            
                        @endif
                    </div>

                    <!--end::Toggle-->

                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">

                        <!--[html-partial:include:{"file":"partials/_extras/dropdown/search-dropdown.html"}]/-->
                    </div>

                    <!--end::Dropdown-->
                </div>

                <!--end::Search-->

                <!--begin::Notifications-->
                <div class="dropdown">

                    <!--begin::Toggle-->
                    {{-- <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                        <div class="btn btn-icon btn-hover-transparent-white btn-dropdown btn-lg mr-1 pulse pulse-white">
                            <span class="svg-icon svg-icon-xl">

                                <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                                        <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                                    </g>
                                </svg>

                                <!--end::Svg Icon-->
                            </span>
                            <span class="pulse-ring"></span>
                        </div>
                    </div> --}}

                    <!--end::Toggle-->

                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                        <form>

                            <!--[html-partial:include:{"file":"partials/_extras/dropdown/notifications.html"}]/-->
                            @include('templates.base.notifications')
                        </form>
                    </div>

                    <!--end::Dropdown-->
                </div>

                <!--end::Notifications-->

                <!--begin::User-->
                <div class="topbar-item">
                    <div class="btn btn-icon btn-hover-transparent-white w-sm-auto d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                        <div class="d-flex flex-column text-right pr-sm-3">
                            <span class="text-white opacity-50 font-weight-bold font-size-sm d-none d-sm-inline">{{ Auth::user()->name }}</span>
                            <span class="text-white font-weight-bolder font-size-sm d-none d-sm-inline">{{ Auth::user()->usersRole(Auth::user()->level) }}</span>
                        </div>
                        <span class="symbol symbol-35">
                            <span class="symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30">{{ substr(strtoupper(Auth::user()->name), 0, 1) }}</span>
                        </span>
                    </div>
                </div>

                <!--end::User-->
            </div>

            <!--end::Topbar-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Top-->

    <!--begin::Bottom-->
    <div class="header-bottom">

        <!--begin::Container-->
        <div class="container">

            <!--begin::Header Menu Wrapper-->
            <div class="header-navs header-navs-left" id="kt_header_navs">

                <!--begin::Tab Navs(for tablet and mobile modes)-->
                <ul class="header-tabs p-5 p-lg-0 d-flex d-lg-none nav nav-bold nav-tabs" role="tablist">

                    <!--begin::Item-->
                    <li class="nav-item mr-2">
                        <a href="#" class="nav-link btn btn-clean active" data-toggle="tab" data-target="#kt_header_tab_1" role="tab">Home</a>
                    </li>

                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="nav-item mr-2">
                        <a href="#" class="nav-link btn btn-clean" data-toggle="tab" data-target="#kt_header_tab_2" role="tab">Leads</a>
                    </li>
                    <!--end::Item-->
                    

                    

                </ul>

                <!--begin::Tab Navs-->

                <!--begin::Tab Content-->
                <div class="tab-content">

                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 {{ Request::is('dashboard') || Request::is('dashboard/*') || Request::is('/') ? 'show active' : '' }}" id="kt_header_tab_1">

                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">

                            <!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item menu-item-{{ Request::is('dashboard') || Request::is('dashboard/*') || Request::is('/') ? 'active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.dashboard.index') }}" class="menu-link">
                                        <span class="menu-text">Dashboard</span>
                                    </a>
                                </li>


                            </ul>

                            <!--end::Nav-->
                        </div>

                        <!--end::Menu-->
                    </div>

                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 {{ Request::is('leads') || Request::is('leads/*') || Request::is('reassign') || Request::is('reassign/*') || Request::is('auto_dialer') || Request::is('auto_dialer/*') || Request::is('pullout') || Request::is('pullout/*') ? 'show active' : '' }}" id="kt_header_tab_2">

                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">

                            <!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item {{ Request::is('leads') || Request::is('leads/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.leads.index') }}" class="menu-link">
                                        <span class="menu-text">Leads</span>
                                    </a>
                                </li>

                            </ul>

                            <!--end::Nav-->
                        </div>

                        <!--end::Menu-->
                    </div>

                   

                

                </div>

                <!--end::Tab Content-->
            </div>

            <!--end::Header Menu Wrapper-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Bottom-->
</div>

<!--end::Header-->

<!-- Modal-->
<div class="modal fade" id="modal_auto_dialer_agents" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Campaign</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <select id="select_agent_campaign" class="form-control">
                    <option value="">Select Campaign</option>
                    @php
                        $select_my_campaigns = \App\Http\Controllers\AutoDialerController::select_my_campaign();
                    @endphp

                    @foreach ($select_my_campaigns as $select_my_campaign)
                        <option value="{{$select_my_campaign->file_id}}">{{$select_my_campaign->campaign_name}}</option>
                    @endforeach

                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                @if(env('DEMO_STATUS') == 'true')
                    @php
                        $date_demo = new DateTime(env('DEMO_DATE'));
                        $now_demo = new DateTime();

                    @endphp
                    <button type="button" id="btn_agent_campaign" class="btn btn-primary font-weight-bold" @if($date_demo < $now_demo) disabled="disabled" @else @endif>Select Campaign</button>
                @else
                    <button type="button" id="btn_agent_campaign" class="btn btn-primary font-weight-bold">Select Campaign</button>
                @endif
                
            </div>
        </div>
    </div>
</div>