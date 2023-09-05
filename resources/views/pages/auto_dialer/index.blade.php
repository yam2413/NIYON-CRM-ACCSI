@extends('templates.default')
@section('title', 'Auto Dialer')
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
									<i class="fas fa-phone text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Create campaign to engage a auto assign call to your collectors. </h3>
							</div>
							<div class="card-toolbar">
								

								@switch(Auth::user()->level)
								    @case('0')
								        <a  href="{{ route('pages.auto_dialer.create') }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
							                <i class="flaticon2-plus"></i> Create Dialer
							            </a>
								        @break
									
									@case('1')
								    
									    @if(env('DIALER_M_ALLOW_CAMPAIGN') == 'true')
									    	 <a  href="{{ route('pages.auto_dialer.create') }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
								                <i class="flaticon2-plus"></i> Create Dialer
								            </a>
										@endif
								       
								        @break
								    @case('2')
								    
									    @if(env('DIALER_M_ALLOW_CAMPAIGN') == 'true')
									    	 <a  href="{{ route('pages.auto_dialer.create') }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
								                <i class="flaticon2-plus"></i> Create Dialer
								            </a>
										@endif
								       
								        @break
								@endswitch
								
								 
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_auto_dialer_list" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th></th>
										<th>Campaign ID</th>
										<th>Campaign Name</th>
										<th>Status</th>
										<th>Start Time</th>
										<th>End Time</th>
										<th>Group</th>
										<th>Create by</th>
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

var tbl_auto_dialer_list = $('#tbl_auto_dialer_list');
tbl_auto_dialer_list.DataTable({
	responsive: true,
	searchDelay: 500,
	processing: true,
	serverSide: true,
	searching: true,
	ajax: {
		    url: "/auto_dialer/getCampaign",
		    type: 'POST',
		    headers: {
		        'X-CSRF-TOKEN': '{{ csrf_token() }}'
		    },
		  },
	order: [[ 8, "desc" ]],
	columns: [
		        
		    {data: 'action',orderable: false},
		    {data: 'file_id',orderable: false},
		    {data: 'campaign_name',orderable: false},
		    {data: 'active_dialer',orderable: false},
		    {data: 'start_time',orderable: false},
		    {data: 'end_time',orderable: false},
		    {data: 'group',orderable: false},
		    {data: 'created_by',orderable: false},
		    {data: 'created_at',orderable: false},
		],
	});


	jQuery(document).on('click', '.delete_campaign', function(e){
		var id = $(this).attr('id');
		 Swal.fire({
	        title: "Are you sure? You won't be able to revert this!",
	        text: "All the insights, logs, leads and assign collectors will be delete.",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes, delete it!"
	    }).then(function(result) {
	        if (result.value) {

	        	$.ajax({
		            url: "/auto_dialer/delete",
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
			                                jQuery("#tbl_auto_dialer_list").dataTable()._fnAjaxUpdate();
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