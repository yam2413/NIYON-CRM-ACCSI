@extends('templates.default')
@section('title', 'Upload New Leads')
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
												<i class="fas fa-info-circle text-primary"></i>
											</span>
											Click the Dropdown button to select the right assign field for each column.
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_placeholder">
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

													<div class="mb-2">

														<div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Groups <span style="color:red;">*</span></label>
									                         <select name="groups" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($groups as $key => $group)
									                         		<option value="{{$group->id}}" @if(request()->input('groups')) selected @endif>{{$group->name}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                   </div>

									                  <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Coll Code <span style="color:red;">*</span></label>
									                         <select name="coll_code" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted">Account Code use for assigning account on upload module.</span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Card Number/Account Number <span style="color:red;">*</span></label>
									                         <select name="account_no" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted">This will be the unique ID of the account.</span>
									                       </div>
									                   </div>

									                   <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Full Name <span style="color:red;">*</span></label>
									                         <select name="full_name" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Primary Address <span style="color:red;">*</span></label>
									                         <select name="address" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>
									                   </div>

									                   <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Cycle Day </label>
									                         <select name="cycle_day" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Email Address </label>
									                         <select name="email" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                   </div>

									                   <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Due Date <span style="color:red;">*</span></label>
									                         <select name="due_date" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Endo Date </label>
									                         <select name="endo_date" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>
									                   </div>

									                   <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Outstanding Balance <span style="color:red;">*</span></label>
									                         <select name="outstanding_bal" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Total Loan Amount <span style="color:red;">*</span></label>
									                         <select name="loan_amount" class="form-control select2">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>
									                   </div>

									                   <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Home Number </label>
									                         <select name="home_no" id="home_no" class="form-control select2 contact_no">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Business Number </label>
									                         <select name="business_no" id="business_no" class="form-control select2 contact_no">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>


									                   </div>

									                   <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Cellphone Number</label>
									                         <select name="cellphone_no" id="cellphone_no" class="form-control select2 contact_no">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Other Phone numnber 1</label>
									                         <select name="other_phone_no_1" id="other_phone_no_1" class="form-control select2 contact_no">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>


									                   </div>

									                   <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Other Phone numnber 2 </label>
									                         <select name="other_phone_no_2" id="other_phone_no_2" class="form-control select2 contact_no">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Other Phone numnber 3</label>
									                         <select name="other_phone_no_3" id="other_phone_no_3" class="form-control select2 contact_no">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>


									                   </div>

									                   <div class="form-group row">

									                      <div class="col-lg-6">
									                        <label>Other Phone numnber 4 </label>
									                         <select name="other_phone_no_4" id="other_phone_no_4" class="form-control select2 contact_no">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>

									                       <div class="col-lg-6">
									                        <label>Other Phone numnber 5 </label>
									                         <select name="other_phone_no_5" id="other_phone_no_5" class="form-control select2 contact_no">
									                         	<option value="">Select</option>
									                         	@foreach ($select_datas as $key => $select_data)
									                         		<option value="{{$key}}">{{$select_data}}</option>
									                         	@endforeach
									                         </select>
									                         <span class="form-text text-muted"></span>
									                       </div>


									                   </div>


									              	</div>


													

													


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-placeholder"><i class="fa fa-save"></i> Submit</button>
															<button type="button" class="btn btn-light-primary font-weight-bold" id="cancel-uploads"><i class="flaticon-cancel"></i> Cancel
															</button>
															<a href="{{ route('pages.file_uploads.index') }}" class="btn btn-light-primary font-weight-bold" data-dismiss="modal"><i class="flaticon2-back"></i> Back</a>
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
<div id="show-modal-view-uploaded"></div>

<!-- Modal-->
<div class="modal fade" id="modal_column_data_type" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal_column_data_type" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    	<form class="form" id="new_form_data_type">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Type Validation</h5>
            </div>
            <div class="modal-body">
               <div class="alert alert-dark" role="alert">
					<p>
						Please ensure to assign field and set the kind of data type in each upload column before starting.
						<ul>
							<li><span class="label label-info label-inline mr-2">Text</span> = It is used to store a single letter, digit, punctuation mark, symbol, or blank space.</li>
							<li><span class="label label-info label-inline mr-2">Amount</span> = It is also a numeric data type used to store numbers that may have a fractional component like monetary values do (707.07, 0.7, 707.00).</li>
							<li><span class="label label-info label-inline mr-2">Date</span> = Typically stores a date in the YYYY-MM-DD format (ISO 8601 syntax).</li>
						</ul>
					</p>
				</div>

				<div class="card-body">
					<div class="mb-2">
								@for ($i = 1; $i < 80; $i++)
									@if($temp_uploads['data'.$i] != '--')
										<div class="form-group row">
											<div class="col-lg-6">
												<label>{{$temp_uploads['data'.$i]}} <span style="color:red;">*</span></label>
												<select class="form-control select2" style="width: 100%;" name="{{'data'.$i}}">
													<option value="">Select</option>
													<option value="text">TEXT</option>
													<option value="amount">AMOUNT</option>
													<option value="date">DATE</option>
												</select>
											</div>
										@php
											$i++;
										@endphp
											<div class="col-lg-6">
												<label>{{$temp_uploads['data'.$i]}} <span style="color:red;">*</span></label>
												<select class="form-control select2" style="width: 100%;" name="{{'data'.$i}}">
													<option value="">Select</option>
													<option value="text">TEXT</option>
													<option value="amount">AMOUNT</option>
													<option value="date">DATE</option>
												</select>
											</div>
										</div>
									@else
										@php
											break;
										@endphp
									@endif
								@endfor

									                  
					
					</div>
				</div>

            </div>
            <div class="modal-footer">
                <button type="button" id="cancel-uploads" class="btn btn-light-primary font-weight-bold">Cancel</button>
                <button type="button" id="save-fields_data_type" class="btn btn-primary font-weight-bold">Save changes</button>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

	@if($file_logs['data_type'] == 0)
		$('#modal_column_data_type').modal('toggle');
	@endif
	

	$('.select2').select2({
	    placeholder: "Select a placeholder",
	    allowClear: true
	});

	jQuery(document).on('change', 'select[name="cycle_day"],select[name="email"],select[name="loan_amount"],select[name="coll_code"],select[name="account_no"],select[name="full_name"],select[name="address"],select[name="due_date"],select[name="endo_date"],select[name="outstanding_bal"],select[name="home_no"],select[name="business_no"],select[name="cellphone_no"],select[name="other_phone_no_1"],select[name="other_phone_no_2"],select[name="other_phone_no_3"],select[name="other_phone_no_4"],select[name="other_phone_no_5"]', function(e){
		var val = $(this).val();
		var name = $(this).attr("name");
		var select_name = $(this);

		$('.select2').each(function(){
			 let elmId = $(this).attr("name");
			
			   if(name != elmId){
			   	
				   	if($(this).val() == val){
				   		$('select[name="'+name+'"]').val('1'); // Select the option with a value of '1'
						$('select[name="'+name+'"]').trigger('change'); // Notify any JS components that the value changed
				   		swal.fire({
				            text: 'You can only assign one field for each value',
				            icon: "error",
				            buttonsStyling: false,
				            confirmButtonText: "Ok, got it!",
				            customClass: {
				                confirmButton: "btn font-weight-bold btn-light-primary"
				            }
				            }).then(function() {
				              KTUtil.scrollTop();
				            });
				            
		                	return;
				   	}
			   	
			   }
			 
			            
		});
	});

	jQuery(document).on('click', '#save-fields_data_type', function(e){
    e.preventDefault();



     const validation =  FormValidation.formValidation(
        KTUtil.getById('new_form_data_type'),
        {
         fields: {

         	

            @for ($i = 1; $i < 80; $i++)

				@if($temp_uploads['data'.$i] != '--')
					'{{'data'.$i}}': {
		                validators: {
		                    notEmpty: {
		                      message: '{{$temp_uploads['data'.$i]}} is required'
		                    },
		                }
		            },					
										
				@else
					@php
						break;
					@endphp
				@endif
			@endfor

            
                    
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
			        url: "/file_uploads/data_type_upload_file",
			        type: "POST",
			        data: $('#new_form_data_type').serialize()+'&file_id={{$uniq_id}}',
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
			                          $('#modal_column_data_type').modal('toggle');
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
                   
                }else{
                	swal.fire({
		                    text: 'Please check the required fields',
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

            });
	});

	jQuery(document).on('click', '#save-placeholder', function(e){
    e.preventDefault();



     const validation =  FormValidation.formValidation(
        KTUtil.getById('new_form_placeholder'),
        {
         fields: {

         	groups: {
                validators: {
                    notEmpty: {
                      message: 'Groups is required'
                    },
                }
            },

            coll_code: {
                validators: {
                    notEmpty: {
                      message: 'Coll Code is required'
                    },
                }
            },
            account_no: {
                validators: {
                    notEmpty: {
                      message: 'Account No/Card Numnber is required'
                    },
                }
            },
            full_name: {
                validators: {
                    notEmpty: {
                      message: 'Full Name is required'
                    },
                }
            },
            address: {
                validators: {
                    notEmpty: {
                      message: 'Primary Address is required'
                    },
                }
            },
            due_date: {
                validators: {
                    notEmpty: {
                      message: 'Due Date is required'
                    },
                }
            },
            loan_amount: {
                validators: {
                    notEmpty: {
                      message: 'Loan Amount is required'
                    },
                }
            },
            outstanding_bal: {
                validators: {
                    notEmpty: {
                      message: 'Outstanding Balance is required'
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
                	var valid_alert = 0;
                	var contact_no = $(".contact_no").val();

                	$('.contact_no').each(function(){
			            if($(this).val() == ""){
			            	valid_alert+=0;
			            }else{
			            	valid_alert+=1;
			            }
			            
			        });

			        if(valid_alert == 0){
			        	swal.fire({
			                    text: 'Select atleast one field for the contact no of the account.',
			                    icon: "error",
			                    buttonsStyling: false,
			                    confirmButtonText: "Ok, got it!",
			                    customClass: {
			                        confirmButton: "btn font-weight-bold btn-light-primary"
			                    }
			                    }).then(function() {
			                        KTUtil.scrollTop();
			                    });

	                		return;
			        }
               
                	

        //              KTApp.blockPage({
        //               overlayColor: '#000000',
        //               state: 'primary',
        //               message: 'Processing...'
        //              });

        //             var modal = `<div class="modal fade" id="modal_view_uploaded" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		      //           <div class="modal-dialog modal-xl" role="document" id="modaly_body_uploaded">
		      //           </div>
		      //       </div>`;
				    // $("#show-modal-view-uploaded").html(modal);
				    
				    // $( "#modaly_body_uploaded").load( "/file_uploads/check_uploads/{{$uniq_id}}/view_uploaded/?"+data, function() {
				    //     KTApp.unblockPage();
				    //     $('#modal_view_uploaded').modal('toggle');
				    // });

				    var data = $('#new_form_placeholder').serialize();
                    setTimeout(function(){
			             window.location = "/file_uploads/check_uploads/{{$uniq_id}}/view_uploaded/?"+data;
			                                  
			       }, 100);
                   
                }else{
                	swal.fire({
		                    text: 'Please check the required fields',
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

            });
	});

	jQuery(document).on('click', '#cancel-uploads', function(e){
	Swal.fire({
	        title: "Are you sure?",
	        text: "You wont be able to revert this!",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes"
	    }).then(function(result) {
	        if (result.value) {
	            
	        KTApp.blockPage({
              overlayColor: '#000000',
              state: 'primary',
              message: 'Processing...'
            });

	 
            $.ajax({
		        url: "/file_uploads/cancel_upload_file",
		        type: "POST",
		        data: 'file_id={{$uniq_id}}',
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
		                         var url = '{{ route('pages.file_uploads.index') }}';
		                         window.location = url;      
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