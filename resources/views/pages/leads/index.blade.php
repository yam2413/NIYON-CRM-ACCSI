@extends('templates.default')
@section('title', 'Leads')
@push('scripts')
<style type="text/css">
	.dataTables_filter, .dataTables_info { display: none; }
</style>
@endpush
@section('content')
<!--begin::Entry-->
						<div class="d-flex flex-column-fluid">
							<!--begin::Container-->
							<div class="container">
								<!--begin::Page Layout-->
								<div class="d-flex flex-row">
									<!--begin::Aside-->
									<div class="flex-column offcanvas-mobile w-300px w-xl-325px" id="kt_profile_aside">
										<!--begin::Forms Widget 13-->
										<div class="card card-custom gutter-b">
											<div class="card-header border-0 pt-5">
												{{-- <h3 class="card-title align-items-start flex-column mb-3">
													<span class="card-label font-size-h3 font-weight-bolder text-dark">Filter</span>
												</h3> --}}
											</div>
											<!--begin::Body-->
											<div class="card-body pt-4">
												<!--begin::Form-->
												<form>
													
													<!--begin::Product info-->
													<div class="mt-6">
														<div class="text-muted mb-4 font-weight-bolder font-size-lg">Filter</div>
														<!--begin::Input-->
														<div class="form-group mb-8" id="div_ptp_show" style="display: none;">
															<label class="font-weight-bolder">Promise Date</label>
															<div class='input-group' id='ptp_date'>
																<input type='text' class="form-control" readonly name="ptp_date"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
																<div class="input-group-append">
																	<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
																</div>
															 </div>
														</div>
														<div class="form-group mb-8">
															<label class="font-weight-bolder">Group</label>
															<select id="filter_groups" class="form-control form-control-solid form-control-lg">
															 	<option value="">Filter By Group</option>
															 	@if(Auth::user()->level == '0')
															 		@foreach ($groups as $key => $group)
														                <option value="{{$group->id}}">{{$group->name}}</option>
														            @endforeach
															 		
															 	@else
															 		<option value="{{Auth::user()->group}}">{{\App\Models\Groups::usersGroup(Auth::user()->group)}}</option>
															 	@endif
															 </select>
														</div>
														<div class="form-group mb-8">
															<label class="font-weight-bolder">Collector</label>
															 <select id="filter_collector" class="form-control form-control-solid form-control-lg">
															 	<option value="">Select Collector</option>
															 </select>
														</div>
														<div class="form-group mb-8">
															<label class="font-weight-bolder">Status</label>
															 <select id="filter_status" class="form-control form-control-solid form-control-lg">
															 	<option value="">All Status</option>
															 </select>
														</div>

														<!--end::Color-->
														<button type="button" id="btn-reload-leads-table" class="btn btn-primary font-weight-bolder mr-2 px-8" data-card-tool="reload" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reload the list"><i class="ki ki-reload icon-nm"></i></button>
														<button type="button" id="btn-clear-leads-table" class="btn btn-clear font-weight-bolder text-muted px-8" data-card-tool="reload" data-toggle="tooltip" data-placement="top" title="" data-original-title="Clear the selected filter"><i class="fas fa-broom"></i> Clear Filter</button>
														<!--end::Input-->
														
													</div>
													<!--end::Product info-->
												</form>
												<!--end::Form-->
											</div>
											<!--end::Body-->
										</div>
										<!--end::Forms Widget 13-->
										<!--begin::List Widget 21-->
										<div class="card card-custom gutter-b">
											<!--begin::Header-->
											<div class="card-header border-0 pt-5">
												<h3 class="card-title align-items-start flex-column mb-5">
													<span class="card-label font-weight-bolder text-dark mb-1">Recent Activity</span>
													{{-- <span class="text-muted mt-2 font-weight-bold font-size-sm">New Arrivals</span> --}}
												</h3>
											</div>
											<!--end::Header-->
											<!--begin::Body-->
											<div class="card-body pt-2">
												<!--begin::Item-->
												@if(count($last_activitys) > 0)

													@foreach ($last_activitys as $last_activity)
														@php
															 $msg_text_short = \Illuminate\Support\Str::limit($last_activity->actions, 100, '...');
														@endphp
														<div class="d-flex mb-8">
														<!--begin::Symbol-->
														<div class="symbol symbol-50 symbol-2by3 flex-shrink-0 mr-4">
															<div class="d-flex flex-column">
																<div class="symbol-label mb-3"><i class="fas fa-history icon-2x"></i></div>
																<a href="{{ route('pages.leads.profile', ['profile_id' => $last_activity->profile_id]) }}" class="btn btn-light-primary font-weight-bolder py-2 font-size-sm">Open</a>
															</div>
														</div>
														<!--end::Symbol-->
														<!--begin::Title-->
														<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
															<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary font-size-sm mb-2">{{$last_activity->full_name}}</a>
															<span class="text-muted font-weight-bold font-size-sm mb-3">{{$msg_text_short}}</span>
															<span class="text-dark-75 font-weight-bolder">{{$last_activity->created_at->diffForHumans()}}</span>
														</div>
														<!--end::Title-->
													</div>
													@endforeach

												@else
													<span class="card-label font-weight-bolder text-dark mb-1">No recent activity</span>
												@endif
												<!--end::Item-->
												
											</div>
											<!--end::Body-->
										</div>
										<!--end::List Widget 21-->
									</div>
									<!--end::Aside-->
									<!--begin::Layout-->
									<div class="flex-row-fluid ml-lg-8">
										<!--begin::Card-->
										<div class="card card-custom card-stretch gutter-b">
											<div class="card-body">
												<!--begin::Engage Widget 15-->
												<div class="card card-custom mb-12">
													<div class="card-body rounded p-0 d-flex" style="background-color:#DAF0FD;">
														<div class="d-flex flex-column flex-lg-row-auto w-auto w-lg-350px w-xl-450px w-xxl-500px p-10 p-md-20">
															{{-- <h1 class="font-weight-bolder text-dark">Search Goods</h1> --}}
															<div class="font-size-h4 mb-8">Manage your leads accounts.</div>
															<!--begin::Form-->
															<form class="d-flex flex-center py-2 px-6 bg-white rounded">
																<span class="svg-icon svg-icon-lg svg-icon-primary">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24" />
																			<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																			<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
																		</g>
																	</svg>
																	<!--end::Svg Icon-->
																</span>
																<input type="text" id="search_leads" class="form-control border-0 font-weight-bold pl-2" placeholder="Search Account Name or Account Number" />
															</form>
															<!--end::Form-->
														</div>
														<div class="d-none d-md-flex flex-row-fluid bgi-no-repeat bgi-position-y-center bgi-position-x-left bgi-size-cover" style="background-image: url({{ asset('media/svg/illustrations/progress.svg') }});"></div>
													</div>
												</div>
												<!--end::Engage Widget 15-->
												<!--begin::Section-->
												<div class="mb-11">

													<!--begin: Datatable-->
													<table id="tbl_leads" class="table table-bordered table-hover table-checkable">
														<thead>
															<tr>
																<th></th>
																<th>Account Status</th>
																<th>Collector</th>
																<th>Account Number</th>
																<th>Name</th>
																<th>Group</th>
																<th>Created Date</th>
															</tr>
														</thead>
													</table>
													<!--end: Datatable-->
													
												</div>
												<!--end::Section-->

												
											</div>
										</div>
										<!--end::Card-->
									</div>
									<!--end::Layout-->
								</div>
								<!--end::Page Layout-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Entry-->
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

