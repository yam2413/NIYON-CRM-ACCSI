@extends('templates.default')
@section('title', $crm_leads->full_name.' | Leads Account')
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

					<div class="card card-custom card-sticky" id="kt_page_sticky_card">
						<div class="card-header">
							<div class="card-title">
								<span class="card-icon">
									@if($crm_leads->priority == 1) <i class="fas fa-exclamation-circle text-warning mr-5" data-toggle="tooltip" data-theme="dark" title="This account is priority"></i> @endif
									<i class="fas fa-user text-primary"></i>
								</span>
								<h3 class="card-label">
									{{ucfirst($crm_leads->full_name)}}
									<small>
										<span class="label label-xl label-secondary label-inline mr-2" data-toggle="tooltip" data-theme="dark" title="Promise to Pay">{{$crm_leads->status}}</span>
										
									</small>
								</h3>
							</div>

							<div class="card-toolbar">
									<ul class="nav nav-tabs nav-bold nav-tabs-line">
										<li class="nav-item">
											 <a href="{{ route('pages.leads.index') }}" class="btn btn-light-primary font-weight-bolder mr-2" >
												<i class="fas fa-long-arrow-alt-left"></i> Back
											 </a>
										</li>
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_3">
												<span class="nav-icon">
													<i class="fas fa-user-tag"></i>
												</span>
												<span class="nav-text">Loan Details</span>
											</a>
										</li>

										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2_3">
												<span class="nav-icon">
													<i class="fas fa-file-import"></i>
												</span>
												<span class="nav-text">Fields Uploaded</span>
											</a>
										</li>

										<li class="nav-item dropdown">
											<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
												<span class="nav-icon">
													<i class="flaticon2-gear"></i>
												</span>
												<span class="nav-text">Other</span>
											</a>
											<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
												<a class="dropdown-item" data-toggle="tab" href="#kt_tab_pane_3_3">
													<i class="fas fa-mobile-alt"></i>&nbsp;Personal Details
												</a>
												<a class="dropdown-item" data-toggle="tab" href="#kt_tab_pane_4_3">
													<i class="fas fa-piggy-bank"></i>&nbsp;Loan History
												</a>
												<a class="dropdown-item" data-toggle="tab" href="#kt_tab_pane_5_3">
													<i class="fas fa-blender-phone"></i>&nbsp;Call Logs
												</a>
												<a class="dropdown-item" data-toggle="tab" href="#kt_tab_pane_6_3">
													<i class="fas fa-mobile-alt"></i>&nbsp;Manual Numbers
												</a>
												
											</div>
										</li>

										
										<li class="nav-item dropdown">
											<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
												<span class="nav-icon">
													<i class="fas fa-ellipsis-h"></i>
												</span>
											</a>
											<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#kt_call_modal">
													<i class="fas fa-phone-alt"></i>&nbsp;Call
												</a>

												@if(env('FEATURE_SMS') == 'true')
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal_send_sms">
													<i class="fab fa-telegram-plane"></i>&nbsp;Send SMS
												</a>
												@endif

												@if(env('FEATURE_EMAIL') == 'true')
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal_send_email">
													<i class="far fa-envelope"></i>&nbsp;Send Email
												</a>
												@endif

												<div class="dropdown-divider"></div>
												@if(Auth::user()->level != '3')
													<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal_reassign">
														<i class="fas fa-user-minus"></i>&nbsp;Re-assign
													</a>
												

													@if($crm_leads->priority == 1)
														<a class="dropdown-item" id="btn-remove-as-priority" href="#">
															<i class="fas fa-exclamation-circle"></i>&nbsp;Remove as Priority
														</a>
													@else
														<a class="dropdown-item" id="btn-set-as-priority" href="#">
															<i class="fas fa-exclamation-circle"></i>&nbsp;Set as Priority
														</a>
													@endif

												@endif
												
											</div>
										</li>
								</ul>
							</div>
							

						</div>
						
						<div class="card-body">
							<small>Collector: {{ Auth::user()->getName($crm_leads->assign_user) }}</small><br>
							<small>Group: {{ \App\Models\Groups::usersGroup($crm_leads->assign_group) }}</small><br>
							<small>Leads ID: {{$crm_leads->profile_id}}</small> | <small>File ID: {{$crm_leads->file_id}}</small>
							<div class="separator separator-solid separator-border-2 separator-dark"></div>
							<div class="tab-content">
								<div class="tab-pane fade show active" id="kt_tab_pane_1_3" role="tabpanel" aria-labelledby="kt_tab_pane_1_3">

									@include('pages.leads.ptp')
								</div>
								<div class="tab-pane fade" id="kt_tab_pane_2_3" role="tabpanel" aria-labelledby="kt_tab_pane_2_3">
									@include('pages.leads.other_uploaded')
									
								</div>
								<div class="tab-pane fade" id="kt_tab_pane_3_3" role="tabpanel" aria-labelledby="kt_tab_pane_3_3">
									
									@include('pages.leads.personal_details')

								</div>

								<div class="tab-pane fade" id="kt_tab_pane_4_3" role="tabpanel" aria-labelledby="kt_tab_pane_4_3">
									
									@include('pages.leads.loan_history')

								</div>


								<div class="tab-pane fade" id="kt_tab_pane_5_3" role="tabpanel" aria-labelledby="kt_tab_pane_5_3">
									
									@include('pages.leads.call_logs')

								</div>

								<div class="tab-pane fade" id="kt_tab_pane_6_3" role="tabpanel" aria-labelledby="kt_tab_pane_6_3">
									
									@include('pages.agent.tab_leads.manual_numbers')

								</div>


							</div>
						</div>
						<div class="card-footer d-flex justify-content-between">
							{{-- <a href="#" class="btn btn-light-primary font-weight-bold">Manage</a>
							<a href="#" class="btn btn-outline-secondary font-weight-bold">Learn more</a> --}}
						</div>
					</div>


				</div>
			</div>
		<!--end::Row-->


	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->

