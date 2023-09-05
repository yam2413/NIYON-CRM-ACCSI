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
								<span class="font-weight-bold mr-2">Status:</span>
								<span class="text-muted">{{$statuses}}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Date Within:</span>
								<span class="text-muted">{{$date_human}}</span>
							</div>
						</div>
						<!--end::Contact-->
						
						<!--begin::Contact-->
						{{-- <div class="pb-6">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical.</div> --}}
						<!--end::Contact-->
						<button type="button" id="btn-campaign-logout" class="btn btn-light-danger font-weight-bold py-3 px-6 mb-2 text-center btn-block">Exit</button>
					</div>
					<!--end::Body-->
				</div>
				<!--end::Card-->
				
				<!--begin::Mixed Widget 14-->
				<div class="card card-custom gutter-b">
					<input type="hidden" id="display-area">
					<!--begin::Header-->
					{{-- <div class="card-header border-0 pt-5">
						<h3 class="card-title font-weight-bolder">Search Previous Accounts</h3>
					</div> --}}
					<!--end::Header-->

					<!--begin::Body-->
					{{-- <div class="card-body d-flex flex-column">
						<div class="flex-grow-1">
							 <select class="form-control select2" id="kt_account_infor" name="kt_account_infor">
                   
               </select>
							
						</div>
					</div> --}}
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
					<div id="div-show-dial-already">
						
					</div>
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
				    //     title: data.status,
				    //     text: "",
				    //     timer: 15000,
				    //     onOpen: function() {
				    //         Swal.showLoading()
				    //     }
				    // }).then(function(result) {
				    //     if (result.dismiss === "timer") {
				    //         console.log("I was closed by the timer")
				    //     }
				    // })
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
					    url: "/status_dialer/agent_call",
					    type: "POST",
					    data: 'contact_no='+call_contact_no+'&name='+name+'&profile_id='+profile_id+'&leads_id='+leads_id,
					    headers: {
					       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					    },
					    dataType: "JSON",
					    success: function(data){
					       KTApp.unblockPage();
					       	
					       	// Swal.fire({
							//         title: 'Connecting',
							//         text: "",
							//         timer: 20000,
							//         onOpen: function() {
							//             Swal.showLoading()
							//         }
							//     }).then(function(result) {
							//         if (result.dismiss === "timer") {
							//             console.log("I was closed by the timer")
							//         }
							//     })

					       intervalID = setInterval(function() { get_call_status_view_auto(call_contact_no, dial); }, 5000);
					       $("#btn-call-again").html('Call Again ?');
			                    
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
				url: "/status_dialer/agent_call",
				type: "POST",
				data: 'contact_no='+call_contact_no+'&name='+name+'&profile_id='+profile_id+'&leads_id='+leads_id,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				dataType: "JSON",
				success: function(data){
					KTApp.unblockPage();

					// 	Swal.fire({
				    //     title: 'Connecting',
				    //     text: "",
				    //     timer: 20000,
				    //     onOpen: function() {
				    //         Swal.showLoading()
				    //     }
				    // }).then(function(result) {
				    //     if (result.dismiss === "timer") {
				    //         console.log("I was closed by the timer")
				    //     }
				    // })
					intervalID = setInterval(function() { get_call_status_view_auto(call_contact_no, dial); }, 5000);
					$("#btn-call-again").html('Call Again ?');
			                    
				}
				}); 

		@endif
		
		 
        
	}

	function get_account_status(leads_id, dial, contact_no, profile_id, name, last_call_contact_no, payment_date, ptp_status){
		//Query the account details
			jQuery.ajax({
					    url: "/status_dialer/view_leads_data/{{Auth::user()->id}}/{{$date}}/{{$statuses}}/{{$group_id}}/"+leads_id,
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
								
							window.start();

							if(ptp_status != '0'){

								// if(ptp_status == '1'){
								// 	var txt_ptp_status = 'This account has PTP Date and PTP Amount';
								// }else if(ptp_status == '2'){
								// 	var txt_ptp_status = '';
								// }
		                            Swal.fire({
								        title: "This account has PTP Date and PTP Amount.",
								        text: 'Promise Date: '+payment_date,
								        icon: "warning",
								        showCancelButton: true,
								        confirmButtonText: "Dial Now",
								        cancelButtonText: "Close",
								        reverseButtons: true
								    }).then(function(result) {
								        if (result.value) {
								            start_call(contact_no, name, profile_id, leads_id, dial);
								        } else if (result.dismiss === "cancel") {
								            
								        }
								    });

								return;
							}
					  		if(dial == 0){
							  	start_call(contact_no, name, profile_id, leads_id, dial);
						  	}else{
						  		intervalID = setInterval(function() { get_call_status_view_auto(last_call_contact_no, dial); }, 5000);
						  		$("#div-show-dial-already").html(`<div class="alert alert-warning" role="alert">
									    This account is already dialed today, auto start dialing will not be trigger.
										</div>`);		
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
			url: "/status_dialer/get_leads_data_with_filter",
			type: "POST",
			data: 'user_id={{Auth::user()->id}}&statuses={{$statuses}}&date={{$date}}&group_id={{$group_id}}',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success: function(data) {
					var leads_id 				= data.leads_id;
					var dial 					= data.dial;
					var contact_no 				= data.contact_no;
					var profile_id 				= data.profile_id;
					var name 					= data.name;
					var last_call_contact_no 	= data.last_call_contact_no;
					var last_call_contact_no 	= data.last_call_contact_no;
					var payment_date 			= data.payment_date;
					var ptp_amount 				= data.ptp_amount;
					var ptp_status 				= data.ptp_status;

					console.log(data);
					if(leads_id == 0){
						KTApp.unblockPage();
						window.reset();
						// $("#btn-campaign-pause").prop('disabled', 'disabled');
						// $("#btn-campaign-break-time").prop('disabled', 'disabled');
						$("#div-load-leads").html(`<div class="alert alert-custom alert-white shadow-lg fade show mb-5" role="alert">
															<div class="alert-icon">
																<i class="flaticon-warning"></i>
															</div>
															<div class="alert-text font-weight-bold">NO STATUS {{$statuses}} LEADS AVAILABLE.</div>
														</div>`);
						return;
					}


					get_account_status(leads_id, dial, contact_no, profile_id, name, last_call_contact_no, payment_date, ptp_status);	          	 

			},
			error: function(data){


			}
		});  
		
	}

	get_my_leads();

	jQuery(document).on('click', '#btn-campaign-logout', function(e){

		e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to exit this statuses campaign?",
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
					url: "/status_dialer/access_campaign_status",
					type: "POST",
					data: 'log_type=logout&status={{$statuses}}&date={{$date}}',
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