@extends('pages.agent.home')
@section('auto_dialer')

<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Profile 4-->
		<div class="d-flex flex-row">
			<!--begin::Aside-->
			<div class="flex-row-auto offcanvas-mobile w-200px w-xl-250px" id="kt_profile_aside">
										<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<!--begin::Body-->
					<div class="card-body pt-4">
												
						<!--begin::User-->
						<div class="d-flex align-items-center">
							<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
								@if(Auth::user()->avatar != NULL)
									<div class="symbol-label" style="background-image:url('{{ asset(Storage::url(Auth::user()->avatar)) }}')"></div>
								@else
									<div class="symbol-label" style="background-image:url('{{ asset('media/users/default.jpg') }}')"></div>
								@endif
								<i class="symbol-badge bg-success"></i>
							</div>
							<div>
								<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ Auth::user()->name }}</a>
								<div class="text-muted">{{Auth::user()->usersRole(Auth::user()->level)}}</div>
							</div>
						</div>
						<!--end::User-->
												
						<!--begin::Contact-->
						<div class="pt-8 pb-6">
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Group:</span>
								<a href="#" class="text-muted text-hover-primary">{{$group_name}}</a>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Campaign:</span>
								<span class="text-muted">{{$campaigns->campaign_name}}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Start Time:</span>
								<span class="text-muted">{{$campaigns->start_time}}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">End Time:</span>
								<span class="text-muted">{{$campaigns->end_time}}</span>
							</div>
						</div>
						<!--end::Contact-->
						
						<!--begin::Contact-->
						{{-- <div class="pb-6">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical.</div> --}}
						<!--end::Contact-->
						@if(env('DIALER_COLL_PAUSE') == 'true')
						<button type="button" id="btn-campaign-pause" class="btn btn-secondary font-weight-bold py-3 px-6 mb-2 text-center btn-block">
						Pause
						</button>
						<button type="button" id="btn-campaign-unpaused" style="display: none;" class="btn btn-light-warning font-weight-bold py-3 px-6 mb-2 text-center btn-block">
						Unpause
						</button>
						@endif

						<button type="button" id="btn-campaign-break-time" class="btn btn-secondary font-weight-bold py-3 px-6 mb-2 text-center btn-block">Take a Break</button>
						<button type="button" id="btn-campaign-logout" class="btn btn-light-danger font-weight-bold py-3 px-6 mb-2 text-center btn-block">Logout</button>
					</div>
					<!--end::Body-->
				</div>
				<!--end::Card-->
				
				<!--begin::Mixed Widget 14-->
				<div class="card card-custom gutter-b">
					<input type="hidden" id="display-area">
					<!--begin::Header-->
					<div class="card-header border-0 pt-5">
						<h3 class="card-title font-weight-bolder">Search Previous Accounts</h3>
					</div>
					<!--end::Header-->

					<!--begin::Body-->
					<div class="card-body d-flex flex-column">
						<div class="flex-grow-1">
							 <select class="form-control select2" id="kt_account_infor" name="kt_account_infor">
                   
               </select>
							
						</div>
						
						{{-- <div class="pt-5">
							<p class="text-center font-weight-normal font-size-lg pb-7">Notes: Current sprint requires stakeholders
							<br />to approve newly amended policies</p>
							<a href="#" class="btn btn-success btn-shadow-hover font-weight-bolder w-100 py-3">Generate Report</a>
						</div> --}}
					</div>
					<!--end::Body-->

				</div>
				<!--end::Mixed Widget 14-->

				<!--begin::Mixed Widget 14-->
				<div class="card card-custom gutter-b">
					<!--begin::Header-->
					<div class="card-header border-0 pt-5">
						<h3 class="card-title font-weight-bolder">BEST TIME TO CALL</h3>
					</div>
					<!--end::Header-->

					<!--begin::Body-->
					<div class="card-body d-flex flex-column">
						<div class="flex-grow-1" id="div-best-time-call-notif">

							
							 
							
						</div>
						
						{{-- <div class="pt-5">
							<p class="text-center font-weight-normal font-size-lg pb-7">Notes: Current sprint requires stakeholders
							<br />to approve newly amended policies</p>
							<a href="#" class="btn btn-success btn-shadow-hover font-weight-bolder w-100 py-3">Generate Report</a>
						</div> --}}
					</div>
					<!--end::Body-->

				</div>
				<!--end::Mixed Widget 14-->

			</div>
			<!--end::Aside-->


			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">
										
				<!--begin::Advance Table Widget 8-->
				<div class="card card-custom gutter-b">
					<!--begin::Header-->
					<div class="card-header border-0 py-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label font-weight-bolder text-dark">Collector Screen</span>
						</h3>
						
						<div class="card-toolbar" id="div-call-status-view">
							<a href="#" class="btn btn-secondary font-weight-bolder font-size-sm" >
								My Extension No: {{ Auth::user()->extension }}
							</a>
						</div>
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body pt-0 pb-3" id="div-load-leads">			
												
					</div>
					<!--end::Body-->
				</div>
				<!--end::Advance Table Widget 8-->
			</div>
			<!--end::Content-->

		</div>
		<!--end::Profile 4-->
	</div>
	<!--end::Container-->

