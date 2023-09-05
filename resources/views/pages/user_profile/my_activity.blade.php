@extends('templates.default')
@section('title', $user->name)
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Profile Overview-->
		<div class="d-flex flex-row">
			<!--begin::Aside-->
				@include('pages.user_profile.aside')
			<!--end::Aside-->
			
			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">
				<!--begin::Card-->
					<div class="card card-custom">
						
						<div class="card-header">

							<div class="card-title">
								{{-- <span class="card-icon">
									<i class="fas fa-user-friends text-primary icon-3x mr-5"></i>
								</span> --}}
								<h3 class="card-label">My Activity</h3>
							</div>
							<div class="card-toolbar">
								 
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_my_activity" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Actions</th>
										<th>Created Date</th>
									</tr>
								</thead>
							</table>
							<!--end: Datatable-->
							
						</div>

					</div>
					<!--end::Card-->
				

			</div>
			<!--end::Profile Overview-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Entry-->
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

var tbl_my_activity = $('#tbl_my_activity');
tbl_my_activity.DataTable({
	responsive: true,
	searchDelay: 500,
	processing: true,
	serverSide: true,
	searching: false,
	ajax: {
		    url: "/my_activity/getMyactivity",
		    type: 'POST',
		    headers: {
		        'X-CSRF-TOKEN': '{{ csrf_token() }}'
		    },
		  },
	order: [[ 1, "desc" ]],
	columns: [
		        
		    {data: 'actions',orderable: false},
		    {data: 'created_at',orderable: false},
		],
	});	
	

});
</script>
@endpush