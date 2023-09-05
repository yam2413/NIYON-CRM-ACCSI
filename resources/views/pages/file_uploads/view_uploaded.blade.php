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
								<h3 class="card-label">Please review before you submit to sync leads, insure you assign the correct fields.</h3>
							</div>
							<div class="card-toolbar">
								<ul class="nav nav-tabs nav-bold nav-tabs-line">
										<li class="nav-item">
											 <a href="/file_uploads/check_uploads/{{$uniq_id}}/?" class="btn btn-light-primary font-weight-bold"><i class="fas fa-arrow-left"></i> Back</a>
										</li>
										<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
										<li class="nav-item">
											 <button type="button" id="submit-to-sync" class="btn btn-primary font-weight-bold"><i class="fas fa-save"></i> Save to Leads list</button>
										</li>
								</ul>
								
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_view_uploaded" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Coll Code</th>
										<th>Card/Account Number</th>
										<th>Full Name</th>
										<th>Cycle Day</th>
										<th>Primary Address</th>
										<th>Due Date</th>
										<th>Outstanding Balance</th>
										<th>Total Loan Amount</th>
										<th>Endo Date</th>
										<th>Email Address</th>
										<th>Home No.</th>
										<th>Business No.</th>
										<th>Cellphone No.</th>
										<th>Other Phone No 1</th>
										<th>Other Phone No 2</th>
										<th>Other Phone No 3</th>
										<th>Other Phone No 4</th>
										<th>Other Phone No 5</th>
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
		    url: "/file_uploads/getUploadedLeads",
		    type: 'POST',
		    headers: {
		        'X-CSRF-TOKEN': '{{ csrf_token() }}'
		    },
		    data: {
		          // parameters for custom backend script demo
		            "coll_code" : '{{request()->get('coll_code')}}',
		            "account_no" : '{{request()->get('account_no')}}',
		            "full_name" : '{{request()->get('full_name')}}',
		            "cycle_day" : '{{request()->get('cycle_day')}}',
		            "address" : '{{request()->get('address')}}',
		            "due_date" : '{{request()->get('due_date')}}',
		            "outstanding_bal" : '{{request()->get('outstanding_bal')}}',
		            "loan_amount" : '{{request()->get('loan_amount')}}',
		            "uniq_id" : '{{$uniq_id}}',
		            "endo_date" : '{{request()->get('endo_date')}}',
		            "email" : '{{request()->get('email')}}',
		            "home_no" : '{{request()->get('home_no')}}',
		            "business_no" : '{{request()->get('business_no')}}',
		            "cellphone_no" : '{{request()->get('cellphone_no')}}',
		            "other_phone_no_1" : '{{request()->get('other_phone_no_1')}}',
		            "other_phone_no_2" : '{{request()->get('other_phone_no_2')}}',
		            "other_phone_no_3" : '{{request()->get('other_phone_no_3')}}',
		            "other_phone_no_4" : '{{request()->get('other_phone_no_4')}}',
		            "other_phone_no_5" : '{{request()->get('other_phone_no_5')}}',
		       },
		  },
	columns: [
		        
		    {data: 'coll_code',orderable: false},
		    {data: 'account_no',orderable: false},
		    {data: 'full_name',orderable: false},
		    {data: 'cycle_day',orderable: false},
		    {data: 'address',orderable: false},
		    {data: 'due_date',orderable: false},
		    {data: 'outstanding_bal',orderable: false},
		    {data: 'loan_amount',orderable: false},
		    {data: 'endo_date',orderable: false},
		    {data: 'email',orderable: false},
		    {data: 'home_no',orderable: false},
		    {data: 'business_no',orderable: false},
		    {data: 'cellphone_no',orderable: false},
		    {data: 'other_phone_no_1',orderable: false},
		    {data: 'other_phone_no_2',orderable: false},
		    {data: 'other_phone_no_3',orderable: false},
		    {data: 'other_phone_no_4',orderable: false},
		    {data: 'other_phone_no_5',orderable: false},
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
		        url: "/file_uploads/sync_leads",
		        type: "POST",
		        data: {
		          // parameters for custom backend script demo
		          	"groups" : '{{request()->get('groups')}}',
		            "coll_code" : '{{request()->get('coll_code')}}',
		            "account_no" : '{{request()->get('account_no')}}',
		            "full_name" : '{{request()->get('full_name')}}',
		            "cycle_day" : '{{request()->get('cycle_day')}}',
		            "address" : '{{request()->get('address')}}',
		            "due_date" : '{{request()->get('due_date')}}',
		            "outstanding_bal" : '{{request()->get('outstanding_bal')}}',
		            "loan_amount" : '{{request()->get('loan_amount')}}',
		            "file_id" : '{{$uniq_id}}',
		            "endo_date" : '{{request()->get('endo_date')}}',
		            "email" : '{{request()->get('email')}}',
		            "home_no" : '{{request()->get('home_no')}}',
		            "business_no" : '{{request()->get('business_no')}}',
		            "cellphone_no" : '{{request()->get('cellphone_no')}}',
		            "other_phone_no_1" : '{{request()->get('other_phone_no_1')}}',
		            "other_phone_no_2" : '{{request()->get('other_phone_no_2')}}',
		            "other_phone_no_3" : '{{request()->get('other_phone_no_3')}}',
		            "other_phone_no_4" : '{{request()->get('other_phone_no_4')}}',
		            "other_phone_no_5" : '{{request()->get('other_phone_no_5')}}',
		       	},
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		            KTApp.unblockPage();

		            	if(data.error == 'true'){
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

		                    return;
		            	}
		                                    
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
		                         var url = '{{ route('pages.file_uploads.index') }}';
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