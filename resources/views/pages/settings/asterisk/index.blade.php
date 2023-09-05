@extends('templates.default')
@section('title', 'Asterisk Configuration')
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
											Asterisk Configuration
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_asterisk">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Asterisk Host *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="host" placeholder="" value="{{env('ASTERISK_HOST') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Asterisk Port *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="email" class="form-control" name="port" placeholder="" value="{{env('ASTERISK_PORT') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Asterisk Username *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="username" placeholder="" value="{{env('ASTERISK_USERNAME') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Asterisk Password *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="password" class="form-control" name="password" placeholder="" value="{{env('ASTERISK_PASSWORD') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Asterisk Version *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<select class="form-control" name="version">
																<option value="">Select Asterisk Version</option>
																<option value="2.4" @if(env('ASTERISK_VERSION') == '2.4') selected @endif>Elastix 2.4</option>
																<option value="2.5" @if(env('ASTERISK_VERSION') == '2.5') selected @endif>Elastix 2.5</option>
																<option value="2.6" @if(env('ASTERISK_VERSION') == '2.6') selected @endif>Isabel</option>
															</select>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Asterisk Prefix *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="prefix" placeholder="" value="{{env('ASTERISK_PREFIX') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Asterisk Phone *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<select class="form-control" name="phone">
																<option value="">Select Asterisk Phone</option>
																<option value="ip_phone" @if(env('ASTERISK_PHONE') == 'ip_phone') selected @endif>IP Phone/Softphone</option>
																<option value="web_phone" @if(env('ASTERISK_PHONE') == 'web_phone') selected @endif>Web Phone</option>
																<option value="all" @if(env('ASTERISK_PHONE') == 'all') selected @endif>All (Allow the user to select Phone Device)</option>
															</select>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<p>PBX Database Connection</p>
													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Username *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="db_username" placeholder="" value="{{env('DB_USERNAME_SECOND') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Password *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="password" class="form-control" name="db_password" placeholder="" value="{{env('DB_PASSWORD_SECOND') }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="button" id="pbx_test_connection" class="btn btn-success font-weight-bold mr-2">Test Connection</button>
														</div>
													</div>

													


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-asterisk"><i class="fa fa-save"></i> Save</button>
															<button type="button" class="btn btn-secondary font-weight-bold mr-2" data-toggle="modal" data-target="#test_call_modal"><i class="fas fa-phone"></i> Test Call</button>
															<button type="reset" class="btn btn-light-primary font-weight-bold" id="clear-asterisk"><i class="flaticon-cancel"></i> Cancel
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

			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<div class="example">
							<div class="example-code">
								<span class="example-copy" data-toggle="tooltip" title="" data-original-title="Copy code"></span>
								<pre class="example-preview" id="asterisk_call_state_prev"></pre>
								<pre class="example-preview" id="asterisk_extesion_prev"></pre>
							</div>
						</div>
					</div>
				</div>
			</div>


	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->

<!-- Modal-->
<div class="modal fade" id="test_call_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="form" id="form_test_call">
        	<div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Test Call</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">

	                <div class="form-group row">
						<label class="col-form-label text-right col-lg-3 col-sm-12">Mobile No.</label>
						<div class="col-lg-9 col-md-9 col-sm-12">
							<input type="number" class="form-control" name="contact_no" id="contact_no" placeholder="" value="" />
							<span class="form-text text-muted"></span>
						</div>
					</div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" class="btn btn-primary font-weight-bold" id="btn_test_call">Call</button>
	            </div>
	        </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {


get_call_status_view = function(contact_no){
		jQuery.ajax({
			url: "/asterisk/get_asterisk_status_debug",
			type: "POST",
			data: 'extension={{Auth::user()->extension}}&contact_no='+contact_no,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success: function(data) {
				$("#asterisk_extesion_prev").text('');
				$("#asterisk_extesion_prev").text(data.msg);

				$("#asterisk_call_state_prev").text('');
				$("#asterisk_call_state_prev").text(data.call_state);
				
			},
			error: function(data){


			}
		}); 
	}

$('.select2').select2({
   placeholder: "Select a group"
});

jQuery(document).on('click', '#save-asterisk', function(e){
    e.preventDefault();

    var validation;
    
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_asterisk'),
        {
         fields: {
            host: {
                validators: {
                    notEmpty: {
                      message: 'Host is required'
                    },
                }
            },
            port: {
                validators: {
                    notEmpty: {
                      message: 'Port is required'
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
            version: {
                validators: {
                    notEmpty: {
                          message: 'Version is required'
                    },
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                          message: 'Phone is required'
                    },
                }
            },
            db_username: {
                validators: {
                    notEmpty: {
                          message: 'Database username is required'
                    },
                }
            },
            db_password: {
                validators: {
                    notEmpty: {
                          message: 'Database password is required'
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
		                url: "/asterisk/update",
		                type: "POST",
		                data: $("#new_form_asterisk").serialize(),
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

	jQuery(document).on('click', '#pbx_test_connection', function(e){
    e.preventDefault();

    		KTApp.blockPage({
                overlayColor: '#000000',
                state: 'primary',
                message: 'Processing...'
            });


            $.ajax({
		        url: "/asterisk/test_pbx_connection",
		        type: "POST",
		        data: $("#form_test_call").serialize(),
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

    });

	jQuery(document).on('click', '#btn_test_call', function(e){
    e.preventDefault();

    var validation;
    
    validation = FormValidation.formValidation(
        KTUtil.getById('form_test_call'),
        {
         fields: {
            contact_no: {
                validators: {
                    notEmpty: {
                      message: 'Mobile No. is required'
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

                     var contact_no = $("#contact_no").val();
                     $.ajax({
		                url: "/asterisk/test_call",
		                type: "POST",
		                data: $("#form_test_call").serialize(),
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
		                            	intervalID = setInterval(function() { get_call_status_view(contact_no); }, 1000);
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