</div>
@endsection
@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {
var timer;
var startTime;
var intervalID;

window.start = function() {
  startTime = parseInt(localStorage.getItem('startTime') || Date.now());
  localStorage.setItem('startTime', startTime);
  timer = setInterval(window.clockTick, 100);
}

window.stop = function() {
  clearInterval(timer);
}

window.reset = function() {
  clearInterval(timer);
  localStorage.removeItem('startTime');
  document.getElementById('display-area').innerHTML = "00:00:00.000";
}

window.clockTick = function(){
  var currentTime = Date.now(),
    timeElapsed = new Date(currentTime - startTime),
    hours = timeElapsed.getUTCHours(),
    mins = timeElapsed.getUTCMinutes(),
    secs = timeElapsed.getUTCSeconds(),
    ms = timeElapsed.getUTCMilliseconds(),
    display = document.getElementById("display-area");

  data =
    (hours > 9 ? hours : "0" + hours) + ":" +
    (mins > 9 ? mins : "0" + mins) + ":" +
    (secs > 9 ? secs : "0" + secs) + "." +
    (ms > 99 ? ms : ms > 9 ? "0" + ms : "00" + ms);

    $("#display-area").val(data);
};

	window.start_extend_timer = function(){
		startTime = parseInt(localStorage.getItem('startTime') || Date.now());
  		localStorage.setItem('startTime', startTime);
		var currentTime = Date.now(),
		timeElapsed = new Date(currentTime - startTime),
		hours = timeElapsed.getUTCHours(),
		mins = timeElapsed.getUTCMinutes(),
		secs = timeElapsed.getUTCSeconds(),
		ms = timeElapsed.getUTCMilliseconds();

		 data =
			(hours > 9 ? hours : "0" + hours) + ":" +
			(mins > 9 ? mins : "0" + mins) + ":" +
			(secs > 9 ? secs : "0" + secs) + "." +
			(ms > 99 ? ms : ms > 9 ? "0" + ms : "00" + ms);

			$("#display-area").val(data);

		return data;
	}

	function unpaused(){
		jQuery.ajax({
			url: "/agent_dialer/pause_status",
			type: "POST",
			data: 'id={{Auth::user()->id}}&status=0&file_id={{$file_id}}',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success: function(data) {
				KTApp.unblockPage();
				$("#btn-campaign-unpaused").css('display','none');
				$("#btn-campaign-pause").css('display','');		          	 
			},
			error: function(data){


			}
		}); 
	}

	function break_timeout(){
		jQuery.ajax({
			url: "/agent_dialer/break_time_status",
			type: "POST",
			data: 'id={{Auth::user()->id}}&status=0&file_id={{$file_id}}',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success: function(data) {
				KTApp.unblockPage();
				window.start();
			},
			error: function(data){


			}
		}); 
	}

	get_call_status_view_auto = function(contact_no, dial){
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
					//$("#modal_add_entry").modal('toggle');
					$("#div-call-status-flag").html('');
					$("#btn_save_new_entry").prop('disabled',false);
					clearInterval(intervalID);
					if(dial == 0){
						// swal.fire({
		        //   text: 'Call ended',
		        //   icon: "success",
		        //   buttonsStyling: false,
		        //   confirmButtonText: "Ok",
		        //   customClass: {
		        //       confirmButton: "btn font-weight-bold btn-light-primary"
		        //   },onClose: function(e) {

		        //     Swal.close();
		        //  }
		        //  }).then(function() {
		        //     KTUtil.scrollTop();
		        //  });
					}else{
							Swal.close();
					}

				}else if(data.status == 'Connecting' || data.status == 'Ringing'){
					$("#btn_save_new_entry").prop('disabled','disabled');
					$("#div-call-status-flag").html('<div class="alert alert-warning" role="alert">You cannot submit a new entry while your are engage in a call.</div>');
					$("#div-call-status-view").html(data.msg);
					// Swal.fire({
				  //       title: data.status,
				  //       text: "",
				  //       timer: 5000,
				  //       onOpen: function() {
				  //           Swal.showLoading()
				  //       }
				  //   }).then(function(result) {
				  //       if (result.dismiss === "timer") {
				  //           console.log("I was closed by the timer")
				  //       }
				  //   })
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

	function get_best_time_to_call(){
		jQuery.ajax({
			url: "/leads/get_best_time_to_call",
			type: "POST",
			data: 'collector_id={{Auth::user()->id}}&file_id={{$file_id}}',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success: function(data) {

					if(data.total > 0){
						$("#div-best-time-call-notif").html('');
						jQuery.each(data.get_data, function(k,val){
								 var link = '{{ route('pages.agent.previous', ['file_id' => $file_id, 'leads_id' => ':leads_id']) }}';
								 link = link.replace(':leads_id', val.id);

								 var div_best_time = `<!--begin::Item-->
									<div class="d-flex align-items-center mb-9 bg-warning rounded p-5">
											<!--begin::Icon-->
											<i class="fas fa-arrow-right mr-5"></i>
											<!--end::Icon-->
											
											<!--begin::Title-->
											<div class="d-flex flex-column flex-grow-1 mr-2">
												<a href="`+link+`" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
												`+val.full_name+`
												</a>
												<span class="text-muted font-weight-bold">`+val.time+`</span>
											</div>
											<!--end::Title-->
									</div>
									<!--end::Item-->`;     
			           $("#div-best-time-call-notif").append(div_best_time);      
		        });

					}else{
						$("#div-best-time-call-notif").html(`<!--begin::Item-->
									<div class="d-flex align-items-center mb-9 bg-light rounded p-5">
											<!--begin::Icon-->
											<i class="fas fa-arrow-right mr-5"></i>
											<!--end::Icon-->
											
											<!--begin::Title-->
											<div class="d-flex flex-column flex-grow-1 mr-2">
												<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
												No Records Found
												</a>
											</div>
											<!--end::Title-->
									</div>
									<!--end::Item-->`);
					}
					
			},
			error: function(data){



					

			}
		}); 
	}

	function myTimer() {
	  get_best_time_to_call();
	}

	timer_val = setInterval(myTimer, 30000);

	function start_call(call_contact_no, name, profile_id, leads_id, dial){
		KTApp.unblockPage();
		if(call_contact_no == 0){
			swal.fire({
		        text: "The account has no valid contact no. - Go to the Personal details Tab to add a mobile no.",
		        icon: "error",
		        buttonsStyling: false,
		        confirmButtonText: "Ok, got it!",
		        allowOutsideClick: false,
		        customClass: {
		          confirmButton: "btn font-weight-bold btn-light-primary"
		        }
		        }).then(function() {
		            KTUtil.scrollTop();
		        });
		   return;
		}

		@if(env('DIALER_CONFIRM_CALL') == 'true')

			Swal.fire({
		        title: "Start a call?",
		        text: "",
		        icon: "warning",
		        showCancelButton: true,
		        confirmButtonText: "Yes"
		    }).then(function(result) {
		        if (result.value) {
		        	KTApp.blockPage({
						overlayColor: '#000000',
						state: 'primary',
						message: 'Calling...'
					});
					$.ajax({
					    url: "/agent_dialer/agent_call",
					    type: "POST",
					    data: 'contact_no='+call_contact_no+'&name='+name+'&profile_id='+profile_id+'&leads_id='+leads_id+'&file_id={{$file_id}}',
					    headers: {
					       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					    },
					    dataType: "JSON",
					    success: function(data){
					       KTApp.unblockPage();
					       // Swal.fire({
							   //      title: 'Connecting',
							   //      text: "",
							   //      timer: 20000,
							   //      onOpen: function() {
							   //          Swal.showLoading()
							   //      }
							   //  }).then(function(result) {
							   //      if (result.dismiss === "timer") {
							   //          console.log("I was closed by the timer")
							   //      }
							   //  })
							   toastr.success("Please accept the confirmation call in your sofphone.");
					       intervalID = setInterval(function() { get_call_status_view_auto(call_contact_no, dial); }, 3000);
					       
			                    
					    }
					}); 
		        }
		    });

		@else
			KTApp.blockPage({
				overlayColor: '#000000',
				state: 'primary',
				message: 'Calling...'
			});

			$.ajax({
				url: "/agent_dialer/agent_call",
				type: "POST",
				data: 'contact_no='+call_contact_no+'&name='+name+'&profile_id='+profile_id+'&leads_id='+leads_id+'&file_id={{$file_id}}',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				dataType: "JSON",
				success: function(data){
					KTApp.unblockPage();
					// Swal.fire({
					// 		        title: 'Connecting',
					// 		        text: "",
					// 		        timer: 20000,
					// 		        onOpen: function() {
					// 		            Swal.showLoading()
					// 		        }
					// 		    }).then(function(result) {
					// 		        if (result.dismiss === "timer") {
					// 		            console.log("I was closed by the timer")
					// 		        }
					// 		    })
					toastr.success("Please accept the confirmation call in your sofphone.");
					intervalID = setInterval(function() { get_call_status_view_auto(call_contact_no, dial); }, 3000);
					
			                    
				}
				}); 

		@endif
		
		 
        
	}

	function get_account_status(leads_id, profile_id, name, account_status, dial_status, contact_no, last_call_contact_no){
		//Query the account details
			jQuery.ajax({
					    url: "/agent_dialer/view_leads_data/{{$file_id}}/{{Auth::user()->id}}/"+leads_id,
					    type: "GET",
					    headers: {
					       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					    },
					    dataType: "html",
					    success: function(data){
					      $("#div-load-leads").html(data);

							  KTApp.unblockPage();
				  			$("#btn-campaign-pause").prop('disabled', false);
								$("#btn-campaign-break-time").prop('disabled', false);

								switch(account_status) {
								  case 0:

								    window.start();
					  				if(dial_status == 0){
							  			start_call(contact_no, name, profile_id, leads_id, dial_status);
						  			}else{
						  				intervalID = setInterval(function() { get_call_status_view_auto(last_call_contact_no, dial_status); }, 3000);
						  				//$("#btn-call-again").html('Call Again?');
						  			}

								    break;
								  case 1:

								    window.start();
								    if(dial_status == 0){
							  			start_call(contact_no, name, profile_id, leads_id, dial_status);
						  			}else{
						  				intervalID = setInterval(function() { get_call_status_view_auto(last_call_contact_no, dial_status); }, 3000);
						  				//$("#btn-call-again").html('Call Again?');
						  			}

								    break;

								  case 2:
								    
								    window.start();
					  				Swal.fire({
								        title: "You pause your auto dialer",
								        text: "",
								        imageUrl: "https://unsplash.it/400/200",
								        confirmButtonText: "Unpause",
								        imageWidth: 400,
								        imageHeight: 200,
								        imageAlt: "Custom image",
								        allowOutsideClick: false,
								        animation: false,
								        customClass: {
					                confirmButton: "btn font-weight-bold btn-light-primary"
					              },onClose: function(e) {
					                       unpaused();	
					              }
					              }).then(function() {
					                 KTUtil.scrollTop();
					              });
								    break;

								  case 3:
								    
								    window.start_extend_timer();
					  				Swal.fire({
								        title: "You are On Break!",
								        text: "",
								        imageUrl: "https://unsplash.it/400/200",
								        confirmButtonText: "Take a Call",
								        imageWidth: 400,
								        imageHeight: 200,
								        imageAlt: "Custom image",
								        allowOutsideClick: false,
								        animation: false,
								        customClass: {
					                 confirmButton: "btn font-weight-bold btn-light-primary"
					              },onClose: function(e) {
					                 break_timeout();	
					              }
					              }).then(function() {
					                 KTUtil.scrollTop();
					              });
								    break;
								  default:
								    // code block
								}

				  			
			                    
					    }
					}); 

	}

	function get_my_leads(){

		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			 message: 'Processing...'
		});  

		jQuery.ajax({
			url: "/agent_dialer/get_leads_data",
			type: "POST",
			data: 'user_id={{Auth::user()->id}}&file_id={{$file_id}}',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success: function(data) {
				var leads_id = data.leads_id;

				console.log(data.active_campaign);

				if(data.active_campaign == 'false'){
					//Check if the campaig is activated
					KTApp.unblockPage();
					window.reset();	
					$("#btn-campaign-pause").prop('disabled', 'disabled');
					$("#btn-campaign-break-time").prop('disabled', 'disabled');
					$("#div-load-leads").html(`<div class="alert alert-custom alert-white shadow-lg fade show mb-5" role="alert">
															<div class="alert-icon">
																<i class="flaticon-warning"></i>
															</div>
															<div class="alert-text">Campaign has been deactivated by the manager/administrator.</div>
														</div>`);

					return;
				}else{
					$("#btn-campaign-pause").prop('disabled', false);
					$("#btn-campaign-break-time").prop('disabled', false);	
				}

				if(data.pause_time == 'true'){
					KTApp.unblockPage();
					window.reset();	
					$("#btn-campaign-pause").prop('disabled', 'disabled');
					$("#btn-campaign-break-time").prop('disabled', 'disabled');
					$("#div-load-leads").html(`<div class="alert alert-custom alert-white shadow-lg fade show mb-5" role="alert">
															<div class="alert-icon">
																<i class="flaticon-warning"></i>
															</div>
															<div class="alert-text">Campaign has been pause by the manager/administrator.</div>
														</div>`);

					return;
				}else{
					$("#btn-campaign-pause").prop('disabled', false);
					$("#btn-campaign-break-time").prop('disabled', false);	
				}

				if(data.sched_time == 'out'){
					KTApp.unblockPage();
					window.reset();	
					$("#btn-campaign-pause").prop('disabled', 'disabled');
					$("#btn-campaign-break-time").prop('disabled', 'disabled');
					$("#div-load-leads").html(`<div class="alert alert-custom alert-white shadow-lg fade show mb-5" role="alert">
															<div class="alert-icon">
																<i class="flaticon-warning"></i>
															</div>
															<div class="alert-text">Campaign is already close. it will resume at {{$campaigns->start_time}}</div>
														</div>`);

					return;
				}else{
					$("#btn-campaign-pause").prop('disabled', false);
					$("#btn-campaign-break-time").prop('disabled', false);	
				}

				if(leads_id == 0){
					KTApp.unblockPage();
					window.reset();
					$("#btn-campaign-pause").prop('disabled', 'disabled');
					$("#btn-campaign-break-time").prop('disabled', 'disabled');	
					$("#div-load-leads").html(`<div class="alert alert-custom alert-white shadow-lg fade show mb-5" role="alert">
															<div class="alert-icon">
																<i class="flaticon-warning"></i>
															</div>
															<div class="alert-text">No leads available, please contact your administrator or supervisor to assign a leads to your campaign.</div>
														</div>`);
					return;
				}

				get_account_status(leads_id, data.profile_id, data.name, data.account_status, data.dial, data.contact_no, data.last_call_contact_no);	          	 

			},
			error: function(data){


			}
		});  
		
	}

	get_my_leads();

	// function myTimer() {
	//   var test get_my_leads();
	// }
	// timer_val = setInterval(myTimer, 20000);

	jQuery("#kt_account_infor").select2({
   placeholder: "Search account number or name",
   allowClear: true,
   ajax: {
    url: "/agent_dialer/{{$file_id}}/search_account_data",
    dataType: 'json',
    delay: 250,
    data: function(params) {
     return {
      q: params.term, // search term
      page: params.page,
      file_id: '{{$file_id}}',
      collector_id: '{{Auth::user()->id}}'
     };
    },
    processResults: function(data, params) {
     // parse the results into the format expected by Select2
     // since we are using custom formatting functions we do not need to
     // alter the remote JSON data, except to indicate that infinite
     // scrolling can be used
     params.page = params.page || 1;
     return {
      results: data.items,
      pagination: {
       more: (params.page * 30) < data.total_count
      }
     };
    },
    cache: true
   },
   escapeMarkup: function(markup) {
    return markup;
   }, // let our custom formatter work
   minimumInputLength: 1,
  });

	jQuery(document).on('change', '#kt_account_infor', function(e){
		var leads_id = $(this).val();
			if(leads_id != ''){
				var link = '{{ route('pages.agent.previous', ['file_id' => $file_id, 'leads_id' => ':leads_id']) }}';
				link = link.replace(':leads_id', leads_id);
				window.location = link; 
			}
			
	});

	jQuery(document).on('click', '#btn-campaign-break-time', function(e){

		e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to take a break?",
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

				jQuery.ajax({
					url: "/agent_dialer/break_time_status",
					type: "POST",
					data: 'id={{Auth::user()->id}}&status=3&file_id={{$file_id}}',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: "json",
					success: function(data) {
						KTApp.unblockPage();
						stop();
						Swal.fire({
					        title: "You are On Break!",
					        text: "",
					        imageUrl: "https://unsplash.it/400/200",
					        confirmButtonText: "Take a Call",
					        imageWidth: 400,
					        imageHeight: 200,
					        imageAlt: "Custom image",
					        allowOutsideClick: false,
					        animation: false,
					        customClass: {
		                        confirmButton: "btn font-weight-bold btn-light-primary"
		                    },onClose: function(e) {
		                      	break_timeout()			
		                    }
		                    }).then(function() {
		                                 KTUtil.scrollTop();
		                    });
							          	 

					},
					error: function(data){


					}
				});  
	        }
	    });
		

	});

	jQuery(document).on('click', '#btn-campaign-pause', function(e){

		e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to pause?",
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

				jQuery.ajax({
					url: "/agent_dialer/pause_status",
					type: "POST",
					data: 'id={{Auth::user()->id}}&status=2&file_id={{$file_id}}',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: "json",
					success: function(data) {
						KTApp.unblockPage();

						$("#btn-campaign-unpaused").css('display','');
						$("#btn-campaign-pause").css('display','none');

						Swal.fire({
					        title: "You pause your auto dialer",
					        text: "",
					        imageUrl: "https://unsplash.it/400/200",
					        confirmButtonText: "Unpause",
					        imageWidth: 400,
					        imageHeight: 200,
					        imageAlt: "Custom image",
					        allowOutsideClick: false,
					        animation: false,
					        customClass: {
		                        confirmButton: "btn font-weight-bold btn-light-primary"
		                    },onClose: function(e) {
		                        unpaused();
		                      				
		                    }
		                    }).then(function() {
		                                 KTUtil.scrollTop();
		                    });
							          	 

					},
					error: function(data){


					}
				});  
	        }
	    });
		

	});


	jQuery(document).on('click', '#btn-campaign-unpaused', function(e){

		e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to unpaused?",
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
				
				jQuery.ajax({
					url: "/agent_dialer/pause_status",
					type: "POST",
					data: 'id={{Auth::user()->id}}&status=0&file_id={{$file_id}}',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: "json",
					success: function(data) {
						KTApp.unblockPage();

						$("#btn-campaign-unpaused").css('display','none');
						$("#btn-campaign-pause").css('display','');
							          	 

					},
					error: function(data){


					}
				});  
	        }
	    });
		

	});

	jQuery(document).on('click', '#btn-campaign-logout', function(e){

		e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to logged out?",
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

				jQuery.ajax({
					url: "/agent_dialer/logged_status",
					type: "POST",
					data: 'id={{Auth::user()->id}}&status=0&file_id={{$file_id}}',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: "json",
					success: function(data) {
						KTApp.unblockPage();
						window.reset();
						if(data.msg == 'ok'){
							var link = '{{ route('pages.dashboard.index') }}';
							window.location = link; 
						}
							          	 

					},
					error: function(data){


					}
				});  
	        }
	    });
		

	});

});
</script>
@endpush