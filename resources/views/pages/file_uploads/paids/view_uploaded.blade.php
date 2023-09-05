@extends('templates.default')
@section('title', 'Activity Log\'s')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
					<div class="card card-custom card-sticky" id="kt_page_sticky_card">
						
						<div class="card-header">

							<div class="card-title">
								<span class="card-icon">
									<i class="fas fa-info-circle text-primary"></i>
								</span>
								<h3 class="card-label">Please review before you submit to paid accounts, insure you assign the correct fields.</h3>
							</div>
							<div class="card-toolbar">
								<ul class="nav nav-tabs nav-bold nav-tabs-line">
										<li class="nav-item">
											 <a href="/upload_paids/check_uploads/{{$uniq_id}}/?" class="btn btn-light-primary font-weight-bold"><i class="fas fa-arrow-left"></i> Back</a>
										</li>
										<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
										<li class="nav-item">
											 <button type="button" id="submit-to-sync" class="btn btn-primary font-weight-bold"><i class="fas fa-save"></i> Update to Paid Accounts</button>
										</li>
								</ul>
								
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_view_uploaded" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Card/Account Number</th>
										<th>Paid Date</th>
										<th>Paid Amount</th>
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
$('#tbl_view_uploaded').DataTable().destroy();
var tbl_view_uploaded = $('#tbl_view_uploaded');
tbl_view_uploaded.DataTable({
	responsive: true,
	searchDelay: 500,
	processing: true,
	serverSide: true,
	searching: false,
	ajax: {
		    url: "/upload_paids/getUploadedPaidLeads",
		    type: 'POST',
		    headers: {
		        'X-CSRF-TOKEN': '{{ csrf_token() }}'
		    },
		    data: {
		          // parameters for custom backend script demo
		            "account_no" : '{{request()->get('account_no')}}',
		            "paid_date" : '{{request()->get('paid_date')}}',
		            "paid_amount" : '{{request()->get('paid_amount')}}',
		            "uniq_id" : '{{$uniq_id}}',
		       },
		  },
	columns: [
		        
		    {data: 'account_no',orderable: false},
		    {data: 'paid_amount',orderable: false},
		    {data: 'paid_date',orderable: false},
		],
	});

 jQuery("#tbl_view_uploaded").dataTable()._fnAjaxUpdate();
	jQuery(document).on('click', '#submit-to-sync', function(e){
	Swal.fire({
	        title: "Are you sure?",
	        text: "You wont be able to revert this!",
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
		        url: "/upload_paids/sync_paids_leads",
		        type: "POST",
		        data: {
		          // parameters for custom backend script demo
		          	"groups" : '{{request()->get('groups')}}',
		            "account_no" : '{{request()->get('account_no')}}',
		            "paid_amount" : '{{request()->get('paid_amount')}}',
		            "paid_date" : '{{request()->get('paid_date')}}',
		            "file_id" : '{{$uniq_id}}',
		       	},
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		            KTApp.unblockPage();
		                                    
		           }
		        }); 

		        swal.fire({
		                    text: 'The upload file is now syncing in the lead list.',
		                    icon: "success",
		                    buttonsStyling: false,
		                    confirmButtonText: "Ok, got it!",
		                    customClass: {
		                        confirmButton: "btn font-weight-bold btn-light-primary"
		                    },onClose: function(e) {
		                         var url = '{{ route('pages.file_uploads.paids.index') }}';
		                         window.location = url;      
		                    }
		                   }).then(function() {
		                        KTUtil.scrollTop();
		                   });      
	        }
	    });

	});	


});
</script>
@endpush