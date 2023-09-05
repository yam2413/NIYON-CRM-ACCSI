@extends('templates.default')
@section('title', 'Groups')
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
									<i class="fas fa-users text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Manage your group settings and view group members.</h3>
							</div>
							<div class="card-toolbar">
								 <a  href="{{ route('pages.groups.create') }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
					                <i class="flaticon2-plus"></i> Add Group
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_groups" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Action</th>
										<th>Name</th>
										<th>Description</th>
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

var tbl_groups = $('#tbl_groups');
tbl_groups.DataTable({
	responsive: true,
	searchDelay: 500,
	processing: true,
	serverSide: true,
	searching: true,
	ajax: {
		    url: "/groups/getGroups",
		    type: 'POST',
		    headers: {
		        'X-CSRF-TOKEN': '{{ csrf_token() }}'
		    },
		  },
	order: [[ 4, "desc" ]],
	columns: [
		        
		    {data: 'action',orderable: false},
		    {data: 'name',orderable: false},
		    {data: 'description',orderable: false},
		    {data: 'create_by',orderable: false},
		    {data: 'created_at',orderable: false},
		],
	});


	jQuery(document).on('click', '.edit_groups', function(e){
			var id = $(this).attr('id');
			$( "#modaly_body_"+id ).load( "/groups/edit/"+id, function() {});
	});

	jQuery(document).on('click', '.delete_groups', function(e){
		var id = $(this).attr('id');
		 Swal.fire({
	        title: "Are you sure?",
	        text: "You won't be able to revert this!",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes, delete it!"
	    }).then(function(result) {
	        if (result.value) {

	        	$.ajax({
		            url: "/groups/delete",
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
			                                jQuery("#tbl_groups").dataTable()._fnAjaxUpdate();
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