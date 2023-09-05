<h3>Talking with: {{ucfirst($crm_leads->full_name)}}</h3>
<small>Leads ID: {{$crm_leads->profile_id}}</small> | <small>Upload ID: {{$crm_leads->file_id}}</small>
<hr>
<p class="mb-0">
	<span class="card-icon">
		<i class="fas fa-info-circle text-primary"></i>
	</span>
	Note: To go on the next account, you must add new entry to update the call disposition and account status.
</p>
<div class="row">
	
		<div class="col-xl-3">
			<button type="button" id="open-new-entries" class="btn btn-primary btn-lg btn-block">
				<i class="fas fa-plus"></i> New Entry
			</button>
			{{-- <a class="btn btn-primary btn-lg btn-block" id="add-new-entries-tab" data-toggle="tab" href="#add-new-entries" aria-controls="add-new-entries">
				<i class="fas fa-plus"></i> Add New Entry
			</a> --}}
		</div>
		<div class="col-xl-3">
			<button id="open-call-again" type="button" class="btn btn-success btn-lg btn-block">
				<i class="fas fa-phone"></i> Call
			</button>
			{{-- <a class="btn btn-success btn-lg btn-block" id="dial-numbers-tab" data-toggle="tab" href="#dial-numbers" aria-controls="dial-numbers">
				<i class="fas fa-phone"></i> Call
			</a> --}}
		</div>
		<hr>			
</div>

<div class="separator separator-solid separator-border-2 separator-dark"></div>
<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" id="loan-tab" data-toggle="tab" href="#loan">
			<span class="nav-icon">
				<i class="fas fa-user-tag"></i>
			</span>
			<span class="nav-text">Loan Details</span>
		</a>
	</li>
	
	<li class="nav-item">
		<a class="nav-link" id="file-upload-tab" data-toggle="tab" href="#file-upload" aria-controls="file-upload">
			<span class="nav-icon">
				<i class="fas fa-file-import"></i>
			</span>
			<span class="nav-text">File Uploaded</span>
		</a>
	</li>

	<li class="nav-item">
		<a class="nav-link" id="personal-tab" data-toggle="tab" href="#personal" aria-controls="personal">
			<span class="nav-icon">
				<i class="fas fa-mobile-alt"></i>
			</span>
			<span class="nav-text">Personal Details</span>
		</a>
	</li>

	<li class="nav-item">
		<a class="nav-link" id="manual-numbers-tab" data-toggle="tab" href="#manual-numbers" aria-controls="manual-numbers">
			<span class="nav-icon">
				<i class="fas fa-mobile-alt"></i>
			</span>
			<span class="nav-text">Manual Numbers</span>
		</a>
	</li>

	<li class="nav-item">
		<a class="nav-link" id="loan-history-tab" data-toggle="tab" href="#loan-history" aria-controls="loan-history">
			<span class="nav-icon">
				<i class="fas fa-piggy-bank"></i>
			</span>
			<span class="nav-text">loan History</span>
		</a>
	</li>

	<li class="nav-item">
		<a class="nav-link" id="call-logs-tab" data-toggle="tab" href="#call-logs" aria-controls="call-logs">
			<span class="nav-icon">
				<i class="fas fa-blender-phone"></i>
			</span>
			<span class="nav-text">Call Logs</span>
		</a>
	</li>
	<li class="nav-item" style="display:none;">
		<a class="nav-link" id="add-new-entries-tab" data-toggle="tab" href="#add-new-entries" aria-controls="add-new-entries">
			<span class="nav-icon">
				<i class="fas fa-blender-phone"></i>
			</span>
			<span class="nav-text">Add New Entry</span>
		</a>
	</li>

	<li class="nav-item" style="display:none;">
		<a class="nav-link" id="dial-numbers-tab" data-toggle="tab" href="#dial-numbers" aria-controls="dial-numbers">
			<span class="nav-icon">
				<i class="fas fa-blender-phone"></i>
			</span>
			<span class="nav-text">Call</span>
		</a>
	</li>
</ul>
						
