<!DOCTYPE html>

<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 11 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

	<!--begin::Head-->
	<head>
		<base href="">
		<meta charset="utf-8" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>
		
		@if(View::hasSection('title'))
	        @yield('title') | {{ config('app.name') }}
	    @else
	        {{ config('app.name') }}
	    @endif
		</title>
		<meta name="description" content="NIYON CRM as an call center auto dialer software improves the efficiency of your phone communication by giving you more information and more options for each call you make. Gives you an utterly new experience of effective phone communication right in your CRM and drives your business processes to advanced standards." />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

		<!--end::Fonts-->

		<!--begin::Page Vendors Styles(used by this page)-->
		

		@if(Auth::check())		
			<link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
			<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />				
		@else
			<link href="{{ asset('css/pages/login/login-3.css') }}" rel="stylesheet" type="text/css" />	
		@endif

		<!--end::Page Vendors Styles-->

		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{ asset('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('css/bootstrap-colorpicker.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles-->

		<!--begin::Layout Themes(used by all pages)-->

		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="{{ asset('media/logos/NIYON CRM ICON (50 × 50px).png') }}" />
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed header-bottom-enabled subheader-enabled page-loading">

		<!--[html-partial:include:{"file":"layout.html"}]/-->
		
		@if(Auth::check())	
			@include('templates.base.layout')
			@include('templates.base.quick-user')
		@else
			@yield('content')
		@endif

		<!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-user.html"}]/-->

		<!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-cart.html"}]/-->

		<!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-panel.html"}]/-->

		<!--[html-partial:include:{"file":"partials/_extras/chat.html"}]/-->

		<!--[html-partial:include:{"file":"partials/_extras/scrolltop.html"}]/-->
		<script>
			var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
		</script>

		<!--begin::Global Config(global config for global JS scripts)-->
		<script>
			var KTAppSettings = {
				"breakpoints": {
					"sm": 576,
					"md": 768,
					"lg": 992,
					"xl": 1200,
					"xxl": 1200
				},
				"colors": {
					"theme": {
						"base": {
							"white": "#ffffff",
							"primary": "#6993FF",
							"secondary": "#E5EAEE",
							"success": "#1BC5BD",
							"info": "#8950FC",
							"warning": "#FFA800",
							"danger": "#F64E60",
							"light": "#F3F6F9",
							"dark": "#212121"
						},
						"light": {
							"white": "#ffffff",
							"primary": "#E1E9FF",
							"secondary": "#ECF0F3",
							"success": "#C9F7F5",
							"info": "#EEE5FF",
							"warning": "#FFF4DE",
							"danger": "#FFE2E5",
							"light": "#F3F6F9",
							"dark": "#D6D6E0"
						},
						"inverse": {
							"white": "#ffffff",
							"primary": "#ffffff",
							"secondary": "#212121",
							"success": "#ffffff",
							"info": "#ffffff",
							"warning": "#ffffff",
							"danger": "#ffffff",
							"light": "#464E5F",
							"dark": "#ffffff"
						}
					},
					"gray": {
						"gray-100": "#F3F6F9",
						"gray-200": "#ECF0F3",
						"gray-300": "#E5EAEE",
						"gray-400": "#D6D6E0",
						"gray-500": "#B5B5C3",
						"gray-600": "#80808F",
						"gray-700": "#464E5F",
						"gray-800": "#1B283F",
						"gray-900": "#212121"
					}
				},
				"font-family": "Poppins"
			};
		</script>

		<!--end::Global Config-->

		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="{{ asset('plugins/custom/formvalidation/zxcvbn.js') }}"></script>
		<script src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
		<script src="{{ asset('js/scripts.bundle.js') }}"></script>
		<script src="{{ asset('js/bootstrap-colorpicker.min.js') }}"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Vendors(used by this page)-->
		<script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
		<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
		<script src="{{ asset('plugins/custom/formvalidation/PasswordStrength.js') }}"></script>
		<!--end::Page Vendors-->
		<!--begin::Page Scripts(used by this page)-->
		@if(Auth::check())		
			{{-- <script src="{{ asset('js/pages/widgets.js') }}"></script> --}}

			@if(env('DEMO_STATUS') == 'true')
				@php
					$date_demo = new DateTime(env('DEMO_DATE'));
					$now_demo = new DateTime();

				@endphp
				@if($date_demo < $now_demo)
								
					<script type="text/javascript">
						swal.fire({
		                    text: '{{env('DEMO_MESSAGE')}}',
		                    icon: "warning",
		                    buttonsStyling: false,
		                    confirmButtonText: "Ok, got it!",
		                    customClass: {
		                       confirmButton: "btn font-weight-bold btn-light-primary"
		                    }
		                    }).then(function() {
		                        KTUtil.scrollTop();
		                    });
					</script>
				@endif
			@endif

			<script type="text/javascript">
				KTUtil.ready(function() {

					@if(Auth::user()->dialer_loggin == 1)

						jQuery.ajax({
					          url: "/agent_dialer/check_campaign_login",
					          type: "POST",
					          data: 'id={{Auth::user()->id}}',
					          headers: {
						             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						            },
					          dataType: "json",
					          success: function(data) {
					          	 KTApp.unblockPage();

					          	 if(data.loggin == 1){
					          	 	 var link = '{{ route('pages.agent.auto_dialer', ['file_id' => ':file_id']) }}';
								     link = link.replace(':file_id', data.file_id);
						        	 window.location = link; 
					          	 }
					          	 

					          },
					          error: function(data){


					          }
					    });
						
					@endif
					jQuery(document).on('click', '#btn_agent_campaign', function(e){
						var file_id = $("#select_agent_campaign").val();
						if(file_id == ''){
							swal.fire({
		                    text: 'Please select campaign to login',
		                    icon: "warning",
		                    buttonsStyling: false,
		                    confirmButtonText: "Ok, got it!",
		                    customClass: {
		                       confirmButton: "btn font-weight-bold btn-light-primary"
		                    }
		                    }).then(function() {
		                        KTUtil.scrollTop();
		                    });
							return;
						}

						KTApp.blockPage({
				           overlayColor: '#000000',
				           state: 'primary',
				           message: 'Processing...'
				        });  

						jQuery.ajax({
					          url: "/agent_dialer/logged_status",
					          type: "POST",
					          data: 'id={{Auth::user()->id}}&status=1&file_id='+file_id,
					          headers: {
						             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						            },
					          dataType: "json",
					          success: function(data) {
					          	 KTApp.unblockPage();

					          	 if(data.msg == 'ok'){
					          	 	 var link = '{{ route('pages.agent.auto_dialer', ['file_id' => ':file_id']) }}';
								     link = link.replace(':file_id', file_id);
						        	 window.location = link; 
					          	 }
					          	 

					          },
					          error: function(data){


					          }
					    });
					     
					    	
					});
				});
			</script>

														
		@else
			<!-- <script src="{{ asset('js/pages/custom/login/login-general.js') }}"></script> -->		
		@endif
		
		@stack('scripts')
		
		<!--end::Page Scripts-->
	</body>

	<!--end::Body-->
</html>