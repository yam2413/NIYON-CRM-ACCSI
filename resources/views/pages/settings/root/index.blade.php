@extends('templates.default')
@section('title', 'Root Settings')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-6">

					<!--begin::Card-->
								<div class="card card-custom">
									<div class="card-header">
										<div class="card-title">
											Demo Settings
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_demo_settings">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Set as Demo</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<input data-switch="true" id="set_as_demo"  type="checkbox" @if(env('DEMO_STATUS') == 'true') checked="checked" @endif data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Demo Date Expired</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<div class='input-group' id='demo_date'>
																<input type='text' class="form-control" readonly name="demo_date"  placeholder="YYYY-MM-DD" value="{{env('DEMO_DATE') }}" />
																<div class="input-group-append">
																	<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
																</div>
															 </div>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Demo Message</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<textarea class="form-control" id="demo_msg" name="demo_msg" rows="5">{{env('DEMO_MESSAGE') }}</textarea>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													
													


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-demo-settings"><i class="fa fa-save"></i> Save</button>
														</div>
													</div>
												</div>
											</form>
											<!--end::Form-->

									</div>
								</div>
								<!--end::Card-->

				</div>


				<div class="col-lg-6">

					<!--begin::Card-->
								<div class="card card-custom">
									<div class="card-header">
										<div class="card-title">
											Active Features
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_features_settings">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Auto Dialer</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<input data-switch="true" id="feature_auto_dialer" type="checkbox" @if(env('FEATURE_AUTO_DIALER') == 'true') checked="checked" @endif data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Email</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<input data-switch="true" id="feature_email" type="checkbox" @if(env('FEATURE_EMAIL') == 'true') checked="checked" @endif data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">SMS</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<input data-switch="true" id="feature_sms" type="checkbox" @if(env('FEATURE_SMS') == 'true') checked="checked" @endif data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
															<span class="form-text text-muted"></span>
														</div>
													</div>
													
													


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-features-settings"><i class="fa fa-save"></i> Save</button>
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

	var arrows;
	 if (KTUtil.isRTL()) {
	  arrows = {
	   leftArrow: '<i class="la la-angle-right"></i>',
	   rightArrow: '<i class="la la-angle-left"></i>'
	  }
	 } else {
	  arrows = {
	   leftArrow: '<i class="la la-angle-left"></i>',
	   rightArrow: '<i class="la la-angle-right"></i>'
	  }
	 }

	 var start = moment();
	 var end = moment();


	$('.select2').select2({
	   placeholder: "Select a group"
	});

	$('[data-switch=true]').bootstrapSwitch();

	var set_as_demo = $('#set_as_demo').bootstrapSwitch('state');
	if(set_as_demo == false){
		//$('#save-demo-settings').prop('disabled', 'disabled');
		$('#demo_msg').prop('disabled', 'disabled');
	}

	jQuery(document).on('switchChange.bootstrapSwitch', '#set_as_demo', function(event, state){

	      if(state == true){
			//$('#save-demo-settings').prop('disabled', false);
			$('#demo_msg').prop('disabled', false);
		  }else{
			//$('#save-demo-settings').prop('disabled', 'disabled');
			$('#demo_msg').prop('disabled', 'disabled');
		  }
	    	
	});


	$('#demo_date').daterangepicker({
		buttonClasses: ' btn',
		applyClass: 'btn-primary',
		cancelClass: 'btn-secondary',
		singleDatePicker: true,
		showDropdowns: true,
		}, function(start, end, label) {
			   $('#demo_date .form-control').val( start.format('YYYY-MM-DD') );
	});


	jQuery(document).on('click', '#save-features-settings', function(e){
    e.preventDefault();

    var validation;
    
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_features_settings'),
        {
         fields: {

           
                    
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

                	var feature_auto_dialer = $('#feature_auto_dialer').bootstrapSwitch('state');
                	var feature_email = $('#feature_email').bootstrapSwitch('state');
                	var feature_sms = $('#feature_sms').bootstrapSwitch('state');

                     KTApp.blockPage({
                      overlayColor: '#000000',
                      state: 'primary',
                      message: 'Processing...'
                     });


                     $.ajax({
		                url: "/root/update_features",
		                type: "POST",
		                data: $("#new_form_features_settings").serialize()+'&feature_auto_dialer='+feature_auto_dialer+'&feature_email='+feature_email+'&feature_sms='+feature_sms,
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

	jQuery(document).on('click', '#save-demo-settings', function(e){
    e.preventDefault();

    var validation;
    
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_demo_settings'),
        {
         fields: {
            demo_date: {
                validators: {
                    notEmpty: {
                      message: 'Demo Date is required'
                    },
                }
            },
            demo_msg: {
                validators: {
                    notEmpty: {
                          message: 'Demo Message is required'
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

                	var set_as_demo = $('#set_as_demo').bootstrapSwitch('state');
                     KTApp.blockPage({
                      overlayColor: '#000000',
                      state: 'primary',
                      message: 'Processing...'
                     });


                     $.ajax({
		                url: "/root/update",
		                type: "POST",
		                data: $("#new_form_demo_settings").serialize()+'&set_as_demo='+set_as_demo,
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