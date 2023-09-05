@extends('templates.default')
@section('title', 'Campaign '.$campaigns->campaign_name)
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
									<i class="fas fa-file-alt text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Campaign Realtime Report > {{$campaigns->campaign_name}}</h3>
							</div>
							<div class="card-toolbar">
								 <h3 class="card-label">Group: {{$group_name}}</h3>
							</div>
						</div>
						
						<div class="card-body">
							<div id="div-total-leads-check" class="alert alert-warning" role="alert" style="display: none;">
								Warning: Most of the account that assign on this campaign has been removed because there is a new uploaded leads. However, You can still add a new leads by going to Add Leads or Create New Campaign for new leads.
							</div>
							<div class="row">
									
									{{-- <div class="col-xl-3">
										<!--begin::Stats Widget 32-->
										<div class="card card-custom bg-dark card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<i class="fas fa-people-arrows icon-2x mr-5"></i>
												</span>
												<span id="demo-call_in_queue" class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block">0</span>
												<span class="font-weight-bold text-white font-size-sm">Call in Queue</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 32-->
									</div> --}}
									
									<div class="col-xl-3">
										<!--begin::Stats Widget 32-->
										<div class="card card-custom bg-dark card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<i class="fas fa-headset icon-2x mr-5"></i>
												</span>
												<span id="demo-agents_in_call" class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block">0</span>
												<span class="font-weight-bold text-white font-size-sm">Collectors In Call</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 32-->
									</div>

									<div class="col-xl-3">
										<!--begin::Stats Widget 32-->
										<div class="card card-custom bg-dark card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<i class="fas fa-phone icon-2x mr-5"></i>
												</span>
												<span id="demo-call_in_placed" class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block">0</span>
												<span class="font-weight-bold text-white font-size-sm">Call Being Placed</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 32-->
									</div>

									<div class="col-xl-3">
										<!--begin::Stats Widget 32-->
										<div class="card card-custom bg-dark card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<i class="fas fa-users icon-2x mr-5"></i>
												</span>
												<span id="demo-total_agents" class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block">0</span>
												<span class="font-weight-bold text-white font-size-sm">Total Collectors</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 32-->
									</div>

									<div class="col-xl-3">
										<!--begin::Stats Widget 32-->
										<div class="card card-custom bg-dark card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<i class="fas fa-user-alt icon-2x mr-5"></i>
												</span>
												<span id="demo-total_agent_logged_in" class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block">0</span>
												<span class="font-weight-bold text-white font-size-sm">Collectors Logged In</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 32-->
									</div>

									<div class="col-xl-3">
										<!--begin::Stats Widget 32-->
										<div class="card card-custom bg-dark card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<i class="fas fa-pause icon-2x mr-5"></i>
												</span>
												<span id="demo-total_agent_paused" class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block">0</span>
												<span class="font-weight-bold text-white font-size-sm">Collectors Paused</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 32-->
									</div>

							</div>

							<div class="row">

								<div class="col-xl-3">
									<button type="button" id="btn-edit-campaigns" class="btn btn-primary btn-block"><i class="far fa-edit"></i> Edit Campaign Details</button>
								</div>

								<div class="col-xl-3">
									<button type="button" id="btn-view-leads" class="btn btn-primary btn-block"><i class="fas fa-users"></i> Add Leads</button>
								</div>

								@if ($campaigns->auto_assign == 0)
									<div class="col-xl-3">
										<button type="button" id="btn-view-collectors" data-toggle="modal" data-target="#modal_add_collectors" class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i> Add Collectors</button>
									</div>
								@endif

								<div class="col-xl-3">
									<button type="button" id="btn-view-insights" class="btn btn-primary btn-block"><i class="fas fa-chart-line"></i> Insights</button>
								</div>

							</div>
							<hr>
							<div class="row">

								<div id="div_activate_campaign" class="col-xl-3">
									<button type="button" id="activate_campaign" class="btn btn-success btn-block" disabled="true"><i class="fas fa-play"></i> Activate Campaign</button>
								</div>

								<div id="div_disabled_campaign" class="col-xl-3" style="display: none;">
									<button type="button" id="disabled_campaign" class="btn btn-danger btn-block"><i class="fas fa-stop"></i> Disabled Campaign</button>
								</div>

								<div class="col-xl-3">
									<button type="button" id="pause_campaign" class="btn btn-secondary btn-block" disabled="true"><i class="fas fa-pause"></i> Pause Campaign</button>
								</div>
								
								<div class="col-xl-3">
									<button type="button" id="reset_campaign" class="btn btn-secondary btn-block"><i class="fas fa-undo"></i> Reset Campaign</button>
								</div>
								
							</div>
							
							
							
						</div>

					</div>
					<!--end::Card-->


				</div>
			</div>
		<!--end::Row-->
		<hr>
		<!--begin::Row-->
			<div class="row">
				{{-- <div class="col-lg-6">

					<!--begin::Card-->
					<div class="card card-custom">
						
						<div class="card-header">

							<div class="card-title">
								<span class="card-icon">
									<i class="fas fa-phone-volume text-primary"></i>
								</span>
								<h3 class="card-label">Live Calls in Campaign.</h3>
							</div>
							<div class="card-toolbar">
								 <a  href="#" class="btn btn-sm btn-light-primary font-weight-bold">
					                <i class="flaticon2-plus"></i> Add Group
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_active_campaign" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Status</th>
										<th>Name</th>
										<th>Contact No.</th>
									</tr>
								</thead>
							</table>
							<!--end: Datatable-->
							
						</div>

					</div>
					<!--end::Card-->


				</div> --}}

				<div class="col-lg-12">

					<!--begin::Card-->
					<div class="card card-custom">
						
						<div class="card-header">

							<div class="card-title">
								<span class="card-icon">
									<i class="fas fa-users text-primary"></i>
								</span>
								<h3 class="card-label">Collectors In Campaign</h3>
							</div>
							<div class="card-toolbar">
								 {{-- <a  href="#" class="btn btn-sm btn-light-primary font-weight-bold">
					                <i class="flaticon2-plus"></i> Add Group
					            </a> --}}
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_campaign_collectors" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>View Assign Accounts</th>
										<th>Name</th>
										<th>State</th>
										<th>Voice Monitoring</th>
									</tr>
								</thead>
							</table>
							<!--end: Datatable-->
							
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
<div class="modal fade" id="modal_add_collectors" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Collectors</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidde	n="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <select id="listbox_collectors" class="dual-listbox" multiple="multiple">
                	@foreach ($list_users as $list_user)
                		<option value="{{$list_user->id}}" @if(in_array($list_user->id, $array_collectors)) selected @endif>{{$list_user->name}}</option>
                	@endforeach
				</select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" id="btn-add-collectors" class="btn btn-primary font-weight-bold">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {
	
	var _this = document.getElementById('listbox_collectors');

        // init dual listbox
        var dualListBox = new DualListbox(_this, {
            addEvent: function (value) {
                console.log(value);
            },
            removeEvent: function (value) {
                console.log(value);
            },
            availableTitle: 'Available Collectors',
            selectedTitle: 'Selected Collectors',
            addButtonText: 'Add',
            removeButtonText: 'Remove',
            addAllButtonText: 'Add All',
            removeAllButtonText: 'Remove All'
    });


    function get_table_colletors_campaign(){
		var tbl_campaign_collectors = $('#tbl_campaign_collectors');
		tbl_campaign_collectors.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: false,
			pageLength: 10,
			lengthChange: false,
			ajax: {
				    url: "/auto_dialer/getCampaignCollectors",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
				          // parameters for custom backend script demo
				            "file_id" : '{{$file_id}}',
				            "group" : '{{$campaigns->group}}',
				       },
				  },
			columns: [
					{data: 'action',orderable: false},
					{data: 'name',orderable: false},
				    {data: 'status',orderable: false},   
				    {data: 'monitoring',orderable: false},
				],
			columnDefs: [
				{
					targets: 0,
					width: '30px',
					className: 'dt-left',
					orderable: false,
					render: function(data, type, full, meta) {
						
						console.log(full.name);
						var link = '{{route('pages.auto_dialer.view_assign', ['id' => ':data', 'file_id' => ':file_id'])}}';
							link = link.replace(':data', data);
							link = link.replace(':file_id', full.file_id);
						return `<a href="`+link+`" class="btn btn-secondary btn-sm btn-block view_assign"><i class="fas fa-info-circle"></i></a>`;
					},
				},
				],
			});
	}

	// jQuery(document).on('click', '.view_assign', function(e){
	// 	var id = $(this).attr('id');
	// 	$( "#moda_body_"+id ).load( "/auto_dialer/view_assign/"+id, function() {});
	// });

    function get_demographics_leads(){
			jQuery.ajax({
		          url: "/auto_dialer/get_total_added_leads",
		          type: "POST",
		          data: 'file_id={{$file_id}}',
		          headers: {
			             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			            },
		          dataType: "json",
		          success: function(data) {
		          	 KTApp.unblockPage();
		          	 $("#demo-total_agents").html(data.total_agents);
		          	 $("#demo-call_in_queue").html(0);
		          	 $("#demo-agents_in_call").html(data.total_agents_in_call);
		          	 $("#demo-call_in_placed").html(data.total_dials);
		          	 $("#demo-total_agent_logged_in").html(data.total_agents_logged_in);
		          	 $("#demo-total_agent_paused").html(data.total_agents_paused);

		          	 if(data.total_leads > 0){
		          	 	$("#activate_campaign").prop('disabled', false);//enabled btn activate campaign
		          	 }else{
		          	 	$("#activate_campaign").prop('disabled', 'disabled');//disabled btn activate campaign
		          	 }

					 if(data.total_current_leads > 0){
						$("#div-total-leads-check").css('display', '');//show the warning for removed leads
					 }

		          	 if(data.active_dialer == 1){
		          	 	$("#div_activate_campaign").css('display', 'none');//hide btn activate campaign
		          	 	$("#btn-view-collectors").prop('disabled', 'disabled');//disbled btn add collectors
		          	 	$("#btn-view-leads").prop('disabled', 'disabled');//disabled btn add leads
		          	 	$("#btn-edit-campaigns").prop('disabled', 'disabled');//disabled btn edit campaign

		          	 	$("#div_disabled_campaign").css('display', '');//show btn disabled campaign
		          	 	//$("#link1").removeAttr('href');
		          	 	$("#pause_campaign").prop('disabled', false);//enabled btn pause campaign
		          	 }else if(data.active_dialer == 2){
		          	 	$("#div_activate_campaign").css('display', 'none');
		          	 	$("#div_disabled_campaign").css('display','none');
		          	 	$("#pause_campaign").prop('disabled', false);
		          	 	$("#pause_campaign").html('<i class="fas fa-pause"></i> Unpaused');
		          	 }else{
		          	 	$("#pause_campaign").prop('disabled', 'disabled');
		          	 }

		          },
		          error: function(data){


		          }
		    });
	}

	 function myTimer() {
	  get_demographics_leads();
	  jQuery("#tbl_campaign_collectors").dataTable()._fnAjaxUpdate();
	}
	get_demographics_leads();
	get_table_colletors_campaign();
	timer_val = setInterval(myTimer, 20000);


	jQuery(document).on('click', '#btn-edit-campaigns', function(e){
		var link = '{{route('pages.auto_dialer.edit', ['file_id' => $file_id])}}';
		window.location = link;  
	});

	jQuery(document).on('click', '#btn-view-leads', function(e){
		var link = '{{route('pages.auto_dialer.add_leads', ['file_id' => $file_id])}}';
		window.location = link;  
	});

	jQuery(document).on('click', '#btn-view-insights', function(e){
		var link = '{{route('pages.auto_dialer.insights', ['file_id' => $file_id])}}';
		window.location = link;  
	});

	

    jQuery(document).on('click', '#btn-add-collectors', function(e){
    	e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to add this selected collectors to campaigns?",
	        text: "",
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
	         	var listbox_collectors = $("#listbox_collectors").val();
				$.ajax({
				    url: "/auto_dialer/add_collectors_to_campaign",
				    type: "POST",
				    data: 'file_id={{$file_id}}&listbox_collectors='+listbox_collectors+'&campaign_name={{$campaigns->campaign_name}}',
				        headers: {
				           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    dataType: "JSON",
				    success: function(data){
				         KTApp.unblockPage();
				        
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
				                                    
				    }
				});

				

            	

		             
	        }
	    });
    });


    jQuery(document).on('click', '#activate_campaign', function(e){
    	e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to activate the campaigns?",
	        text: "",
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
				    url: "/auto_dialer/activate_campaign",
				    type: "POST",
				    data: 'file_id={{$file_id}}&campaign_name={{$campaigns->campaign_name}}&auto_assign={{$campaigns->auto_assign}}',
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

		                            	$("#div_disabled_campaign").css('display','');
		                    			$("#div_activate_campaign").css('display','none');

		                    			$("#btn-view-collectors").prop('disabled', 'disabled');
						          	 	$("#btn-view-leads").prop('disabled', 'disabled');
						          	 	$("#btn-edit-campaigns").prop('disabled', 'disabled');
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


    jQuery(document).on('click', '#disabled_campaign', function(e){
    	e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to disabled the campaigns?",
	        text: "",
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
				    url: "/auto_dialer/disabled_campaign",
				    type: "POST",
				    data: 'file_id={{$file_id}}&campaign_name={{$campaigns->campaign_name}}',
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

		                            	$("#div_disabled_campaign").css('display','none');
		                    			$("#div_activate_campaign").css('display','');
		                    			$("#btn-view-collectors").prop('disabled', false);
						          	 	$("#btn-view-leads").prop('disabled', false);
						          	 	$("#btn-edit-campaigns").prop('disabled', false);

						          	 	
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

    jQuery(document).on('click', '#pause_campaign', function(e){
    	e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to pause this campaign?",
	        text: "If you click yes, the collector will not receive next account leads, However the ongoing call will not be interrupt .",
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
				    url: "/auto_dialer/pause_campaign",
				    type: "POST",
				    data: 'file_id={{$file_id}}&campaign_name={{$campaigns->campaign_name}}&active_dialer={{$campaigns->active_dialer}}',
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


    jQuery(document).on('click', '#reset_campaign', function(e){
    	e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to reset this campaign?",
	        text: "If you click yes, all the added leads and assign collectors will remove.",
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
				    url: "/auto_dialer/reset_campaign",
				    type: "POST",
				    data: 'file_id={{$file_id}}&campaign_name={{$campaigns->campaign_name}}',
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

    jQuery(document).on('click', '.btn-listen', function(e){
    	e.preventDefault();
    	var dial_plan = $(this).attr('id');
    	Swal.fire({
	        title: "Are you sure you want to listen in the call?",
	        text: "",
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
				    url: "/auto_dialer/manager_call",
				    type: "POST",
				    data: 'file_id={{$file_id}}&campaign_name={{$campaigns->campaign_name}}&dial_plan='+dial_plan,
				        headers: {
				           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    dataType: "JSON",
				    success: function(data){
				        KTApp.unblockPage();


				        
				
				                                    
				    }
				});

				

            	

		             
	        }
	    });
    });

    jQuery(document).on('click', '.btn-whisper', function(e){
    	e.preventDefault();
    	var dial_plan = $(this).attr('id');
    	Swal.fire({
	        title: "Are you sure you want to whisper in the call?",
	        text: "",
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
				    url: "/auto_dialer/manager_call",
				    type: "POST",
				    data: 'file_id={{$file_id}}&campaign_name={{$campaigns->campaign_name}}&dial_plan='+dial_plan,
				        headers: {
				           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    dataType: "JSON",
				    success: function(data){
				        KTApp.unblockPage();


				        
				
				                                    
				    }
				});

				

            	

		             
	        }
	    });
    });

    jQuery(document).on('click', '.btn-barge', function(e){
    	e.preventDefault();
    	var dial_plan = $(this).attr('id');
    	Swal.fire({
	        title: "Are you sure you want to barge in the call?",
	        text: "",
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
				    url: "/auto_dialer/manager_call",
				    type: "POST",
				    data: 'file_id={{$file_id}}&campaign_name={{$campaigns->campaign_name}}&dial_plan='+dial_plan,
				        headers: {
				           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    dataType: "JSON",
				    success: function(data){
				        KTApp.unblockPage();


				        
				
				                                    
				    }
				});

				

            	

		             
	        }
	    });
    });

    		

});
</script>
@endpush