@extends('templates.default')
@section('title', 'SMS Configuration')
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
											SMS Configuration
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_smsmo">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">SMSMO Host *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="host" placeholder="" value="{{env('SMSMO_HOST') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">SMSMO Username *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="username" placeholder="" value="{{env('SMSMO_USERNAME') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">SMSMO Password *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="password" class="form-control" name="password" placeholder="" value="{{env('SMSMO_PASSWORD') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													

												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-smsmo"><i class="fa fa-save"></i> Save</button>
															<button type="reset" class="btn btn-light-primary font-weight-bold" id="clear-smsmo"><i class="flaticon-cancel"></i> Cancel
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
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

$('.select2').select2({
   placeholder: "Select a group"
});

jQuery(document).on('click', '#save-smsmo', function(e){
    e.preventDefault();

    var validation;
    
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_smsmo'),
        {
         fields: {
            host: {
                validators: {
                    notEmpty: {
                      message: 'Host is required'
                    },
                }
            },
            username: {
                validators: {
                    notEmpty: {
                          message: 'Username is required'
                    },
                }
            },

            password: {
                validators: {
                    notEmpty: {
                          message: 'Password is required'
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
		                url: "/sms/update",
		                type: "POST",
		                data: $("#new_form_smsmo").serialize(),
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