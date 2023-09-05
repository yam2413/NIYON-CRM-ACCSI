
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

                    @switch(Auth::user()->level)
                        @case(1)
                                <!--begin::Item-->
                                <li class="nav-item">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('dashboard') || Request::is('/') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_1" role="tab">Home</a>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item mr-3">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('leads') || Request::is('leads/*') || Request::is('reassign') || Request::is('reassign/*') || Request::is('auto_dialer') || Request::is('auto_dialer/*') || Request::is('pullout') || Request::is('pullout/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_2" role="tab">Leads</a>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                @if(env('DEMO_STATUS') == 'true')
                                        @php
                                            $date_demo = new DateTime(env('DEMO_DATE'));
                                            $now_demo = new DateTime();
                                        @endphp

                                        @if($date_demo < $now_demo)
                                            
                                        @else
                                            <li class="nav-item mr-3">
                                                <a href="#" class="nav-link py-4 px-6 {{ Request::is('upload_paids') || Request::is('upload_paids/*') || Request::is('file_uploads') || Request::is('file_uploads/*') || Request::is('update_leads') || Request::is('update_leads/*')  ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_6" role="tab">File Upload</a>
                                            </li>
                                        @endif
                                @else
                                    <li class="nav-item mr-3">
                                        <a href="#" class="nav-link py-4 px-6 {{ Request::is('upload_paids') || Request::is('upload_paids/*') || Request::is('file_uploads') || Request::is('file_uploads/*') || Request::is('update_leads') || Request::is('update_leads/*')  ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_6" role="tab">File Upload</a>
                                    </li>		
                                @endif
                                
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item mr-3">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('sms_template') || Request::is('sms_template/*') || Request::is('users') || Request::is('groups') || Request::is('emails') || Request::is('users/*') || Request::is('groups/*') || Request::is('emails/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_3" role="tab">Manage</a>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item mr-3">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('email_logs') || Request::is('email_logs/*') || Request::is('sms_logs') || Request::is('sms_logs/*') || Request::is('leads_logs') || Request::is('leads_logs/*') || Request::is('system_logs') || Request::is('system_logs/*') || Request::is('call_status') || Request::is('call_status/*') || Request::is('call_logs') || Request::is('call_logs/*') || Request::is('summary_calls') || Request::is('summary_calls/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_4" role="tab">Reports</a>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item mr-3">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('sms') || Request::is('sms/*') || Request::is('emails_settings') || Request::is('emails_settings/*') || Request::is('asterisk') || Request::is('asterisk/*') || Request::is('dialer') || Request::is('dialer/*') || Request::is('root') || Request::is('root/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_5" role="tab">Settings</a>
                                </li>
                            @break

                            @case(2)
                                <!--begin::Item-->
                                <li class="nav-item">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('dashboard') || Request::is('/') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_1" role="tab">Home</a>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item mr-3">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('leads') || Request::is('leads/*') || Request::is('reassign') || Request::is('reassign/*') || Request::is('auto_dialer') || Request::is('auto_dialer/*') || Request::is('pullout') || Request::is('pullout/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_2" role="tab">Leads</a>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                @if(env('DEMO_STATUS') == 'true')
                                        @php
                                            $date_demo = new DateTime(env('DEMO_DATE'));
                                            $now_demo = new DateTime();
                                        @endphp

                                        @if($date_demo < $now_demo)
                                            
                                        @else
                                            <li class="nav-item mr-3">
                                                <a href="#" class="nav-link py-4 px-6 {{ Request::is('upload_paids') || Request::is('upload_paids/*') || Request::is('file_uploads') || Request::is('file_uploads/*') || Request::is('update_leads') || Request::is('update_leads/*')  ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_6" role="tab">File Upload</a>
                                            </li>
                                        @endif
                                @else
                                    <li class="nav-item mr-3">
                                        <a href="#" class="nav-link py-4 px-6 {{ Request::is('upload_paids') || Request::is('upload_paids/*') || Request::is('file_uploads') || Request::is('file_uploads/*') || Request::is('update_leads') || Request::is('update_leads/*')  ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_6" role="tab">File Upload</a>
                                    </li>		
                                @endif
                                
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item mr-3">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('sms_template') || Request::is('sms_template/*') || Request::is('users') || Request::is('groups') || Request::is('emails') || Request::is('users/*') || Request::is('groups/*') || Request::is('emails/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_3" role="tab">Manage</a>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item mr-3">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('email_logs') || Request::is('email_logs/*') || Request::is('sms_logs') || Request::is('sms_logs/*') || Request::is('leads_logs') || Request::is('leads_logs/*') || Request::is('system_logs') || Request::is('system_logs/*') || Request::is('call_status') || Request::is('call_status/*') || Request::is('call_logs') || Request::is('call_logs/*') || Request::is('summary_calls') || Request::is('summary_calls/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_4" role="tab">Reports</a>
                                </li>
                                <!--end::Item-->

                            @break

                            @case(3)
                                <!--begin::Item-->
                                <li class="nav-item">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('dashboard') || Request::is('/') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_1" role="tab">Home</a>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="nav-item mr-3">
                                    <a href="#" class="nav-link py-4 px-6 {{ Request::is('leads') || Request::is('leads/*') || Request::is('reassign') || Request::is('reassign/*') || Request::is('auto_dialer') || Request::is('auto_dialer/*') ? 'active' : '' }}" data-toggle="tab" data-target="#kt_header_tab_2" role="tab">Leads</a>
                                </li>
                                <!--end::Item-->

                            @break
                    
                       
                    @endswitch
                    

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
                                <i class="fas fa-phone-volume"></i> Auto Dilaer
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

                    @switch(Auth::user()->level)
                        @case(1)
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

                            <li class="nav-item mr-2">
                                <a href="#" class="nav-link btn btn-clean" data-toggle="tab" data-target="#kt_header_tab_3" role="tab">Manage</a>
                            </li>

                            <!--end::Item-->

                            <!--begin::Item-->
                            <li class="nav-item mr-2">
                                <a href="#" class="nav-link btn btn-clean" data-toggle="tab" data-target="#kt_header_tab_4" role="tab">Reports</a>
                            </li>

                            <!--end::Item-->

                            <!--begin::Item-->
                            <li class="nav-item mr-2">
                                <a href="#" class="nav-link btn btn-clean" data-toggle="tab" data-target="#kt_header_tab_5" role="tab">Settings</a>
                            </li>

                            <!--end::Item-->
                            @break

                        @case(2)
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

                            <!--end::Item-->

                            <!--begin::Item-->
                            <li class="nav-item mr-2">
                                <a href="#" class="nav-link btn btn-clean" data-toggle="tab" data-target="#kt_header_tab_4" role="tab">Reports</a>
                            </li>

                            <!--end::Item-->

                            <!--end::Item-->
                            @break


                        @case(3)
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
                            @break
                    
                    @endswitch
                    

                    

                </ul>

                <!--begin::Tab Navs-->

                <!--begin::Tab Content-->
                <div class="tab-content">

                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 {{ Request::is('dashboard') || Request::is('/') ? 'show active' : '' }}" id="kt_header_tab_1">

                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">

                            <!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item menu-item-{{ Request::is('dashboard') || Request::is('/') ? 'active' : '' }}" aria-haspopup="true">
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

                            @if(Auth::user()->level != '3')
                                @if(env('FEATURE_AUTO_DIALER') == 'true')
                                <li class="menu-item {{ Request::is('auto_dialer') || Request::is('auto_dialer/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.auto_dialer.index') }}" class="menu-link">
                                        <span class="menu-text">Auto Dialer</span>
                                    </a>
                                </li>
                                @endif
                                

                                <li class="menu-item {{ Request::is('reassign') || Request::is('reassign/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.leads.reassign.index') }}" class="menu-link">
                                        <span class="menu-text">Reassign</span>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::is('pullout') || Request::is('pullout/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.pullout.index') }}" class="menu-link">
                                        <span class="menu-text">Pullout Accounts</span>
                                    </a>
                                </li>
                            @endif

                            </ul>

                            <!--end::Nav-->
                        </div>

                        <!--end::Menu-->
                    </div>

                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 {{ Request::is('upload_paids') || Request::is('upload_paids/*') || Request::is('file_uploads') || Request::is('file_uploads/*') || Request::is('update_leads') || Request::is('update_leads/*') ? 'show active' : '' }}" id="kt_header_tab_6">

                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">

                            <!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item {{ Request::is('file_uploads') || Request::is('file_uploads/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.file_uploads.index') }}" class="menu-link">
                                        <span class="menu-text">New Leads</span>
                                    </a>
                                </li>

                                {{-- <li class="menu-item {{ Request::is('update_leads') || Request::is('update_leads/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.file_uploads.update_leads.index') }}" class="menu-link">
                                        <span class="menu-text">Update Leads</span>
                                    </a>
                                </li> --}}

                                <li class="menu-item {{ Request::is('upload_paids') || Request::is('upload_paids/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.file_uploads.paids.index') }}" class="menu-link">
                                        <span class="menu-text">Paid</span>
                                    </a>
                                </li>

                                {{-- <li class="menu-item" aria-haspopup="true">
                                    <a href="#" class="menu-link">
                                        <span class="menu-text">Borrower Details</span>
                                    </a>
                                </li> --}}


                            </ul>

                            <!--end::Nav-->
                        </div>

                        <!--end::Menu-->
                    </div>

                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 {{ Request::is('sms_template') || Request::is('sms_template/*') || Request::is('users') || Request::is('users/*') || Request::is('groups') || Request::is('groups/*') || Request::is('emails') || Request::is('emails/*')  ? 'show active' : '' }}" id="kt_header_tab_3">

                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">

                            <!--begin::Nav-->
                        
                        
                            <ul class="menu-nav">
                                
                                @switch(Auth::user()->level)
                            @case(1)
                                <li class="menu-item {{ Request::is('users') || Request::is('users/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.users.index') }}" class="menu-link">
                                        <span class="menu-text">Users</span>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::is('groups') || Request::is('groups/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.groups.index') }}" class="menu-link">
                                        <span class="menu-text">Groups</span>
                                    </a>
                                </li>

                                @if(env('FEATURE_EMAIL') == 'true')
                                <li class="menu-item {{ Request::is('emails') || Request::is('emails/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.emails.index') }}" class="menu-link">
                                        <span class="menu-text">Email Templates</span>
                                    </a>
                                </li>
                                @endif

                                @if(env('FEATURE_SMS') == 'true')
                                <li class="menu-item {{ Request::is('sms_template') || Request::is('sms_template/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.sms.index') }}" class="menu-link">
                                        <span class="menu-text">SMS Templates</span>
                                    </a>
                                </li>
                                @endif
                                @break
                        
                            @case(2)
                                <li class="menu-item {{ Request::is('my_team') || Request::is('my_team/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.my_team.index') }}" class="menu-link">
                                        <span class="menu-text">My Team</span>
                                    </a>
                                </li>
                            @break
                        @endswitch

                                


                            </ul>

                            <!--end::Nav-->
                        </div>

                        <!--end::Menu-->
                    </div>


                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 {{ Request::is('email_logs') || Request::is('email_logs/*') || Request::is('sms_logs') || Request::is('sms_logs/*') || Request::is('leads_logs') || Request::is('leads_logs/*') || Request::is('system_logs') || Request::is('system_logs/*') || Request::is('call_status') || Request::is('call_status/*') || Request::is('call_logs') || Request::is('call_logs/*') || Request::is('summary_calls') || Request::is('summary_calls/*') ? 'show active' : '' }}" id="kt_header_tab_4">

                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">

                            <!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item {{ Request::is('call_logs') || Request::is('call_logs/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.reports.call_logs.index') }}" class="menu-link">
                                        <span class="menu-text">Call Logs</span>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::is('call_status') || Request::is('call_status/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.reports.call_status.index') }}" class="menu-link">
                                        <span class="menu-text">Call Status</span>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::is('summary_calls') || Request::is('summary_calls/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.reports.summary_calls.index') }}" class="menu-link">
                                        <span class="menu-text">Summary of Calls</span>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::is('system_logs') || Request::is('system_logs/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.reports.system_logs.index') }}" class="menu-link">
                                        <span class="menu-text">System Logs</span>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::is('leads_logs') || Request::is('leads_logs/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.reports.leads_logs.index') }}" class="menu-link">
                                        <span class="menu-text">Leads Logs</span>
                                    </a>
                                </li>

                                @if(env('FEATURE_SMS') == 'true')
                                <li class="menu-item {{ Request::is('sms_logs') || Request::is('sms_logs/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.reports.sms.index') }}" class="menu-link">
                                        <span class="menu-text">SMS</span>
                                    </a>
                                </li>
                                @endif

                                @if(env('FEATURE_EMAIL') == 'true')
                                <li class="menu-item {{ Request::is('email_logs') || Request::is('email_logs/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.reports.email.index') }}" class="menu-link">
                                        <span class="menu-text">Email</span>
                                    </a>
                                </li>
                                @endif

                                {{-- <li class="menu-item" aria-haspopup="true">
                                    <a href="#" class="menu-link">
                                        <span class="menu-text">Users Time Spent</span>
                                    </a>
                                </li> --}}

                                


                            </ul>

                            <!--end::Nav-->
                        </div>

                        <!--end::Menu-->
                    </div>

                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 {{ Request::is('sms') || Request::is('sms/*') || Request::is('emails_settings') || Request::is('emails_settings/*') || Request::is('asterisk') || Request::is('asterisk/*') || Request::is('dialer') || Request::is('dialer/*') || Request::is('root') || Request::is('root/*') ? 'show active' : '' }}" id="kt_header_tab_5">

                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">

                            <!--begin::Nav-->
                            <ul class="menu-nav">

                                @if(env('FEATURE_SMS') == 'true')
                                <li class="menu-item {{ Request::is('sms') || Request::is('sms/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.settings.sms.index') }}" class="menu-link">
                                        <span class="menu-text">SMS Config</span>
                                    </a>
                                </li>
                                @endif

                                @if(env('FEATURE_EMAIL') == 'true')
                                <li class="menu-item {{ Request::is('emails_settings') || Request::is('emails_settings/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.settings.emails.index') }}" class="menu-link">
                                        <span class="menu-text">Email SMTP</span>
                                    </a>
                                </li>
                                @endif

                                <li class="menu-item {{ Request::is('asterisk') || Request::is('asterisk/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.settings.asterisk.index') }}" class="menu-link">
                                        <span class="menu-text">Asterisk Config</span>
                                    </a>
                                </li>

                                @if(env('FEATURE_AUTO_DIALER') == 'true')
                                <li class="menu-item {{ Request::is('dialer') || Request::is('dialer/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.settings.dialer.index') }}" class="menu-link">
                                        <span class="menu-text">Auto Dialer Settings</span>
                                    </a>
                                </li>
                                @endif

                                @if(Auth::user()->email == 'root')
                                <li class="menu-item {{ Request::is('root') || Request::is('root/*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('pages.settings.root.index') }}" class="menu-link">
                                        <span class="menu-text">Root Settings</span>
                                    </a>
                                </li>
                                @endif


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