<!--begin::Sticky Toolbar-->
{{-- <ul class="sticky-toolbar nav flex-column pl-2 pr-2 pt-3 pb-3 mt-4">
	<!--begin::Item-->
	<li class="nav-item" id="kt_sticky_toolbar_call_toggler" data-toggle="tooltip" title="Call Active" data-placement="left">
		<a class="btn btn-sm btn-icon btn-bg-light btn-icon-danger btn-hover-danger" href="#" data-toggle="modal" data-target="#kt_call_modal">
			<i class="fas fa-phone-volume"></i>
		</a>
	</li>
			<!--end::Item-->
</ul> --}}
<!--end::Sticky Toolbar-->


<!--begin::Chat Panel-->
		<div class="modal modal-sticky modal-sticky-bottom-right" id="kt_call_modal" role="dialog" data-backdrop="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<!--begin::Card-->
					<div class="card card-custom">
						<!--begin::Header-->
						<div class="card-header align-items-center px-4 py-3">
							<div class="text-left flex-grow-1">
								<!--begin::Dropdown Menu-->
								<div class="dropdown dropdown-inline">
									<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</button>
									<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-md">
										
									</div>
								</div>
								<!--end::Dropdown Menu-->
							</div>
							<div class="text-center flex-grow-1">
								<div class="text-dark-75 font-weight-bold font-size-h5">{{$crm_leads->full_name}}</div>
								<div> 
									<span class="label label-dot label-success"></span>
									Extension No: 
									<span class="font-weight-bold text-muted font-size-sm">{{ Auth::user()->extension }}</span>
								</div>
							</div>
							<div class="text-right flex-grow-1">
								<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-dismiss="modal">
									<i class="ki ki-close icon-1x"></i>
								</button>
							</div>
						</div>
						<!--end::Header-->
						
						<!--begin::Footer-->
						<div class="card-footer align-items-center">
							<!--begin::Compose-->
							<select id="call_contact_no" class="form-control">
								@foreach ($contact_no_lists as $contact_no_list)
									@if($contact_no_list == '' || $contact_no_list == '--')
										@php
											continue;
										@endphp
									@endif
									<option value="{{$contact_no_list}}">{{$contact_no_list}}</option>
								@endforeach
							</select>
							<div class="d-flex align-items-center justify-content-between mt-5">
								<div class="mr-3">
									<a href="#" class="btn btn-clean btn-md mr-1" data-toggle="modal" data-target="#modal_add_entry">
										<u>New Entry</u>
									</a>
									{{-- <a href="#" class="btn btn-clean btn-icon btn-md">
										<i class="flaticon2-photo-camera icon-lg"></i>
									</a> --}}
								</div>
								<div>
									<button type="button" id="manual_call" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6"><i class="fas fa-phone-alt"></i> Call</button>
								</div>
							</div>
							<!--begin::Compose-->
						</div>
						<!--end::Footer-->
					</div>
					<!--end::Card-->
				</div>
			</div>
		</div>
		<!--end::Chat Panel-->	

