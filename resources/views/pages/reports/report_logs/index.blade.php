@extends('templates.default')
@section('title', 'Extract Report Logs')
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
						
						<div class="card-body">
							<div class="alert alert-custom alert-white alert-shadow fade show mb-5" role="alert">
								<div class="alert-icon">
									<i class="flaticon-warning"></i>
								</div>
								<div class="alert-text">See bulk files that have previously been exported. Files are available to download for 4 days.</div>
							</div>
							<!--begin: Datatable-->
							<table id="tbl_report_logs" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th></th>
										<th>File ID</th>
										<th>Status</th>
										<th>Report Type</th>
										<th>Created By</th>
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

	var tbl_report_logs = $('#tbl_report_logs');
	tbl_report_logs.DataTable({
		responsive: true,
		searchDelay: 500,
		processing: true,
		serverSide: true,
		pageLength: 5,
		searching: false,
		lengthChange: false,
		ajax: {
			    url: "/report_logs/get_Report_Logs",
			    type: 'POST',
			    headers: {
			        'X-CSRF-TOKEN': '{{ csrf_token() }}'
			    },
			  },
		order: [[ 5, "desc" ]],
		columns: [
			        
			    {data: 'action',orderable: false},
			    {data: 'file_id',orderable: false},
			    {data: 'status',orderable: false},
			    {data: 'report_type',orderable: false},
			    {data: 'user',orderable: false},
			    {data: 'created_at',orderable: false},
			],
		});
});
</script>
@endpush