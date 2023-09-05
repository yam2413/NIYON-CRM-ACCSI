@extends('templates.default')
@section('title', 'Users')
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
									<i class="fas fa-user-friends text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">View everyone in your CRM, add and remove user and modify access level.</h3>
							</div>
							<div class="card-toolbar">
								 <a  href="{{ route('pages.users.create') }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
					                <i class="flaticon2-plus"></i> Add User
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_users" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th></th>
										<th>Coll Code</th>
										<th>Extension</th>
										<th>Email</th>
										<th>Name</th>
										<th>Access Level</th>
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

var tbl_users = $('#tbl_users');
tbl_users.DataTable({
	responsive: true,
	searchDelay: 500,
	processing: true,
	serverSide: true,
	searching: true,
	ajax: {
		    url: "/users/getUsers",
		    type: 'POST',
		    headers: {
		        'X-CSRF-TOKEN': '{{ csrf_token() }}'
		    },
		  },
	order: [[ 7, "desc" ]],
	columns: [
		        
		    {data: 'action',orderable: false},
		    {data: 'coll',orderable: false},
		    {data: 'extension',orderable: false},
		    {data: 'email',orderable: false},
		    {data: 'name',orderable: false},
		    {data: 'level',orderable: false},
		    {data: 'group',orderable: false},
		    {data: 'created_at',orderable: false},
		],
	});	


jQuery(document).on('click', '.edit_users', function(e){
		var id = $(this).attr('id');
		$( "#modaly_body_"+id ).load( "/users/edit/"+id, function() {});
});


jQuery(document).on('click', '.edit_pass', function(e){
		var id = $(this).attr('id');
		$( "#modaly_body_pass_"+id ).load( "/users/edit_pass/"+id, function() {});
});


jQuery(document).on('click', '.delete_users', function(e){
		var id = $(this).attr('id');
		 Swal.fire({
	        title: "Are you sure? You won't be able to revert this!",
	        text: "All the assign accounts on this user will be untag and the history logs will be deleted also.",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes, delete it!"
	    }).then(function(result) {
	        if (result.value) {

	        	$.ajax({
		            url: "/users/delete",
		            type: "POST",
		            data: "id="+id,
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
			                                jQuery("#tbl_users").dataTable()._fnAjaxUpdate();
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