var tbl_leads = $('#tbl_leads');

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

 var start = moment();
 var end = moment();

 $('#ptp_date .form-control').val(start.format('YYYY-MM-DD'));

$('#search_leads').on('keyup', function() {
  jQuery("#tbl_leads").DataTable().search($(this).val()).draw();
});


function func_tbl_leads(groups, status, collector, date){
	$("#tbl_leads").empty();
	if(status == '1' || status == '2' || status == '3' || status == '4'){
		$("#tbl_leads").html(`<thead><tr>
										<th></th>
										<th>Account Status</th>
										<th>Payment Amount</th>
										<th>Payment Date</th>
										<th>Collector</th>
										<th>Account Number</th>
										<th>Name</th>
										<th>Group</th>
										<th>Created Date</th>
									</tr></thead>`);
		tbl_leads.DataTable({
		responsive: true,
		// searchDelay: 500,
		processing: true,
		serverSide: true,
		searching: true,
		ajax: {
			    url: "/leads/getLeads",
			    type: 'POST',
			    headers: {
			        'X-CSRF-TOKEN': '{{ csrf_token() }}'
			    },
			    data: {
			          // parameters for custom backend script demo
			            "groups" : groups,
			            "status" : status,
			            "collector" : collector,
			            "date" : date,
			       },
			  },
		order: [[ 8, "desc" ]],
		columns: [
			        
			    {data: 'action',orderable: false},
			    {data: 'status',orderable: false},
			    {data: 'ptp_amount',orderable: false},
			    {data: 'payment_date',orderable: false},
			    {data: 'assign_user',orderable: false},
			    {data: 'account_number',orderable: false},
			    {data: 'full_name',orderable: false},
			    {data: 'assign_group',orderable: false},
			    {data: 'created_at',orderable: false},
			],
		});
	}else{
		$("#tbl_leads").html(`<thead>
									<tr>
										<th></th>
										<th>Account Status</th>
										<th>Collector</th>
										<th>Account Number</th>
										<th>Name</th>
										<th>Group</th>
										<th>Created Date</th>
									</tr>
								</thead>`);
		tbl_leads.DataTable({
		responsive: true,
		// searchDelay: 500,
		processing: true,
		serverSide: true,
		searching: true,
		ajax: {
			    url: "/leads/getLeads",
			    type: 'POST',
			    headers: {
			        'X-CSRF-TOKEN': '{{ csrf_token() }}'
			    },
			    data: {
			          // parameters for custom backend script demo
			            "groups" : groups,
			            "status" : status,
			            "date" : date,
			            "collector" : collector,
			       },
			  },
		order: [[ 6, "desc" ]],
		columns: [
			        
			    {data: 'action',orderable: false},
			    {data: 'status',orderable: false},
			    {data: 'assign_user',orderable: false},
			    {data: 'account_number',orderable: false},
			    {data: 'full_name',orderable: false},
			    {data: 'assign_group',orderable: false},
			    {data: 'created_at',orderable: false},
			],
		});
	}

	

	KTApp.unblockPage();
}
func_tbl_leads(0, '', 0, 0);

 function getUserList(group){
		$.ajax({
		        url: "/summary_calls/get_user_list",
		        type: "POST",
		        data: 'group='+group,
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		          $('#filter_collector').empty().append('<option value="">Select Collector</option>');
		          jQuery.each(data.get_data, function(k,val){     
                    $('#filter_collector').append($('<option>', { 
                        value: val.id,
                        text : val.name 
                    }));
                  
                  });
		                                    
		       }
		 });
}

