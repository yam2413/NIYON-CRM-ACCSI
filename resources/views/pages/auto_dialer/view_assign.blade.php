@extends('templates.default')
@section('title', 'Assign leads from '.$user->name)
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
									<i class="fas fa-user text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">{{$user->name}} assign for the campaign {{$campaigns->campaign_name}} </h3>
							</div>
							<div class="card-toolbar">
								 <a  href="{{ route('pages.auto_dialer.dialer', ['file_id' => $file_id]) }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
					                <i class="fas fa-arrow-left"></i> Back
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">
							<!--begin::Search Form-->
							<div class="mb-7">
								<div class="row align-items-center">
									<div class="col-lg-9 col-xl-8">
										<div class="row align-items-center">

											<div class="col-md-6 my-2 my-md-0">
												<div class="input-icon">
													<input type="text" class="form-control" placeholder="Search..." id="search_leads" />
													<span>
														<i class="flaticon2-search-1 text-muted"></i>
													</span>
												</div>
											</div>

											<div class="col-md-6 my-2 my-md-0">
												<div class="d-flex align-items-center">
													<label class="mr-3 mb-0 d-none d-md-block">Filter Status:</label>
													<select id="filter_status" class="form-control form-control-solid">
														<option value="0">All</option>
														@foreach ($statuses as $statuse)
															<option value="{{$statuse->status_name}}">{{$statuse->status_name}}</option>
														@endforeach
													</select>
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
										<th>Account Number</th>
										<th>Name</th>
										<th>Outstanding Balance</th>
										<th>Dial</th>
										<th>Processed</th>
										<th>Time Process</th>
										<th>Group</th>
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

	$('#search_leads').on('keyup', function() {
	  jQuery("#tbl_leads").DataTable().search($(this).val()).draw();
	});

	var filter_groups = $("#filter_groups").val();
	var tbl_leads = $('#tbl_leads');
	function func_tbl_leads(status){
			tbl_leads.DataTable({
			responsive: true,
			// searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/auto_dialer/getLeadsAssign",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
				          // parameters for custom backend script demo
				            "file_id" : '{{$file_id}}',
				            "collector" : '{{$id}}',
				            "status" : status,
				       },
				  },
			order: [[ 7, "desc" ]],
			columns: [
				        
				    {data: 'action',orderable: false},
				    {data: 'status',orderable: false},
				    {data: 'assign_user',orderable: false},
				    {data: 'account_number',orderable: false},
				    {data: 'full_name',orderable: false},
				    {data: 'outstanding_balance',orderable: false},
				    {data: 'dial',orderable: false},
				    {data: 'process',orderable: false},
				    {data: 'process_time',orderable: false},
				    {data: 'assign_group',orderable: false},
				    {data: 'created_at',orderable: false},
				],
			});
		

		

		KTApp.unblockPage();
	}
	func_tbl_leads(0);

	jQuery(document).on('change', '#filter_status', function(e){
      var status = $(this).val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

      
	   
	   $('#tbl_leads').DataTable().destroy();
		func_tbl_leads(status);
    	
});

});
</script>
@endpush