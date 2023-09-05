@extends('templates.default')
@section('title', 'Create User')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
								<div class="card card-custom">
									<div class="card-header">
										<div class="card-title">
											<h3 class="card-label">Add One User</h3>
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_users">
												@csrf
												<div class="card-body">

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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Coll Code *</label>
														<div class="col-lg-3 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="coll" id="coll" placeholder="" value="" />
															<span class="form-text text-muted">Coll Code will use for tagging accounts during file uploads.</span>
														</div>
														{{-- <label class="col-1 col-form-label">Generate Coll Code</label>
														<div class="col-1">
															<input data-switch="true" id="generate-coll-code"  type="checkbox"data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
														</div> --}}
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Extension No *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="extension" placeholder="" value="" />
															<span class="form-text text-muted">Extension no. will use for connecting your softphone on PBX.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Username *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="email" placeholder="" value="" />
															<span class="form-text text-muted">This will be the username of the owner.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Password *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="password" class="form-control" name="password" placeholder="" value="" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">First Name *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="firstname" placeholder="" value="" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Last Name *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="lastname" placeholder="" value="" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													@if (Auth::user()->level == 0)
														<div class="form-group row">
															<label class="col-form-label text-right col-lg-3 col-sm-12">Group *</label>
															<div class="col-lg-9 col-md-9 col-sm-12">
																<select class="form-control select2" name="groups">
																	<option></option>
																	@foreach ($groups as $group)
																		<option value="{{$group->id}}">{{$group->name}}</option>
																	@endforeach
																	
																</select>
																<span class="form-text text-muted"></span>
															</div>
														</div>
													@endif

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Access Level *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<select class="form-control" name="level" >
																@if (Auth::user()->level == 0)
																	<option>Select Access Level</option>
																	<option value="0">System Administrator</option>
																	<option value="1">Admin</option>
																	<option value="2">Manager</option>
																@endif
																<option value="3">Collector</option>
															</select>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-users"><i class="fa fa-save"></i> Submit</button>
															<button type="reset" class="btn btn-light-primary font-weight-bold" id="clear-users"><i class="flaticon-cancel"></i> Cancel
															</button>
															@if (Auth::user()->level == 0)
																<a href="{{ route('pages.users.index') }}" class="btn btn-light-primary font-weight-bold" data-dismiss="modal"><i class="flaticon2-back"></i> Back</a>
															@else
																<a href="{{ route('pages.my_team.index') }}" class="btn btn-light-primary font-weight-bold" data-dismiss="modal"><i class="flaticon2-back"></i> Back</a>
															@endif
														</div>
													</div>
												</div>
											</form>
											<!--end::Form-->

									</div>
								</div>
								<!--end::Card-->


				</div>
			</div>
		<!--end::Row-->


	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

$('.select2').select2({
   placeholder: "Select a group"
});

// $('[data-switch=true]').bootstrapSwitch();
// jQuery(document).on('switchChange.bootstrapSwitch', '#generate-coll-code', function(event, state){

// 	      if(state == true){
// 			//$('#save-demo-settings').prop('disabled', false);
// 			$('#coll').prop('disabled', 'disabled');
			
// 		  }else{
// 			$('#coll').prop('disabled', false);
			
// 		  }
	    	
// });

jQuery(document).on('click', '#save-users', function(e){
    e.preventDefault();

    var validation;

    const passwordMeter = document.getElementById('passwordMeter');
    
    const randomNumber = function(min, max) {
         return Math.floor(Math.random() * (max - min + 1) + min);
    };
    

    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_users'),
        {
         fields: {
         	email: {
						validators: {
							notEmpty: {
								message: 'Email is required'
							},
						}
					},
			coll: {
                validators: {
                    notEmpty: {
                      message: 'Coll Code is required'
                    },
                }
            },
            lastname: {
                validators: {
                    notEmpty: {
                      message: 'Last name is required'
                    },
                }
            },
            firstname: {
                validators: {
                    notEmpty: {
                      message: 'First name is required'
                    },
                }
            },
            extension: {
                validators: {
                    notEmpty: {
                          message: 'Extension is required'
                    },
                }
            },

            password: {
                validators: {
                    notEmpty: {
                          message: 'password is required'
                    },
                }
            },
            groups: {
                validators: {
                    notEmpty: {
                          message: 'Group is required'
                    },
                }
            },
            level: {
                validators: {
                    notEmpty: {
                          message: 'Access level is required'
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
		                url: "/users/store",
		                type: "POST",
		                data: $("#new_form_users").serialize(),
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