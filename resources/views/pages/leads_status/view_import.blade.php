@extends('templates.default')
@section('title', 'View Imported Accounts')
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
									<i class="fas fa-exchange-alt text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">View all the account and change the status.</h3>
							</div>
							<div class="card-toolbar">
								 <a  href="{{ route('pages.leads_status.index') }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
					                <i class="fas fa-arrow-left"></i> Exit
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<div class="row">

								<div class="col-xl-3">
									<button type="button" id="btn_update_all_accounts" class="btn btn-secondary btn-block">
										<i class="fas fa-save"></i> Update All Account
									</button>					
								</div>	

											

							</div>
							<hr>
							<table id="tbl_leads" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
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

					</div>
					<!--end::Card-->


				</div>
			</div>
		<!--end::Row-->


	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
<!-- Modal-->
<div class="modal fade" id="modal-show-form-status" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="form" id="add_new_entry_form">
            	<div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Select New Status of the account</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                <input type="hidden" name="type_value" id="type_value">
	                <div class="mb-2">
	                	<div class="col-lg-12">
				            <label>New Status <span style="color:red;">*</span></label>
				             <select class="form-control form-control-lg" id="new_status" name="new_status">
							 </select>
				             <span class="form-text text-muted"></span>
				        </div>

	                </div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="save-new-statuses" class="btn btn-primary font-weight-bold">Save changes</button>
	            </div>
            </form>
        </div>
    </div>
</div>

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

	var tbl_leads = $('#tbl_leads');
	function func_tbl_leads(){
		
		tbl_leads.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/leads_status/get_imported_list",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
				          // parameters for custom backend script demo
				            "file_id" : '{{$file_id}}',
				            "group_id" : '{{$group_id}}',
				       },
				  },
			order: [[ 5, "desc" ]],
			columns: [
				    {data: 'status',orderable: false},
				    {data: 'assign_user',orderable: false},
				    {data: 'account_number',orderable: false},
				    {data: 'full_name',orderable: false},
				    {data: 'assign_group',orderable: false},
				    {data: 'created_at',orderable: false},
				],
			});
		

		KTApp.unblockPage();
	}

	func_tbl_leads();

	function getStatus(){
		$.ajax({
		        url: "/leads/get_group_status",
		        type: "POST",
		        data: 'group={{$group_id}}&except=BEST TIME TO CALL',
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		        
			      $('#filter_status').empty();
			      
			      jQuery.each(data.get_data, function(k,val){  

	                  	$('#new_status').append($('<option>', { 
	                        value: val.name,
	                        text : val.name 
	                   }));
	                  	
                  
                  });
		                                    
		       }
		 });
	}

	jQuery(document).on('click', '#btn_update_all_accounts', function(e){

		$("#modal-show-form-status").modal('toggle');
		$("#type_value").val('all');
		getStatus();

	});	

	jQuery(document).on('click', '#save-new-statuses', function(e){
	    var new_status 	= $("#new_status").val();
	    var type_value 	= $("#type_value").val();

	    var txt_warn = "Are you sure, you want to update the status of all accounts to "+new_status+" ?";

	Swal.fire({
	        title: txt_warn,
	        //text: "You won't be able to revert this!",
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
				    url: "/leads_status/update_status",
				    type: "POST",
				    data: 'id=0&group_id={{$group_id}}&new_status='+new_status+'&old_status=0&date=0&collector=0&type=import&file_id={{$file_id}}',
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
		                            $("#modal-show-form-status").modal('toggle');
		                            jQuery("#tbl_leads").dataTable()._fnAjaxUpdate();
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