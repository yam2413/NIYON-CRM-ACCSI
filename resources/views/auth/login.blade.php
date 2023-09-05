<!--begin::Main-->
<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-3 wizard d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Aside-->
				<div class="login-aside d-flex flex-column flex-row-auto">
					<!--begin::Aside Top-->
					<div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
						<!--begin::Aside header-->
						<a href="#" class="login-logo text-center pt-lg-25 pb-10">
							<img src="{{ asset('media/logos/NIYON CRM LOGO.png') }}" class="max-h-70px" alt="" />
						</a>
						<!--end::Aside header-->
						<!--begin::Aside Title-->
						<h3 class="font-weight-bolder text-center font-size-h4 text-dark-50 line-height-xl">Manage Customer Relations, Sales & Marketing Outbound Campaigns From One Place.</h3>
						<!--end::Aside Title-->
					</div>
					<!--end::Aside Top-->
					<!--begin::Aside Bottom-->
					<div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-x-center" style="background-position-y: calc(100% + 5rem); background-image: url({{ asset('media/svg/illustrations/login-visual-5.svg') }})"></div>
					<!--end::Aside Bottom-->
				</div>
				<!--begin::Aside-->
				<!--begin::Content-->
				<div class="bg-dark login-content flex-row-fluid d-flex flex-column p-10">
					<!--begin::Wrapper-->
					<div class="d-flex flex-row-fluid flex-center">
						<!--begin::Signin-->
						<div class="login-form">
							<!--begin::Form-->
							<form class="form" id="kt_login_singin_form" action="">
								@csrf
								<!--begin::Title-->
								<div class="pb-5 pb-lg-15">
									<h3 class="font-weight-bolder text-white font-size-h2 font-size-h1-lg">Sign In</h3>
								</div>
								<!--begin::Title-->
								<!--begin::Form group-->
								<div class="form-group">
									<label class="font-size-h6 font-weight-bolder text-white">Email</label>
									<input class="form-control h-auto py-7 px-6 rounded-lg border-0" type="email" name="email" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group">
									<div class="d-flex justify-content-between mt-n5">
										<label class="font-size-h6 font-weight-bolder text-white pt-5">Password</label>
										{{-- <a href="custom/pages/login/login-3/forgot.html" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5">Forgot Password ?</a> --}}
									</div>
									<input class="form-control h-auto py-7 px-6 rounded-lg border-0" type="password" name="password" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Action-->
								<div class="pb-lg-0 pb-5">
									<button type="submit" id="kt_login_singin_form_submit_button" class="btn btn-secondary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign In</button>
								</div>
								<!--end::Action-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Signin-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Login-->
		</div>
<!--end::Main-->




@push('scripts')
<script type="text/javascript">
"use strict";

// Class Definition
var KTLogin = function() {
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

	var _handleFormSignin = function() {
		var form = KTUtil.getById('kt_login_singin_form');
		var formSubmitUrl = KTUtil.attr(form, 'action');
		var formSubmitButton = KTUtil.getById('kt_login_singin_form_submit_button');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation(
		        form,
		        {
		            fields: {
						email: {
							validators: {
								notEmpty: {
									message: 'Email is required'
								}
							}
						},
						password: {
							validators: {
								notEmpty: {
									message: 'Password is required'
								}
							}
						}
		            },
		            plugins: {
						trigger: new FormValidation.plugins.Trigger(),
						submitButton: new FormValidation.plugins.SubmitButton(),
	            		//defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
						bootstrap: new FormValidation.plugins.Bootstrap({
						//	eleInvalidClass: '', // Repace with uncomment to hide bootstrap validation icons
						//	eleValidClass: '',   // Repace with uncomment to hide bootstrap validation icons
						})
		            }
		        }
		    )
		    .on('core.form.valid', function() {
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

				// Simulate Ajax request
				setTimeout(function() {
					KTUtil.btnRelease(formSubmitButton);
				}, 2000);

						var data = $("#kt_login_singin_form").serialize();
		                var formx = $("#kt_login_singin_form")[0]; 
		                var formDatax = new FormData(formx);

		        		$.ajax({
		                    url: "user_login",
		                    type: "POST",
		                    data: formDatax,
		                    contentType: false,
		                    processData:false,
		                    dataType: "JSON",
		                    success: function(data){

		                        if(data.error == 'false'){
		                        	setTimeout(function(){
			                          window.location = "{{ route('home') }}";
			                       }, 100);
		                        }else{
		                        	swal.fire({
						                text: data.msg,
						                icon: "error",
						                buttonsStyling: false,
						                confirmButtonText: "Ok, got it!",
				                        customClass: {
				    						confirmButton: "btn font-weight-bold btn-light-primary"
				    					}
						            }).then(function() {
										KTUtil.scrollTop();
									});
		                        }
		                        
		                    }

		                });
		    })
			.on('core.form.invalid', function() {
				Swal.fire({
					text: "Sorry, looks like there are some errors detected, please try again.",
					icon: "error",
					buttonsStyling: false,
					confirmButtonText: "Ok, got it!",
					customClass: {
						confirmButton: "btn font-weight-bold btn-light-primary"
					}
				}).then(function() {
					KTUtil.scrollTop();
				});
		    });
    }


    // Public Functions
    return {
        init: function() {
            _handleFormSignin();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();
});
</script>
@endpush