<div class="tab-content mt-5" id="myTabContent">
	<div class="tab-pane fade" id="add-new-entries" role="tabpanel" aria-labelledby="add-new-entries-tab">
		<!--begin::Row-->
		@include('pages.agent.tab_leads.add_entry')
	
	</div>
	<div class="tab-pane fade" id="dial-numbers" role="tabpanel" aria-labelledby="dial-numbers-tab">
		<!--begin::Row-->
		@include('pages.agent.tab_leads.dial_numbers')
	
	</div>
	<div class="tab-pane fade active show" id="loan" role="tabpanel" aria-labelledby="loan-tab">
		<!--begin::Row-->
		@include('pages.agent.tab_leads.loans')
	
	</div>
	<div class="tab-pane fade" id="file-upload" role="tabpanel" aria-labelledby="file-upload-tab">
		@include('pages.leads.other_uploaded')
	</div>
	<div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
		@include('pages.leads.personal_details')
	</div>
	<div class="tab-pane fade" id="manual-numbers" role="tabpanel" aria-labelledby="manual-numbers-tab">
		@include('pages.agent.tab_leads.manual_numbers')
	</div>
	<div class="tab-pane fade" id="loan-history" role="tabpanel" aria-labelledby="loan-history-tab">
		@include('pages.leads.loan_history')
	</div>
	<div class="tab-pane fade" id="call-logs" role="tabpanel" aria-labelledby="call-logs-tab">
		@include('pages.leads.call_logs')
	</div>
</div>

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

