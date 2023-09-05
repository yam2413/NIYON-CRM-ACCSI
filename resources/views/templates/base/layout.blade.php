
<!--begin::Main-->

		<!--[html-partial:include:{"file":"partials/_header-mobile.html"}]/-->
		@include('templates.base.header-mobile')
		<div class="d-flex flex-column flex-root">

			<!--begin::Page-->
			<div class="d-flex flex-row flex-column-fluid page">

				<!--begin::Wrapper-->
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

					<!--[html-partial:include:{"file":"partials/_header.html"}]/-->
					

					@switch(Auth::user()->level)
						@case(0)
							@include('templates.base.header')
							@break
						@case(1)
							@include('templates.base.level_header.admin_header')
							@break
						
						@case(2)
							@include('templates.base.level_header.manager_header')
							@break
						
						@case(3)
							@include('templates.base.level_header.collector_header')
							@break

						@default
							
					@endswitch
					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

						<!--[html-partial:include:{"file":"partials/_subheader/subheader-v1.html"}]/-->
						@include('templates.base.subheader')
						<!--Content area here-->
						@yield('content')
						
					</div>

					<!--end::Content-->

					<!--[html-partial:include:{"file":"partials/_footer.html"}]/-->
					@include('templates.base.footer')
				</div>

				<!--end::Wrapper-->
			</div>

			<!--end::Page-->
		</div>

		<!--end::Main-->