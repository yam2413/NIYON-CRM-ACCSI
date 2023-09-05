@extends('templates.default')
@section('title', $user->name)
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Profile Overview-->
		<div class="d-flex flex-row">
			<!--begin::Aside-->
				@include('pages.user_profile.aside')
			<!--end::Aside-->
			
			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">
				
				<!--begin::Card-->
								<div class="card card-custom">
									<div class="card-header">
										<div class="card-title">
											<h3 class="card-label">Change Password</h3>
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_password">
												@csrf
												<div class="card-body">
													<input type="hidden" name="id" value="{{$user->id}}">
													<div class="alert alert-custom alert-light-danger d-none" role="alert" id="edit_form_user_msg">
														<div class="alert-icon">
															<i class="flaticon2-information"></i>
														</div>
														<div class="alert-text font-weight-bold">Oh snap! Change a few things up and try submitting again.</div>
														<div class="alert-close">
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span>
																	<i class="ki ki-close"></i>
																</span>
															</button>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Password *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="password" class="form-control" name="password" placeholder="" value="" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-users-update-password"><i class="fa fa-save"></i> Update</button>
														</div>
													</div>
												</div>
											</form>
											<!--end::Form-->

									</div>
								</div>
								<!--end::Card-->

			</div>
			<!--end::Profile Overview-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Entry-->
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

	jQuery(document).on('click', '#save-users-update-password', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_password'),
        {
         fields: {
         	 password: {
                validators: {
                    notEmpty: {
                          message: 'password is required'
                    },
                }
            },
                    
         },

        plugins: { //Learn more: https://formvalidation.io/guide/plugins
            trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
            bootstrap: new FormValidation.plugins.Bootstrap(),
                    // Validate fields when clicking the Submit button
            submitButton: new FormValidation.plugins.SubmitButton(),

            passwordStrength: new FormValidation.plugins.PasswordStrength({
                            field: 'password',
                            message: 'The password is weak',
                            minimalScore: 1,
                            onValidated: function(valid, message, score) {
                            switch (score) {
                                case 0:
                                    passwordMeter.style.width = randomNumber(1, 20) + '%';
                                    passwordMeter.style.backgroundColor = '#ff4136';
                                case 1:
                                    passwordMeter.style.width = randomNumber(20, 40) + '%';
                                    passwordMeter.style.backgroundColor = '#ff4136';
                                    break;
                                case 2:
                                    passwordMeter.style.width = randomNumber(40, 60) + '%';
                                    passwordMeter.style.backgroundColor = '#ff4136';
                                    break;
                                case 3:
                                    passwordMeter.style.width = randomNumber(60, 80) + '%';
                                    passwordMeter.style.backgroundColor = '#ffb700';
                                    break;
                                case 4:
                                    passwordMeter.style.width = '100%';
                                    passwordMeter.style.backgroundColor = '#19a974';
                                    break;
                                default:
                                    break;
                            }
                        },
                      }),
             }
                   
            }
        );

            validation.validate().then(function(status) {

                if (status == 'Valid') {


                     KTApp.blockPage({
                      overlayColor: '#000000',
                      state: 'primary',
                      message: 'Processing...'
                     });


                     $.ajax({
		                url: "/users/update_pass",
		                type: "POST",
		                data: $("#new_form_password").serialize(),
		                headers: {
		                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		                },
		                dataType: "JSON",
		                success: function(data){

		                    KTApp.unblockPage();

		                     if(data.error == 'false'){
		                        
		                        swal.fire({
		                            text: data.msg,
		                            icon: "success",
		                            buttonsStyling: false,
		                            confirmButtonText: "Ok, got it!",
		                            customClass: {
		                                confirmButton: "btn font-weight-bold btn-light-primary"
		                            },onClose: function(e) {
		                            	location.reload();
		                            }
		                            }).then(function() {
		                                 KTUtil.scrollTop();
		                            });
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

                   
                }

            });
	});

});
</script>
@endpush