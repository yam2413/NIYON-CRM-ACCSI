@extends('templates.default')
@section('title', 'SMS Template\'s')
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
									<i class="fas fa-mobile-alt text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Manage your SMS templates.</h3>
							</div>
							<div class="card-toolbar">
								 
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin::Form-->
											<form class="form" id="new_form_sms">
												@csrf
												<div class="card-body">

													<div class="alert alert-custom alert-light-danger d-none" role="alert">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Place Holder</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<select class="form-control" id="placeholder" disabled="disabled">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Message</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<textarea class="form-control" id="body" name="body" rows="6" disabled="disabled"></textarea>
															<span class="form-text text-muted" id="status-update"></span>
															<span class="form-text text-muted" id="remaining">160 characters remaining</span>
															<span class="form-text text-muted" id="messages-count">1 message(s)</span>
														</div>
													</div>


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-sms" disabled="disabled"><i class="fa fa-save"></i> Save</button>
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

	
	jQuery(document).on('keyup','#body', function(e){

        var $remaining = $('#remaining'),
        $messages = $('#messages-count');

        var chars = this.value.length;
        var messages = Math.ceil(chars / 160);
        var remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
        //$sms_count.val(messages);

        if (messages == 7) {

             var messages = Math.ceil(chars / 40);
             var remaining = messages * 40 - (chars % (messages * 40) || messages * 40);
             $remaining.text(remaining + ' characters remaining');
             $messages.text('7 message(s)');
             //$sms_count.val('7');         

        }


    });

    function count_body_msg(){
    	var $remaining = $('#remaining'),
        $messages = $('#messages-count');

        var chars = $("#body").val().length;
        var messages = Math.ceil(chars / 160);
        var remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
        //$sms_count.val(messages);

        if (messages == 7) {

             var messages = Math.ceil(chars / 40);
             var remaining = messages * 40 - (chars % (messages * 40) || messages * 40);
             $remaining.text(remaining + ' characters remaining');
             $messages.text('7 message(s)');
             //$sms_count.val('7');         

        }
    }

    count_body_msg();

    jQuery(document).on('change', '#placeholder',function(e) {
        e.preventDefault();
        var selectedItem = $("#placeholder option:selected");
        $('#body').val(function(_,v){
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
	              url: "/sms_template/select_sms_templates",
	              type: "POST",
	              data:'group='+val,
	              headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	              },
	              dataType: "JSON",
	              success: function(data){
	                KTApp.unblockPage();
	                $('#body').prop('disabled', false);
		        	$('#placeholder').prop('disabled', false);
		        	$('#save-sms').prop('disabled', false);
		        	$("#status-update").text(data.status);
		        	$("#body").val(data.body);
	                count_body_msg();                 
	              }
	          });
        	
        }else{
        	$("#body").val('');
        	$('#body').prop('disabled', 'disabled');
        	$('#placeholder').prop('disabled', 'disabled');
        	$("#status-update").text(data.status);
        	$('#save-sms').prop('disabled', 'disabled');
        }
  });


  jQuery(document).on('click', '#save-sms', function(e){
    e.preventDefault();

    var validation;
    


    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_sms'),
        {
         fields: {

            group: {
                validators: {
                    notEmpty: {
                      message: 'Group is required'
                    },
                }
            },
            body: {
                validators: {
                    notEmpty: {
                      message: 'Body Message is required'
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
		                url: "/sms_template/store",
		                type: "POST",
		                data: $("#new_form_sms").serialize(),
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