<!-- Modal-->
<div class="modal fade" id="modal_send_sms" tabindex="-1" role="dialog" aria-labelledby="modal_send_sms" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    	<form class="form" id="send_sms_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Send SMS</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                 <div class="mb-2">

		                  <div class="form-group row">

	                          <div class="col-lg-12">
	                            <label>Send To *</label>
	                             <select id="sms_mobile" name="sms_mobile" class="form-control">
									@foreach ($sms_no_lists as $sms_no_list)
										@if($sms_no_list == '' || $sms_no_list == '--')
											@php
												continue;
											@endphp
										@endif
										<option value="{{$sms_no_list}}">{{$sms_no_list}}</option>
									@endforeach
								</select>
	                             <span class="form-text text-muted"></span>
	                           </div>

	                      </div>

	                      <div class="form-group row">

	                          <div class="col-lg-12">
	                            <label>Body *</label>
	                             <textarea name="sms_body" class="form-control" rows="6" readonly="readonly">@if($temp_emails) {{$sms_msg}} @endif</textarea>
	                             <span class="form-text text-muted">SMS Message can not be edited. Only the Manager level can update the default SMS message</span>
	                           </div>

	                      </div>


	              	</div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_send_sms" class="btn btn-primary font-weight-bold">Send SMS</button>
	            </div>
	        </div>
	    </form>
    </div>
</div>

<!-- Modal-->
<div class="modal fade" id="modal_send_email" tabindex="-1" role="dialog" aria-labelledby="modal_send_email" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    	<form class="form" id="send_email_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                 <div class="mb-2">

		                  <div class="form-group row">

	                          <div class="col-lg-12">
	                            <label>To *</label>
	                             <input type="text" class="form-control" name="borrower_email" value="{{$crm_leads->email}}" readonly="readonly">
	                             <span class="form-text text-muted"></span>
	                           </div>

	                      </div>

	                      <div class="form-group row">

	                          <div class="col-lg-12">
	                            <label>Subject *</label>
	                             <input type="text" class="form-control" name="email_subject" value="{{$email_subject}}" readonly="readonly">
	                             <span class="form-text text-muted">Email Subject can not be edited. Only the Manager level can update the default email subject</span>
	                           </div>

	                      </div>

	                      <div class="form-group row">

	                          <div class="col-lg-12">
	                            <label>Body *</label>
	                             <textarea id="kt-tinymce-2" name="email_body" class="tox-target">@if($temp_emails) {{$email_msg}} @endif</textarea>
	                             <span class="form-text text-muted">Email Body can not be edited. Only the Manager level can update the default email message</span>
	                           </div>

	                      </div>


	              	</div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_send_email" class="btn btn-primary font-weight-bold">Send Email</button>
	            </div>
	        </div>
	    </form>
    </div>
</div>


