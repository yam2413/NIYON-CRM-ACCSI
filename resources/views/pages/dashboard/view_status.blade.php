@extends('templates.default')
@section('title', $statuses)
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

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
					<div class="card card-custom">
						
						<div class="card-header">

							<div class="card-title">
								<span class="card-icon">
									<i class="fas fa-bell text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">{{$statuses}}</h3>
							</div>

							{{-- @if(Auth::user()->level != 0) --}}
							<div class="card-toolbar">
								 <a  href="#" id="btn-active-auto-dialer" class="btn btn-primary btn-block btn-shadow-hover font-weight-bold mr-2">
					                <i class="fas fa-file-import"></i> Active Auto Dialer
					            </a>
					
							</div>
							{{-- @endif --}}
						</div>
						
						<div class="card-body">
							<!--begin::Search Form-->
							<div class="mb-7">
								<div class="row align-items-center">
									<div class="col-lg-12 col-xl-11">
										<div class="row align-items-center">

											<div class="col-md-4 my-2 my-md-0">
												<div class="d-flex align-items-center">
													<label class="mr-3 mb-0 d-none d-md-block">Filter Groups:</label>
													<select id="filter_groups" class="form-control form-control-solid">
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

											<div class="col-md-4 my-2 my-md-0">
												<div class="d-flex align-items-center">
													<label class="mr-3 mb-0 d-none d-md-block">Filter Date Status:</label>
													<div class='input-group' id='demograph_date'>
														<input type='text' class="form-control" readonly name="demograph_date"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
														<div class="input-group-append">
															<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
														</div>
													</div>
												</div>
											</div>

											<div class="col-md-4 my-2 my-md-0">
												<div class="d-flex align-items-center">
													<label class="mr-3 mb-0 d-none d-md-block">Filter PTP Date:</label>
													<div class='input-group' id='ptp_date'>
														<input type='text' class="form-control" readonly name="ptp_date"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
														<div class="input-group-append">
															<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
														</div>
													</div>
												</div>
											</div>

										</div>
									</div>
									


								</div>
							</div>
							<div class="mb-7">
								<div class="row align-items-center">
									<div class="col-lg-12 col-xl-11">
										<div class="row align-items-center">

											<div class="col-md-4 my-2 my-md-0">
												<div class="input-icon">
													<input type="text" class="form-control" placeholder="Search..." id="search_leads" />
													<span>
														<i class="flaticon2-search-1 text-muted"></i>
													</span>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>
							<!--end::Search Form-->
							<!--begin: Datatable-->
							<table id="tbl_leads" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th></th>
										<th>Account Status</th>
										<th>Collector</th>
										<th>Name</th>
										<th>Home No.</th>
										<th>CP No.</th>
										<th>Outstanding Balance</th>
										<th>PTP Date</th>
										<th>Last Status Updated</th>
										<th>Created Date</th>
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

 var start = moment().startOf('month');
 var end = moment().endOf('month');
 var date = start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
 $('#demograph_date .form-control').val(date);

$('#search_leads').on('keyup', function() {
  jQuery("#tbl_leads").DataTable().search($(this).val()).draw();
});

var filter_groups = $("#filter_groups").val();
var tbl_leads = $('#tbl_leads');
function func_tbl_leads(groups, status, collector, date, ptp_date){
		tbl_leads.DataTable({
		responsive: true,
		// searchDelay: 500,
		processing: true,
		serverSide: true,
		searching: true,
		ajax: {
			    url: "/dashboard/getLeads",
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
			            "ptp_date" : ptp_date,
			       },
			  },
		order: [[ 8, "desc" ]],
		columns: [
			        
			    {data: 'action',orderable: false},
			    {data: 'status',orderable: false},
			    {data: 'assign_user',orderable: false},
			    {data: 'full_name',orderable: false},
			    {data: 'home_no',orderable: false},
			    {data: 'cellphone_no',orderable: false},
			    {data: 'outstanding_balance',orderable: true},
			    {data: 'payment_date',orderable: true},
			    {data: 'status_updated',orderable: true},
			    {data: 'created_at',orderable: true},
			],
		});
	

	

	KTApp.unblockPage();
}
func_tbl_leads(filter_groups, '{{$statuses}}', 0, date, '');

jQuery(document).on('change', '#filter_groups', function(e){
		var filter_groups = $(this).val();
		var date = $('#demograph_date .form-control').val();
		var ptp_date = $('#ptp_date .form-control').val();
		$('#tbl_leads').DataTable().destroy();
		func_tbl_leads(filter_groups, '{{$statuses}}', 0, date, ptp_date);
 });

	$('#demograph_date').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',

            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
        	var date = start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
        	var filter_groups = $("#filter_groups").val();
            $('#demograph_date .form-control').val(date);
            var ptp_date = $('#ptp_date .form-control').val();

            $('#tbl_leads').DataTable().destroy();
			func_tbl_leads(filter_groups, '{{$statuses}}', 0, date, ptp_date);
    });

    $('#ptp_date').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',

            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
        	var ptp_date = start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
        	var filter_groups = $("#filter_groups").val();
            $('#ptp_date .form-control').val(ptp_date);
            var date = $('#demograph_date .form-control').val();

            $('#tbl_leads').DataTable().destroy();
			func_tbl_leads(filter_groups, '{{$statuses}}', 0, date, ptp_date);
    });

    jQuery(document).on('click', '#btn-active-auto-dialer', function(e){
    	var date = $('#demograph_date .form-control').val();
		e.preventDefault();
		var str = "This auto dialer will cover only all the {{$statuses}} leads between "+date+".<br><br> <p style='color:red;'>The account's last status update is today and will not cover this active dialer.</p>";
    	Swal.fire({
	        title: "Are you sure you want to run this {{$statuses}} leads on auto dialer?",
	        html: str,
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
					data: 'log_type=login&status={{$statuses}}&date='+date,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: "json",
					success: function(data) {
						KTApp.unblockPage();
						var filter_groups = $("#filter_groups").val();
						var link = '{{ route('pages.agent.status_dialer', ['group_id' => ':group_id', 'statuses' => $statuses, 'date' => ':date']) }}';
						link = link.replace(':group_id', filter_groups);
						link = link.replace(':date', date);

						window.location = link;
							          	 

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