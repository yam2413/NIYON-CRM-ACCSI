@extends('templates.default')
@section('title', 'Create File Header')
@section('content')
<!--begin::Entry-->
<style>
    .div-assign-field{
        background-color: red;
    }
</style>
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
											<h3 class="card-label">Create File Header for {{$groups->name}}</h3>
										</div>
									</div>
									<div class="card-body">
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
										<!--begin::Form-->
											<form class="form" id="new_form_file_header">
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

                                                    <div class="separator separator-dashed my-8"></div>
                                                    <div id="kt_repeater_1">
                                                        <div class="form-group row">
                                                            {{-- <label class="col-lg-1 col-form-label text-right">Column Name:</label> --}}
                                                            <div data-repeater-list="group_a" class="col-lg-12">
                                                               @if (count($file_headers) > 0)
                                                                   @foreach ($file_headers as $file_header)
                                                                        <div data-repeater-item="{{$file_header->id}}" class="form-group row align-items-center">
                                                                             <input type="hidden" class="form-control" name="head_id" value="{{$file_header->id}}"/>
                                                                            <div class="col-md-3">
                                                                                <label>Header Name:</label>
                                                                                <input type="text" class="form-control" name="header_name" placeholder="Enter Header name" value="{{$file_header->field_name}}" readonly="readonly"/>
                                                                                <div class="d-md-none mb-2"></div>
                                                                                <span class="form-text text-muted"></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label>Data Type:</label>
                                                                                <select class="form-control" name="data_type">
                                                                                    <option value="">Select</option>
                                                                                    <option value="text" @if($file_header->data_type == 'text') selected @endif>TEXT</option>
                                                                                    <option value="amount" @if($file_header->data_type == 'amount') selected @endif>AMOUNT</option>
                                                                                    <option value="date" @if($file_header->data_type == 'date') selected @endif>DATE</option>
                                                                                </select>
                                                                                <div class="d-md-none mb-2"></div>
                                                                            </div>
                                                                            
                                                                            <div class="col-md-3 div-assign">
                                                                                <label>Assign Field:</label>
                                                                                <select class="form-control assign_field" name="assign_field" @if($file_header->assign_field == 'require_field') style="background-color: #2B95F3;" @elseif($file_header->assign_field != '') style="background-color: #F3AD2B;"  @endif>
                                                                                    <option value="">Select</option>
                                                                                    <option value="require_field" @if($file_header->assign_field == 'require_field') selected @endif>Require Field</option>
                                                                                    <option value="account_no" @if($file_header->assign_field == 'account_no') selected @endif>Account No</option>
                                                                                    <option value="ch_name" @if($file_header->assign_field == 'ch_name') selected @endif>CH Name</option>
                                                                                    <option value="outstanding_amount" @if($file_header->assign_field == 'outstanding_amount') selected @endif>Outstanding Balance</option>
                                                                                    {{-- <option value="ch_birthday" @if($file_header->assign_field == 'ch_birthday') selected @endif>CH Birthday</option>
                                                                                    <option value="ch_address" @if($file_header->assign_field == 'ch_address') selected @endif>CH Address</option>
                                                                                    <option value="ch_email_address" @if($file_header->assign_field == 'ch_email_address') selected @endif>CH Email Address</option> --}}
                                                                                    <option value="home_no" @if($file_header->assign_field == 'home_no') selected @endif>Home No.</option>
                                                                                    <option value="mobile_no" @if($file_header->assign_field == 'mobile_no') selected @endif>Mobile No.</option>
                                                                                    <option value="office_no" @if($file_header->assign_field == 'office_no') selected @endif>Office/Business No.</option>
                                                                                    <option value="other_contact_1" @if($file_header->assign_field == 'other_contact_1') selected @endif>Other Contact No. 1</option>
                                                                                    <option value="other_contact_2" @if($file_header->assign_field == 'other_contact_2') selected @endif>Other Contact No. 2</option>
                                                                                    <option value="other_contact_3" @if($file_header->assign_field == 'other_contact_3') selected @endif>Other Contact No. 3</option>
                                                                                    <option value="other_contact_4" @if($file_header->assign_field == 'other_contact_4') selected @endif>Other Contact No. 4</option>
                                                                                    <option value="other_contact_5" @if($file_header->assign_field == 'other_contact_5') selected @endif>Other Contact No. 5</option>
                                                                                    {{-- <option value="other_contact_6" @if($file_header->assign_field == 'other_contact_6') selected @endif>Other Contact No. 6</option>
                                                                                    <option value="other_contact_7" @if($file_header->assign_field == 'other_contact_7') selected @endif>Other Contact No. 7</option>
                                                                                    <option value="other_contact_8" @if($file_header->assign_field == 'other_contact_8') selected @endif>Other Contact No. 8</option>
                                                                                    <option value="other_contact_9" @if($file_header->assign_field == 'other_contact_9') selected @endif>Other Contact No. 9</option>
                                                                                    <option value="other_contact_10" @if($file_header->assign_field == 'other_contact_10') selected @endif>Other Contact No. 10</option> --}}
                                                                                    {{-- <option value="loan_amount" @if($file_header->assign_field == 'loan_amount') selected @endif>Loan Amount</option>
                                                                                    <option value="endo_date" @if($file_header->assign_field == 'endo_date') selected @endif>Endo Date</option>
                                                                                    <option value="cycle_day" @if($file_header->assign_field == 'cycle_day') selected @endif>Cycle Day</option> --}}
                                                                                </select>
                                                                                <div class="d-md-none mb-2"></div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
                                                                                <i class="la la-trash-o"></i>Remove</a>
                                                                            </div>
                                                                        </div>
                                                                   @endforeach
                                                               @else
                                                                    <div data-repeater-item="" class="form-group row align-items-center">
                                                                    <div class="col-md-3">
                                                                        <label>Header Name:</label>
                                                                        <input type="text" class="form-control" name="header_name" placeholder="Enter Header name" />
                                                                        <div class="d-md-none mb-2"></div>
                                                                        <span class="form-text text-muted"></span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>Data Type:</label>
                                                                        <select class="form-control" name="data_type">
                                                                            <option value="">Select</option>
                                                                            <option value="text">TEXT</option>
                                                                            <option value="amount">AMOUNT</option>
                                                                            <option value="date">DATE</option>
                                                                        </select>
                                                                        <div class="d-md-none mb-2"></div>
                                                                    </div>
                                                                    <div class="col-md-3 div-assign-field">
                                                                        <label>Assign Field:</label>
                                                                        <select class="form-control" name="assign_field">
                                                                            <option value="">Select</option>
                                                                            <option value="require_field">Require Field</option>
                                                                            <option value="account_no">Account No</option>
                                                                            <option value="ch_name">CH Name</option>
                                                                            <option value="outstanding_amount">Outstanding Balance</option>
                                                                            {{-- <option value="ch_birthday">CH Birthday</option>
                                                                            <option value="ch_address">CH Address</option>
                                                                            <option value="ch_email_address">CH Email Address</option> --}}
                                                                            <option value="home_no">Home No.</option>
                                                                            <option value="mobile_no">Mobile No.</option>
                                                                            <option value="office_no">Office/Business No.</option>
                                                                            <option value="other_contact_1">Other Contact No. 1</option>
                                                                            <option value="other_contact_2">Other Contact No. 2</option>
                                                                            <option value="other_contact_3">Other Contact No. 3</option>
                                                                            <option value="other_contact_4">Other Contact No. 4</option>
                                                                            <option value="other_contact_5">Other Contact No. 5</option>
                                                                            {{-- <option value="other_contact_6">Other Contact No. 6</option>
                                                                            <option value="other_contact_7">Other Contact No. 7</option>
                                                                            <option value="other_contact_8">Other Contact No. 8</option>
                                                                            <option value="other_contact_9">Other Contact No. 9</option>
                                                                            <option value="other_contact_10">Other Contact No. 10</option> --}}
                                                                            {{-- <option value="loan_amount">Loan Amount</option>
                                                                            
                                                                            <option value="endo_date">Endo Date</option>
                                                                            <option value="cycle_day">Cycle Day</option> --}}
                                                                        </select>
                                                                        <div class="d-md-none mb-2"></div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
                                                                        <i class="la la-trash-o"></i>Remove</a>
                                                                    </div>
                                                                </div>
                                                               @endif
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-lg-2 col-form-label text-right"></label>
                                                            <div class="col-lg-4">
                                                                <a href="javascript:;" data-repeater-create="" class="btn btn-sm font-weight-bolder btn-light-primary">
                                                                <i class="la la-plus"></i>Add</a>
                                                            </div>
                                                        </div>
                                                    </div>
													


												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-file-header"><i class="fa fa-save"></i> Submit</button>
															<button type="submit" class="btn btn-light-danger font-weight-bold" id="delete-file-header"><i class="flaticon-cancel"></i> Delete
															</button>
															<a href="{{ route('pages.groups.index') }}" class="btn btn-light-primary font-weight-bold" data-dismiss="modal"><i class="flaticon2-back"></i> Back</a>
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
    
    $('#kt_repeater_1').repeater({
            initEmpty: false,
           

             
            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {                
                $(this).slideUp(deleteElement);                 
            }   
    });

    jQuery(document).on('change', '.assign_field', function(e){
        //$(".div-assign-field").css('background-color','red');
        if($(this).val() == 'require_field'){
            $(".div-assign").find(this).css('background-color','#2B95F3');
        }else if($(this).val() != ''){
            $(".div-assign").find(this).css('background-color','#F3AD2B');
        }else{
            $(".div-assign").find(this).css('background-color','');
        }
        
        // alert();
       // $(this).find('option').css('background-color', 'red');
    });

    jQuery(document).on('click', '#save-file-header', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_file_header'),
        {
         fields: {

            'header_name': {
                validators: {
                    notEmpty: {
                      message: 'Group name is required'
                    },
                }
            },
            'data_type': {
                validators: {
                    notEmpty: {
                      message: 'Description is required'
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
		                url: "/groups/store_file_header",
		                type: "POST",
		                data: $("#new_form_file_header").serialize()+'&group_id={{$id}}',
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

    

    jQuery(document).on('click', '#delete-file-header', function(e){
        e.preventDefault();
		 Swal.fire({
	        title: "Are you sure?",
	        text: "You won't be able to revert this!",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes, delete it!"
	    }).then(function(result) {
	        if (result.value) {

	        	$.ajax({
		            url: "/groups/delete_file_header",
		            type: "POST",
		            data: "id={{$id}}",
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
		                                	console.log('on close event fired!');
			                                var get_campaign = '{{ route('pages.groups.index') }}';
            				   
				                            window.location = get_campaign;
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