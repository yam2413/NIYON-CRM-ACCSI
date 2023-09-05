@extends('templates.default')
@section('title', 'Call Monitoring')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">
		 <!--begin: Search Form-->
							<form class="mb-15">
								<div class="row mb-6">
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Groups:</label>
										<select id="filter_groups" class="form-control">
											{{-- <option value="0">Select All</option> --}}
											@if(Auth::user()->level == '0')
															
												@foreach ($groups as $key => $group)
													<option value="{{$group->id}}">{{$group->name}}</option>
												@endforeach
																	 		
											@else
												<option value="{{Auth::user()->group}}">{{\App\Models\Groups::usersGroup(Auth::user()->group)}}</option>
											@endif
										</select>
									</div>

								</div>
							</form>

							<!--begin::Card-->
		<!--begin::Row-->
		<div class="row" id="div-show-call-monitoring">

									
									
									
									
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
var timer_val;
function get_leads_status(group_id){
	jQuery.ajax({
		url: "/call_monitoring/get_call_monitoring",
		type: "POST",
		data: 'group_id='+group_id,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		dataType: "json",
		success: function(data) {
			$("#div-show-call-monitoring").html('');

			if(data.total > 0){

				jQuery.each(data.get_data, function(k,val){
				var html = `<!--begin::Col-->
									<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
										<!--begin::Card-->
										<div class="card card-custom bg-`+val.call_color+` gutter-b card-stretch">
											<!--begin::Body-->
											<div class="card-body pt-4">
												
												<!--begin::User-->
												<div class="d-flex align-items-end mb-7">
													<!--begin::Pic-->
													<div class="d-flex align-items-center">
														<!--begin::Pic-->
														<div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
															<div class="symbol symbol-circle symbol-lg-45">
																<img src="{{ asset('media/users/default.jpg') }}" alt="image" />
															</div>
															<div class="symbol symbol-lg-75 symbol-circle symbol-primary d-none">
																<span class="font-size-h3 font-weight-boldest">JM</span>
															</div>
														</div>
														<!--end::Pic-->
														<!--begin::Title-->
														<div class="d-flex flex-column">
															<a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">`+val.name+`</a>
														</div>
														<!--end::Title-->
													</div>
													<!--end::Title-->
												</div>
												<!--end::User-->
												<!--begin::Info-->
												<div class="mb-7">
													<div class="d-flex justify-content-between align-items-center">
														<span class="text-dark-75 font-weight-bolder mr-2">Extension:</span>
														<a href="#" class="font-weight-bolder text-dark">`+val.extension+`</a>
													</div>
													<div class="d-flex justify-content-between align-items-center">
														<span class="text-dark-75 font-weight-bolder mr-2">Call Status:</span>
														<a href="#" class="font-weight-bolder text-dark">`+val.call_status+`</a>
													</div>
												</div>
												<!--end::Info-->
												<div class="btn-group btn-group-lg" role="group" aria-label="Large button group">
												    <button type="button" class="btn btn-outline-secondary btn-listen" id="`+val.dialplan_listen+`">
												    	Listen
												    </button>
												    <button type="button" class="btn btn-outline-secondary btn-whisper" id="`+val.dialplan_whisper+`">
												    	Whisper
												    </button>
												    <button type="button" class="btn btn-outline-secondary btn-barge" id="`+val.dialplan_barge+`">
												    	Barge
												    </button>
												</div>


											</div>
											<!--end::Body-->
										</div>
										<!--end::Card-->
									</div>
									<!--end::Col-->`;
					$("#div-show-call-monitoring").append(html);   
					    
		    	});

			}else{
				$("#div-show-call-monitoring").html(`<div class="alert  alert-custom alert-secondary" role="alert">
    <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
    <div class="alert-text">No Available Call</div>
</div>`);
			}
			
					
					
		},
		error: function(data){



					

		}
	}); 
 }

 var filter_groups = $("#filter_groups").val();
 get_leads_status(filter_groups);
 timer_val = setInterval(function() { get_leads_status(filter_groups); }, 5000);


  jQuery(document).on('change', '#filter_groups', function(e){
		var filter_groups = $(this).val();
		get_leads_status(filter_groups);
		clearInterval(timer_val);
		timer_val = setInterval(function() { get_leads_status(filter_groups); }, 5000);
 });

  jQuery(document).on('click', '.btn-listen', function(e){
    	e.preventDefault();
    	var dial_plan = $(this).attr('id');
    	Swal.fire({
	        title: "Are you sure you want to listen in the call?",
	        text: "Make sure your softphone or IPphone are connected to the PBX",
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
				    data: 'dial_plan='+dial_plan,
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
	        text: "Make sure your softphone or IPphone are connected to the PBX",
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
				    data: 'dial_plan='+dial_plan,
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
	        text: "Make sure your softphone or IPphone are connected to the PBX",
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
				    data: 'dial_plan='+dial_plan,
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