<!-- Modal-->
<div class="modal fade" id="modal_reassign" tabindex="-1" role="dialog" aria-labelledby="modal_reassign" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    	<form class="form" id="reassign_accounts_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Re-assign</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                 <div class="mb-2">

		                  <div class="form-group row">

	                          <div class="col-lg-12">
	                            <label>Re-Assign User *</label>
	                             <select class="form-control select2" name="reassign" style="width: 100%;">
				                	<option value="">Select Collector</option>
				                	@foreach ($users as $user)
				                		<option value="{{$user->id}}">{{$user->name}}</option>
				                	@endforeach
				                </select>
	                             <span class="form-text text-muted">Only the user with the same group can re-assign.</span>
	                           </div>

	                      </div>
	              	</div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_submit_reassign_user" class="btn btn-primary font-weight-bold">Re-assign</button>
	            </div>
	        </div>
	    </form>
    </div>
</div>
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

$('[data-switch=true]').bootstrapSwitch();
$('.select2').select2();

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

// $("#payment_amount").inputmask('₱ 999,999,999.99', {
//    numericInput: true
//   }); //123456  =>  € ___.__1.234,56

@if(env('DEMO_STATUS') == 'true')
	@php
		$date_demo = new DateTime(env('DEMO_DATE'));
		$now_demo = new DateTime();

	@endphp
	@if($date_demo < $now_demo)
		$('#save-personal-details').prop('disabled', 'disabled');
		$('#btn_submit_reassign_user').prop('disabled', 'disabled');
		$('#btn_send_email').prop('disabled', 'disabled');
		$('#btn_send_sms').prop('disabled', 'disabled');
		$('#manual_call').prop('disabled', 'disabled');
		$('#btn_save_new_entry').prop('disabled', 'disabled');
	@endif
@endif


tinymce.init({
    selector: '#kt-tinymce-2'
});