$('[data-switch=true]').bootstrapSwitch();
$('#call_status').select2();
var intervalID;

	$('#kt_repeater_manual_numbers').repeater({
        initEmpty: false,
             
        show: function() {
                $(this).slideDown();                               
        },

        hide: function(deleteElement) {                 
            if(confirm('Are you sure you want to delete this element?')) {
                $(this).slideUp(deleteElement);
            }                                  
        }      
    });


	function load_ptp_histories(){
		var tbl_ptp_histories = $('#tbl_ptp_histories');
		tbl_ptp_histories.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/leads/getPTPHistories",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
				          // parameters for custom backend script demo
				            "profile_id" : '{{$crm_leads->profile_id}}',
				            "leads_id" : '{{$crm_leads->id}}',
				       },
				  },
			order: [[ 5, "desc" ]],
			columns: [
				        
				    {data: 'created_by',orderable: false},
				    {data: 'status',orderable: false},
				    {data: 'payment_date',orderable: false},
				    {data: 'payment_amount',orderable: false},
				    {data: 'remarks',orderable: false},
				    {data: 'created_at',orderable: false},
				],
			});
	}

	function load_loan_histories(){
		var tbl_loan_history = $('#tbl_loan_history');
		tbl_loan_history.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/leads/getLoanHistory",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
				          // parameters for custom backend script demo
				            "profile_id" : '{{$crm_leads->profile_id}}',
				            "leads_id" : '{{$crm_leads->id}}',
				            "account_number" : '{{$crm_leads->account_number}}',
				       },
				  },
			order: [[ 6, "desc" ]],
			columns: [
				        
				    {data: 'loan_amount',orderable: false},
				    {data: 'status',orderable: false},
				    {data: 'outstanding_balance',orderable: false},
				    {data: 'ptp_amount',orderable: false},
				    {data: 'payment_date',orderable: false},
				    {data: 'assign_user',orderable: false},
				    {data: 'assign_group',orderable: false},
				    {data: 'created_at',orderable: false},
				],
			});
	}

	function load_call_histories(){
		var tbl_call_logs = $('#tbl_call_logs');
		tbl_call_logs.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/leads/getCallLogs",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
				          // parameters for custom backend script demo
				            "profile_id" : '{{$crm_leads->profile_id}}',
				            "leads_id" : '{{$crm_leads->id}}',
				       },
				  },
			order: [[ 3, "desc" ]],
			columns: [
				        
				    {data: 'contact_no',orderable: false},
				    {data: 'call_by',orderable: false},
				    {data: 'extension',orderable: false},
				    {data: 'created_at',orderable: false},
				],
			});
	}

	

	load_ptp_histories();
	load_loan_histories();
	load_call_histories();

	function getStatus(group){
		$.ajax({
		        url: "/leads/get_group_status",
		        type: "POST",
		        data: 'group='+group+'&except=NEW',
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		        
			      $('#status').empty().append('<option value="">Select Account Status</option>');
			      
			      jQuery.each(data.get_data, function(k,val){     
	                  	$('#status').append($('<option>', { 
	                        value: val.name,
	                        text : val.name 
	                  }));
                  
                  });
		                                    
		       }
		 });
	}

	function getContactNo(profile_id){
		$.ajax({
		        url: "/leads/get_contact_no",
		        type: "POST",
		        data: 'profile_id='+profile_id,
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		        
			      $('#call_contact_no').empty();
			      
			      jQuery.each(data.get_data, function(k,val){     
	                  	$('#call_contact_no').append($('<option>', { 
	                        value: val.id,
	                        text : val.name 
	                  }));
                  
                  });
		                                    
		       }
		 });
	}

	

	// jQuery(document).on('click', '#dial_numbers-tab', function(e){
	// 	getContactNo('{{$crm_leads->profile_id}}');
	// });
	jQuery(document).on('click', '#open-new-entries', function(e){
		$("#add-new-entries-tab").click();
	});
	jQuery(document).on('click', '#open-call-again', function(e){
		$("#dial-numbers-tab").click();
		getContactNo('{{$crm_leads->profile_id}}');
	});

	jQuery(document).on('change', '#call_status', function(e){
		var div_ptp = `<div class="col-lg-6">
				                <label>PTP/Payment Date</label>
				                <div class="input-group date">
							      <input type="text" class="form-control" id="kt_datepicker_2" readonly name="payment_date"  placeholder="Select date"/>
							      <div class="input-group-append">
							       <span class="input-group-text">
							        <i class="la la-calendar-check-o"></i>
							       </span>
							      </div>
							     </div>
				                <span class="form-text text-muted">Optional</span>
				            </div>

				            <div class="col-lg-6">
				                <label>PTP/Payment Amount</label>
				                <input type='text' class="form-control" id="payment_amount" name="payment_amount" type="text"/>
				                <span class="form-text text-muted">Optional</span>
				            </div>`;
		var div_ptp_status = `<div class="col-lg-12">
				                <label>Account Status <span style="color:red;">*</span></label>
				                <select class="form-control select2" name="status" id="status" style="width: 100%;">
							    </select>
				                <span class="form-text text-muted"></span>
				            </div>`;


		// if($(this).val() == 'Answered'){

		// }else{
		// 	$("#div-show-ptp-fields").html('');
		// 	$("#div-show-ptp-status").html('');
		// }
		$("#div-show-ptp-fields").html(div_ptp);
			$("#div-show-ptp-status").html(div_ptp_status);

			$('#payment_amount').mask('000,000,000,000,000.00', {
			    reverse: true
			});

			$('#status').select2();
			getStatus('{{$crm_leads->assign_group}}');
			var date = new Date();
			date.setDate(date.getDate());

			$('#kt_datepicker_2').datepicker({
				rtl: KTUtil.isRTL(),
				todayHighlight: true,
				orientation: "bottom left",
				templates: arrows,
				startDate: date,
				locale: {
					format: 'YYYY-MM-DD'
				}
			  });
	});

	jQuery(document).on('change', '#status', function(e){
		var status_val = $(this).val();
		var div_ptp = `<div class="col-lg-6">
				                <label>PTP/Payment Date</label>
				                <div class="input-group date">
							      <input type="text" class="form-control" id="kt_datepicker_2" readonly name="payment_date"  placeholder="Select date"/>
							      <div class="input-group-append">
							       <span class="input-group-text">
							        <i class="la la-calendar-check-o"></i>
							       </span>
							      </div>
							     </div>
				                <span class="form-text text-muted">Optional</span>
				            </div>

				            <div class="col-lg-6">
				                <label>PTP/Payment Amount</label>
				                <input type='text' class="form-control" id="payment_amount" name="payment_amount" type="text"/>
				                <span class="form-text text-muted">Optional</span>
				            </div>`;

		if($(this).val() == 'BEST TIME TO CALL'){
			
			$("#div-show-ptp-fields").html('');
			$("#div-show-best-time").html(`<div class="col-lg-6">
				                <label>BEST TIME TO CALL</label>
				                <div class="input-group date" id="kt_datetimepicker_1" data-target-input="nearest">
								   <input type="text" name="best_time" class="form-control datetimepicker-input" placeholder="Select date & time" data-target="#kt_datetimepicker_1"/>
								   <div class="input-group-append" data-target="#kt_datetimepicker_1" data-toggle="datetimepicker">
								    <span class="input-group-text">
								     <i class="ki ki-calendar"></i>
								    </span>
								   </div>
								  </div>
				            </div>`);
			$('#kt_datetimepicker_1').datetimepicker({
				format: 'YYYY-MM-DD hh:mm A'
			});
		}else if(status_val.indexOf('PTP') > -1){
			$("#payment_amount").val('{{number_format($crm_leads->outstanding_balance,2)}}');
			
		}else{
			$("#div-show-best-time").html('');
			$("#div-show-ptp-fields").html(div_ptp);
			$('#payment_amount').mask('000,000,000,000,000.00', {
			    reverse: true
			});

			// $('.select2').select2();

			var date = new Date();
			date.setDate(date.getDate());

			   $('#kt_datepicker_2').datepicker({
			   rtl: KTUtil.isRTL(),
			   todayHighlight: true,
			   orientation: "bottom left",
			   templates: arrows,
			   startDate: date,
			   locale: {
					format: 'YYYY-MM-DD'
				}
			  });
			
		}
	});

	jQuery(document).on('switchChange.bootstrapSwitch', '#send_sms_ptp', function(event, state){
		  var html_div = '';
	      if(state == true){
			html_div += `<label>Send SMS</label><select id="mobile" name="mobile" class="form-control">`;
									@foreach ($sms_no_lists as $sms_no_list)
										@if($sms_no_list == '' || $sms_no_list == '--')
											@php
												continue;
											@endphp
										@endif
										html_div += `<option value="{{$sms_no_list}}">{{$sms_no_list}}</option>`;
									@endforeach
			html_div += `</select><span class="form-text text-muted"></span>`;
			$('#div-show-sms-mobile-ptp').html(html_div);
		  }else{
			$('#div-show-sms-mobile-ptp').html(html_div);
		  }
	    	
	});

	jQuery(document).on('click', '#btn_save_new_entry', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('add_new_entry_form'),
        {
         fields: {
         	place_call: {
                validators: {
                    notEmpty: {
                      message: 'Place of Call is required'
                    },
                }
            },
            contact_type: {
                validators: {
                    notEmpty: {
                      message: 'Contact Type is required'
                    },
                }
            },
            call_status: {
                validators: {
                    notEmpty: {
                      message: 'Call Status is required'
                    },
                }
            },
            status: {
                validators: {
                    notEmpty: {
                      message: 'Application Status is required'
                    },
                }
            },
            best_time: {
                validators: {
                    notEmpty: {
                      message: 'Best time to call is required'
                    },
                }
            },
            // payment_date: {
            //     validators: {
            //         notEmpty: {
            //           message: 'Payment Date is required'
            //         },
            //     }
            // },
            // payment_amount: {
            //     validators: {
            //         notEmpty: {
            //           message: 'Payment Amount is required'
            //         },
            //     }
            // },
            remarks: {
                validators: {
                    notEmpty: {
                      message: 'Remarks is required'
                    },
                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                      message: 'Mobile is required'
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
                    var time_spent = window.start_extend_timer();
                    var send_sms_ptp = $('#send_sms_ptp').bootstrapSwitch('state');
                	var send_email_ptp = $('#send_email_ptp').bootstrapSwitch('state');

                     $.ajax({
		                url: "/leads/store_ptp",
		                type: "POST",
		                data: $("#add_new_entry_form").serialize()+'&name={{$crm_leads->full_name}}&profile_id={{$crm_leads->profile_id}}&leads_id={{$crm_leads->id}}&group={{$crm_leads->assign_group}}&send_sms_ptp='+send_sms_ptp+'&send_email_ptp='+send_email_ptp+'&file_id={{$file_id}}&leads_status={{$crm_leads->status}}&audo_dialer={{$dialer_view_type}}&time_spent='+time_spent+'&status_updated={{$crm_leads->status_updated}}',
		                headers: {
		                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		                },
		                dataType: "JSON",
		                success: function(data){
		                	window.reset();
		                    KTApp.unblockPage();

		                    	KTApp.blockPage({
						            overlayColor: '#000000',
						            state: 'primary',
						            message: 'We are pulling the next account to you....'
						        });

			                    Swal.fire({
								    icon: "success",
								    title: data.msg,
								    showConfirmButton: false,
								    timer: 1500
								});

		                     if(data.error == 'false'){
		                        //$("#modal_add_entry").modal('toggle');
		                        @if(Request::is('agent_dialer/*') )
		                        	setTimeout(function(){
			                          window.location = "{{ route('pages.agent.auto_dialer', ['file_id' => $file_id]) }}";
			                       }, 100);
		                        @else

								    setTimeout(function(){
				                        location.reload();
				                    }, '{{env('DIALER_TIMECALL_ENGAGE')}}');

		                        @endif
		                        

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

	jQuery(document).on('click', '#save-personal-details', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('update_personal_details_form'),
        {
         fields: {
         	@if(env('FEATURE_EMAIL') == 'true')
            pd_email: {
                validators: {
                    notEmpty: {
                      message: 'Email is required'
                    },
                    emailAddress: {
						message: 'The value is not a valid email address'
					}
                }
            },
            @endif
                    
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
			                    text: 'Add atleast one field for the contact no of the account.',
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


                     KTApp.blockPage({
                      overlayColor: '#000000',
                      state: 'primary',
                      message: 'Processing...'
                     });


                     $.ajax({
		                url: "/leads/update_personal_details",
		                type: "POST",
		                data: $("#update_personal_details_form").serialize()+'&name={{$crm_leads->full_name}}&profile_id={{$crm_leads->profile_id}}&leads_id={{$crm_leads->id}}',
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

		                      				//location.reload();
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

	//intervalID = setInterval(function() { window.get_call_status_view(call_contact_no); }, 5000);

	get_call_status_view = function(contact_no){
		jQuery.ajax({
			url: "/agent_dialer/get_call_status_view",
			type: "POST",
			data: 'extension={{Auth::user()->extension}}&contact_no='+contact_no,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success: function(data) {
				console.log(data.asterisk_status);
				if(data.call_flag == '0'){
					$("#div-call-status-view").html(data.msg);
					$("#div-call-status-flag").html('');
					$("#btn_save_new_entry").prop('disabled',false);
					
					clearInterval(intervalID);
					
				}else if(data.status == 'Connecting' || data.status == 'Ringing'){
					$("#btn_save_new_entry").prop('disabled','disabled');
					$("#div-call-status-flag").html('<div class="alert alert-warning" role="alert">You cannot submit a new entry while your are engage in a call.</div>');
					$("#div-call-status-view").html(data.msg);

				}else{
					$("#div-call-status-view").html(data.msg);
					$("#div-call-status-flag").html('<div class="alert alert-warning" role="alert">You cannot submit a new entry while your are engage in a call.</div>');
					$("#btn_save_new_entry").prop('disabled','disabled');
					Swal.close();
				}
				
			},
			error: function(data){


			}
		}); 
	}


	jQuery(document).on('click', '#manual_call', function(e){
		var call_contact_no = $("#call_contact_no").val();
			KTApp.blockPage({
              overlayColor: '#000000',
              state: 'primary',
              message: 'Processing...'
            });

	 
            $.ajax({
		        url: "/agent_dialer/agent_call",
		        type: "POST",
		        data: 'contact_no='+call_contact_no+'&name={{$crm_leads->full_name}}&profile_id={{$crm_leads->profile_id}}&leads_id={{$crm_leads->id}}&file_id={{$file_id}}',
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		            KTApp.unblockPage();

		            if(data.error == 'false'){
		                
		                //window.get_call_status_view(call_contact_no);
		                // Swal.fire({
						// 	title: 'Connecting',
						// 	text: "",
						// 	timer: 20000,
						// 	   	onOpen: function() {
						// 	            Swal.showLoading()
						// 	        }
						// 	}).then(function(result) {
						// 	    if (result.dismiss === "timer") {
						// 	        console.log("I was closed by the timer")
						// 		}
						// })
		            	toastr.success("Please accept the confirmation call in your sofphone.");
		                intervalID = setInterval(function() { get_call_status_view(call_contact_no); }, 1000);      
		               	jQuery("#tbl_call_logs").dataTable()._fnAjaxUpdate();  
				        $("#add-new-entries-tab").click();
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


	jQuery(document).on('click', '#save-manual-numbers', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('add_manual_numbers_form'),
        {
         fields: {

            'field_name': {
                validators: {
                    notEmpty: {
                      message: 'Contact Type is required'
                    },
                }
            },
            'contact_no': {
                validators: {
                    notEmpty: {
                      message: 'Contact No. is required'
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
		                url: "/leads/store_manual_number",
		                type: "POST",
		                data: $("#add_manual_numbers_form").serialize()+'&file_id={{$file_id}}&profile_id={{$crm_leads->profile_id}}',
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