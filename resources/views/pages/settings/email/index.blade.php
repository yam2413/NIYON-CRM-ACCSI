@extends('templates.default')
@section('title', 'SMTP Configuration')
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
											SMTP Configuration
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_smtp">
												@csrf
												<div class="card-body">

													<div class="alert alert-custom alert-light-danger d-none" role="alert" id="edit_form_asterisk_msg">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Mailer *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="mailer" placeholder="" value="{{env('MAIL_MAILER') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Mail Host *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="email" class="form-control" name="host" placeholder="" value="{{env('MAIL_HOST') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Mail Port *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="port" placeholder="" value="{{env('MAIL_PORT') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Mail Username *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="username" placeholder="" value="{{env('MAIL_USERNAME') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Mail Password *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="password" class="form-control" name="password" placeholder="" value="{{env('MAIL_PASSWORD') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Mail Encryption *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="encryption" placeholder="" value="{{env('MAIL_ENCRYPTION') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Mail From Address *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="from_address" placeholder="" value="{{env('MAIL_FROM_ADDRESS') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Mail Name *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="mail_email" placeholder="" value="{{env('MAIL_FROM_NAME') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													

													


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-smtp"><i class="fa fa-save"></i> Save</button>
															<button type="button" class="btn btn-light-primary font-weight-bold" data-toggle="modal" data-target="#modal_test_email"><i class="fab fa-telegram-plane"></i> Test Email
															</button>
															<button type="reset" class="btn btn-light-primary font-weight-bold" id="clear-smtp"><i class="flaticon-cancel"></i> Cancel
															</button>
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

<!-- Modal-->
<div class="modal fade" id="modal_test_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="form" id="new_form_send_test_email">
        	<div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Test Email</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	            	<div class="mb-2">
	            		<div class="form-group row">
	            			<div class="col-lg-12">
                            	<label>Email Address</label>
                            	<input type="text" class="form-control" name="email" placeholder="input the email">
                             	<span class="form-text text-muted"></span>
                           </div>
	            		</div>
	            	</div>
	                
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="send_test_email" class="btn btn-primary font-weight-bold">Send</button>
	            </div>
	        </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

$('.select2').select2({
   placeholder: "Select a group"
});


jQuery(document).on('click', '#send_test_email', function(e){
    e.preventDefault();

    var validation;
    
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_send_test_email'),
        {
         fields: {
            email: {
                validators: {
                    notEmpty: {
                      message: 'Email Address is required'
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
		                url: "/emails_settings/send_email",
		                type: "POST",
		                data: $("#new_form_send_test_email").serialize(),
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


jQuery(document).on('click', '#save-smtp', function(e){
    e.preventDefault();

    var validation;
    
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_smtp'),
        {
         fields: {
            mailer: {
                validators: {
                    notEmpty: {
                      message: 'Mail Mailer type is required'
                    },
                }
            },
            host: {
                validators: {
                    notEmpty: {
                      message: 'Mail Host is required'
                    },
                }
            },
            port: {
                validators: {
                    notEmpty: {
                          message: 'Mail Port is required'
                    },
                }
            },

            username: {
                validators: {
                    notEmpty: {
                          message: 'Mail Username is required'
                    },
                }
            },

            password: {
                validators: {
                    notEmpty: {
                          message: 'Mail Password is required'
                    },
                }
            },
            encryption: {
                validators: {
                    notEmpty: {
                          message: 'Mail Encryption type is required'
                    },
                }
            },
            from_address: {
                validators: {
                    notEmpty: {
                          message: 'Mail from address is required'
                    },
                }
            },
            mail_email: {
                validators: {
                    notEmpty: {
                          message: 'Mail Name is required'
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
		                url: "/emails_settings/update_smtp",
		                type: "POST",
		                data: $("#new_form_smtp").serialize(),
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