function getStatus(group){
		$.ajax({
		        url: "/leads/get_group_status",
		        type: "POST",
		        data: 'group='+group+'&except=none',
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		        
			      $('#filter_status').empty().append('<option value="">Select Status</option>');
			      
			      jQuery.each(data.get_data, function(k,val){     
	                  	$('#filter_status').append($('<option>', { 
	                        value: val.name,
	                        text : val.name 
	                  }));
                  
                  });
		                                    
		       }
		 });
}

$('#ptp_date').daterangepicker({
	buttonClasses: ' btn',
	applyClass: 'btn-primary',
	cancelClass: 'btn-secondary',

	singleDatePicker: true,
	showDropdowns: true,
	locale: {
		format: 'MM/DD/YYYY'
	}
	}, function(start, end, label) {
		   $('#ptp_date .form-control').val( start.format('YYYY-MM-DD') );
		   var status = $("#filter_status").val();
		   var collector = $("#filter_collector").val();
		   var groups = $("#filter_groups").val();
		   var date = start.format('YYYY-MM-DD');
		   $('#tbl_leads').DataTable().destroy();
		   func_tbl_leads(groups, status, collector, date);
});

jQuery(document).on('click', '#btn-reload-leads-table', function(e){
      jQuery("#tbl_leads").dataTable()._fnAjaxUpdate();
    	
});

jQuery(document).on('click', '#btn-clear-leads-table', function(e){
      var status = $("#filter_status").val("").change();
	  var groups = $("#filter_groups").val("").change();
	  var collector = $("#filter_collector").val("").change();
	  var date = start.format('YYYY-MM-DD');
	  $('#ptp_date .form-control').val(start.format('YYYY-MM-DD'));
   //    $('#tbl_leads').DataTable().destroy();
	  // func_tbl_leads(groups,status, date);	
});

jQuery(document).on('change', '#filter_collector', function(e){
      var collector = $(this).val();
      var groups = $("#filter_groups").val();
      var status 	= $("#filter_status").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

       if(status == '1' || status == '2' || status == '3' || status == '4'){
       		$("#div_ptp_show").css('display','');
       		var date = $('#ptp_date .form-control').val();
       }else{
       		$("#div_ptp_show").css('display','none');
       		var date = 0;
       }

	   
	  $('#tbl_leads').DataTable().destroy();
	  func_tbl_leads(groups, status, collector, date);
    	
});

jQuery(document).on('change', '#filter_groups', function(e){
      var groups = $(this).val();
      var status = $("#filter_status").val();
      var collector = $("#filter_collector").val();
       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

       if(status == '1' || status == '2' || status == '3' || status == '4'){
       		$("#div_ptp_show").css('display','');
       		var date = $('#ptp_date .form-control').val();
       }else{
       		$("#div_ptp_show").css('display','none');
       		var date = 0;
       }
       getUserList(groups);
       getStatus(groups);

		 $('#tbl_leads').DataTable().destroy();
		 func_tbl_leads(groups, status, collector, date);
    	
});

jQuery(document).on('change', '#filter_status', function(e){
      var status = $(this).val();
      var groups = $("#filter_groups").val();
      var collector = $("#filter_collector").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

       if(status == '1' || status == '2' || status == '3' || status == '4'){
       		$("#div_ptp_show").css('display','');
       		var date = $('#ptp_date .form-control').val();
       }else{
       		$("#div_ptp_show").css('display','none');
       		var date = 0;
       }
	   
	   $('#tbl_leads').DataTable().destroy();
		func_tbl_leads(groups, status, collector, date);
    	
});



});
</script>
@endpush