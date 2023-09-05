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
		<meta name="description" content="Updates and statistics" />
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
		<link href="{{ asset('css/pages/error/error-5.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Page Vendors Styles-->

		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{ asset('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles-->

		<!--begin::Layout Themes(used by all pages)-->

		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="{{ asset('media/logos/NIYON CRM ICON (50 × 50px).png') }}" />
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed header-bottom-enabled subheader-enabled page-loading">

		<!--[html-partial:include:{"file":"layout.html"}]/-->
		<hr>
		
		@yield('auto_dialer')

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
		<!--end::Global Theme Bundle-->
		<!--begin::Page Vendors(used by this page)-->
		<script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
		<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
		<script src="{{ asset('plugins/custom/formvalidation/PasswordStrength.js') }}"></script>
		<!--end::Page Vendors-->
		<!--begin::Page Scripts(used by this page)-->
	
		@stack('scripts')
		
		<!--end::Page Scripts-->
	</body>

	<!--end::Body-->
</html>