tinymce.get('kt-tinymce-2').setMode('readonly');


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


	jQuery(document).on('click', '#manual_call', function(e){
		var call_contact_no = $("#call_contact_no").val();
			KTApp.blockPage({
              overlayColor: '#000000',
              state: 'primary',
              message: 'Processing...'
            });

	 
            $.ajax({
		        url: "/leads/manual_call",
		        type: "POST",
		        data: 'contact_no='+call_contact_no+'&name={{$crm_leads->full_name}}&profile_id={{$crm_leads->profile_id}}&leads_id={{$crm_leads->id}}',
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		            KTApp.unblockPage();

		            if(data.error == 'false'){
		                jQuery("#tbl_call_logs").dataTable()._fnAjaxUpdate();  
		                $("#modal_add_entry").modal('toggle');        
		                // swal.fire({
		                //     text: data.msg,
		                //     icon: "success",
		                //     buttonsStyling: false,
		                //     confirmButtonText: "Ok, got it!",
		                //     customClass: {
		                //         confirmButton: "btn font-weight-bold btn-light-primary"
		                //     },onClose: function(e) {

		                //     }
		                //    }).then(function() {
		                //         KTUtil.scrollTop();
		                //    });
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


	jQuery(document).on('click', '#btn-set-as-priority', function(e){

		 Swal.fire({
	        title: "Are you sure to tag this account as priority?",
	        text: "You won't be able to revert this!",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes"
	    }).then(function(result) {
	        if (result.value) {

	        	$.ajax({
		            url: "/leads/set_as_priority",
		            type: "POST",
		            data: "profile_id={{$crm_leads->profile_id}}&name={{$crm_leads->full_name}}&flag=1&leads_id={{$crm_leads->id}}",
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


	jQuery(document).on('click', '#btn-remove-as-priority', function(e){

		 Swal.fire({
	        title: "Are you sure to remove this account as priority?",
	        text: "You won't be able to revert this!",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes"
	    }).then(function(result) {
	        if (result.value) {

	        	$.ajax({
		            url: "/leads/set_as_priority",
		            type: "POST",
		            data: "profile_id={{$crm_leads->profile_id}}&name={{$crm_leads->full_name}}&flag=0&leads_id={{$crm_leads->id}}",
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

	jQuery(document).on('change', '#call_status', function(e){
		var div_ptp = `<div class="col-lg-6">
				                <label>PTP/Payment Date </label>
				                <div class="input-group date">
							      <input type="text" class="form-control" id="kt_datepicker_2" readonly name="payment_date"  placeholder="Select date"/>
							      <div class="input-group-append">
							       <span class="input-group-text">
							        <i class="la la-calendar-check-o"></i>
							       </span>
							      </div>
							     </div>
				                <span class="form-text text-muted"></span>
				            </div>

				            <div class="col-lg-6">
				                <label>PTP/Payment Amount </label>
				                <input type='text' class="form-control" id="payment_amount" name="payment_amount" type="text" value=""/>
				                <span class="form-text text-muted"></span>
				            </div>`;
		var div_ptp_status = `<div class="col-lg-12">
				                <label>Account Status <span style="color:red;">*</span></label>
				                <select class="form-control select2" name="status" id="status" style="width: 100%;">
							    </select>
				                <span class="form-text text-muted"></span>
				            </div>
				            `;


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

			$('.select2').select2();
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

                    var send_sms_ptp = $('#send_sms_ptp').bootstrapSwitch('state');
                	var send_email_ptp = $('#send_email_ptp').bootstrapSwitch('state');

                     $.ajax({
		                url: "/leads/store_ptp",
		                type: "POST",
		                data: $("#add_new_entry_form").serialize()+'&name={{$crm_leads->full_name}}&profile_id={{$crm_leads->profile_id}}&leads_id={{$crm_leads->id}}&group={{$crm_leads->assign_group}}&send_sms_ptp='+send_sms_ptp+'&send_email_ptp='+send_email_ptp+'&leads_status={{$crm_leads->status}}&time_spent=0&status_updated={{$crm_leads->status_updated}}',
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


	jQuery(document).on('click', '#btn_submit_reassign_user', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('reassign_accounts_form'),
        {
         fields: {
         	reassign: {
                validators: {
                    notEmpty: {
                      message: 'Re-Assign User is required'
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
		                url: "/leads/update_reassign",
		                type: "POST",
		                data: $("#reassign_accounts_form").serialize()+'&name={{$crm_leads->full_name}}&profile_id={{$crm_leads->profile_id}}&leads_id={{$crm_leads->id}}',
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

	

	jQuery(document).on('click', '#btn_send_sms', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('send_sms_form'),
        {
         fields: {
         	sms_mobile: {
                validators: {
                    notEmpty: {
                      message: 'Mobile no. is required'
                    },
                }
            },
            sms_body: {
                validators: {
                    notEmpty: {
                      message: 'Message body is required'
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
		                url: "/leads/send_sms",
		                type: "POST",
		                data: $("#send_sms_form").serialize()+'&name={{$crm_leads->full_name}}&profile_id={{$crm_leads->profile_id}}&leads_id={{$crm_leads->id}}',
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

	jQuery(document).on('click', '#btn_send_email', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('send_email_form'),
        {
         fields: {
         	borrower_email: {
                validators: {
                    notEmpty: {
                      message: 'Borrower Email is required'
                    },
                }
            },
            email_subject: {
                validators: {
                    notEmpty: {
                      message: 'Subject is required'
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
		                url: "/leads/send_email",
		                type: "POST",
		                data: $("#send_email_form").serialize()+'&name={{$crm_leads->full_name}}&profile_id={{$crm_leads->profile_id}}&leads_id={{$crm_leads->id}}',
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

	jQuery(document).on('click', '#button_ask_to_call', function(e){
	    e.preventDefault();
	    Swal.fire({
	        title: "Do you want to start dialling before you add new entry?",
	        icon: "info",
	        showCancelButton: true,
	        confirmButtonText: "<i class='fas fa-plus'></i> New Entry",
	        cancelButtonText: "<i class='fas fa-phone-alt'></i> Start Dialling",
	        reverseButtons: true
	    }).then(function(result) {
	        if (result.value) {
	            $("#modal_add_entry").modal("toggle");
	        } else if (result.dismiss === "cancel") {
	        	$("#kt_call_modal").modal('toggle');
	           
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
		                data: $("#add_manual_numbers_form").serialize()+'&profile_id={{$crm_leads->profile_id}}',
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