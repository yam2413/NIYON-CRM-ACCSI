@extends('templates.default')
@section('title', 'Email Templates')
@push('scripts')
<script src="{{ asset('plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
@endpush
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
								<span class="card-icon">
									<i class="far fa-envelope-open text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Manage your email templates.</h3>
							</div>
							<div class="card-toolbar">
								 
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin::Form-->
											<form class="form" id="new_form_emails">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Select Group Templates</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<select id="filter_groups" name="group" class="form-control select2">
																<option value="">Select Group</option>
																@foreach ($groups as $key => $group)
																    <option value="{{$group->id}}">{{$group->name}}</option>
																 @endforeach
															</select>
															<span class="form-text text-muted">Select a group to edit the email templates.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Place Holder For Subject</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<select class="form-control" id="placeholder_subject" disabled="disabled">
																<option value="">Select Place Holder for subject</option>
																<option value="{[FULL_NAME]}">{[FULL_NAME]}</option>
																<option value="{[ADDRESS]}">{[ADDRESS]}</option>
																<option value="{[OUTSTANDING_BALANCE]}">{[OUTSTANDING_BALANCE]}</option>
																<option value="{[DUE_DATE]}">{[DUE_DATE]}</option>
																<option value="{[ENDO_DATE]}">{[ENDO_DATE]}</option>
																<option value="{[ACCOUNT_NUMER]}">{[ACCOUNT_NUMER]}</option>
																<option value="{[PTP_DATE]}">{[PTP_DATE]}</option>
																<option value="{[PTP_AMOUNT]}">{[PTP_AMOUNT]}</option>
															</select>
															<span class="form-text text-muted">This placeholder is the corresponding data of the accounts.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Subject</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" id="subject" name="subject" class="form-control" disabled="disabled">
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Place Holder for Body</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<select class="form-control" id="placeholder" disabled="disabled">
																<option value="">Select Place Holder for email body</option>
																<option value="{[FULL_NAME]}">{[FULL_NAME]}</option>
																<option value="{[ADDRESS]}">{[ADDRESS]}</option>
																<option value="{[OUTSTANDING_BALANCE]}">{[OUTSTANDING_BALANCE]}</option>
																<option value="{[DUE_DATE]}">{[DUE_DATE]}</option>
																<option value="{[ENDO_DATE]}">{[ENDO_DATE]}</option>
																<option value="{[ACCOUNT_NUMER]}">{[ACCOUNT_NUMER]}</option>
																<option value="{[PTP_DATE]}">{[PTP_DATE]}</option>
																<option value="{[PTP_AMOUNT]}">{[PTP_AMOUNT]}</option>
															</select>
															<span class="form-text text-muted">This placeholder is the corresponding data of the accounts.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Email Body</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<textarea id="kt-tinymce-2" name="body" class="tox-target"></textarea>
															<span class="form-text text-muted" id="status-update"></span>
														</div>
													</div>


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-email-temp" disabled="disabled"><i class="fa fa-save"></i> Save</button>
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

$('.select2').select2();

	tinymce.init({
       selector: '#kt-tinymce-2'
    });

    tinymce.get('kt-tinymce-2').setMode('readonly');

    jQuery(document).on('change', '#placeholder',function(e) {
        e.preventDefault();
        //alert();
            var selectedItem = $("#placeholder option:selected");
      
            var data =  tinymce.get('kt-tinymce-2').getContent();
            tinymce.get('kt-tinymce-2').setContent(data+' '+selectedItem.val());
    });

    jQuery(document).on('change', '#placeholder_subject',function(e) {
        e.preventDefault();
        var selectedItem = $("#placeholder_subject option:selected");
        $('#subject').val(function(_,v){
          if(selectedItem.val() != '0'){
            return v + selectedItem.val();
          }
          else {
            return v;
          }
        });
  	});  

    jQuery(document).on('change', '#filter_groups',function(e) {
        e.preventDefault();
        var val = $(this).val();
        if(val != ''){
        	

        	KTApp.blockPage({
	            overlayColor: '#000000',
	            state: 'primary',
	            message: 'Processing...'
	          });

	        $.ajax({
	              url: "/emails/select_email_templates",
	              type: "POST",
	              data:'group='+val,
	              headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	              },
	              dataType: "JSON",
	              success: function(data){
	                KTApp.unblockPage();
	                tinymce.get('kt-tinymce-2').setMode('design');
	                $('#subject').prop('disabled', false);
	                $('#placeholder_subject').prop('disabled', false);
		        	$('#placeholder').prop('disabled', false);
		        	$('#save-email-temp').prop('disabled', false);
	                $("#status-update").text(data.status);
	                $("#subject").val(data.subject);
            		tinymce.get('kt-tinymce-2').setContent(data.body);       
	                                
	              }
	          });
        	
        }else{
        	$('#save-email-temp').prop('disabled', 'disabled');
        	$('#placeholder').prop('disabled', 'disabled');
        	$('#subject').prop('disabled', 'disabled');
	        $('#placeholder_subject').prop('disabled', 'disabled');
	        $("#subject").val('');
        	tinymce.get('kt-tinymce-2').setMode('readonly');
        }
  });


  jQuery(document).on('click', '#save-email-temp', function(e){
    e.preventDefault();

    var validation;
    


    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_emails'),
        {
         fields: {

            group: {
                validators: {
                    notEmpty: {
                      message: 'Group is required'
                    },
                }
            },
            subject: {
                validators: {
                    notEmpty: {
                      message: 'Subject is required'
                    },
                    stringLength: {
					    max:70,
					    message: '70 Character limit is allowed'
					},
                }
            },
            body: {
                validators: {
                    callback: {
                            message: 'The body must be between 5 and 1000 characters long',
                                callback: function (value) {
                                        // Get the plain text without HTML
                                        const text = tinymce.activeEditor.getContent({
                                            format: 'text',
                                        });

                             return text.length <= 200 && text.length >= 5;
                        },
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

                     var data =  tinymce.get('kt-tinymce-2').getContent();

                     $.ajax({
		                url: "/emails/store",
		                type: "POST",
		                data: $("#new_form_emails").serialize()+'&body='+encodeURIComponent(data),
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

                   
                }

            });
	}); 


});
</script>
@endpush