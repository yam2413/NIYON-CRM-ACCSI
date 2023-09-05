@extends('templates.default')
@section('title', 'Add Leads')
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
								<h3 class="card-label">Select and Add Leads to Campaign.</h3>
							</div>
							<div class="card-toolbar">
								 <a  href="{{ route('pages.auto_dialer.dialer', ['file_id' => $file_id]) }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
					                <i class="flaticon2-back"></i> Back
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">

							<div class="row">
								<div class="col-xl-3">
										<!--begin::Stats Widget 32-->
										<div class="card card-custom bg-dark card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<i class="fas fa-users icon-2x mr-5"></i>
												</span>
												<span id="demo-total_leads" class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block">0</span>
												<span class="font-weight-bold text-white font-size-sm">Total Leads Added</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 32-->
									</div>
							</div>

							<div class="row">

								<div class="col-xl-3">
									<button type="button" id="btn_add_campaign" class="btn btn-primary btn-lg btn-block" disabled="true">
										<i class="flaticon2-plus"></i> Add to Campaign
									</button>
								</div>

								<div class="col-xl-3">
									<button type="button" id="btn_add_all_campaign" class="btn btn-success btn-lg btn-block">
										<i class="fas fa-reply-all"></i> Add All to Campaign
									</button>
								</div>

								<div class="col-xl-3">
									<select id="filter_status" class="form-control form-control-solid form-control-lg">
										<option value="0">All Status</option>
										@foreach ($filter_status as $key => $status)
											 <option value="{{$status->status_name}}">{{$status->status_name}}</option>
										@endforeach
									</select>
								</div>

							</div>
							<hr>
							<!--begin: Datatable-->
							<table id="tbl_add_leads" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th></th>
										<th>Account No.</th>
										<th>Fullname</th>
										<th>Status</th>
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

var tbl_add_leads = $('#tbl_add_leads');

function func_tbl_leads(status){
	tbl_add_leads.DataTable({
	responsive: true,
	searchDelay: 500,
	processing: true,
	serverSide: true,
	searching: true,
	ajax: {
		    url: "/auto_dialer/getAddLeads",
		    type: 'POST',
		    headers: {
		        'X-CSRF-TOKEN': '{{ csrf_token() }}'
		    },
		     data: {
			    // parameters for custom backend script demo
			    "groups" : "{{$campaigns->group}}",
			    "file_id" : "{{$file_id}}",
			    "status" : status,
			},
		  },
	order: [[ 2, "asc" ]],
	headerCallback: function(thead, data, start, end, display) {
				thead.getElementsByTagName('th')[0].innerHTML = `
                    <label class="checkbox checkbox-single">
                        <input type="checkbox" value="" class="group-checkable"/>
                        <span></span>
                    </label>`;
				},
	columns: [
		    {data: 'id'},
		    {data: 'account_number',orderable: false},
		    {data: 'full_name',orderable: false},
		    {data: 'status',orderable: false},
		    {data: 'created_at',orderable: false},
		],
		columnDefs: [
				{
					targets: 0,
					width: '30px',
					className: 'dt-left',
					orderable: false,
					render: function(data, type, full, meta) {
						return `
                        <label class="checkbox checkbox-single">
                            <input type="checkbox" name="group_id_checkbox" value="`+data+`" class="checkable"/>
                            <span></span>
                        </label>`;
					},
				},
		],
	});
}

	function get_total_added_leads(){
			jQuery.ajax({
		          url: "/auto_dialer/get_total_added_leads",
		          type: "POST",
		          data: 'file_id={{$file_id}}',
		          headers: {
			             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			            },
		          dataType: "json",
		          success: function(data) {
		          	 KTApp.unblockPage();
		          	 $("#demo-total_leads").html(data.total_leads);
		          	 

		          	 if(data.sync_all_leads == 1){
		          	 	$("#btn_add_all_campaign").prop('disabled', true);
		          	 	$("#btn_add_campaign").css('display', 'none');
		          	 	jQuery("#tbl_add_leads").dataTable()._fnAjaxUpdate();	
		          	 }else{
		          	 	$("#btn_add_all_campaign").prop('disabled', false);
		          	 	$("#btn_add_campaign").css('display', '');
		          	 }

		          },
		          error: function(data){


		          }
		    });
	}

	function myTimer() {
	  get_total_added_leads();
	  	
	}
	get_total_added_leads();
	timer_val = setInterval(myTimer, 10000);

func_tbl_leads(0);

	tbl_add_leads.on('change', '.group-checkable', function() {
		var set = $(this).closest('table').find('td:first-child .checkable');
		var checked = $(this).is(':checked');

		$(set).each(function() {
			if (checked) {
				$(this).prop('checked', true);
				$(this).closest('tr').addClass('active');
				$("#btn_add_campaign").prop('disabled', false);
			}else{
				$(this).prop('checked', false);
				$(this).closest('tr').removeClass('active');
				$("#btn_add_campaign").prop('disabled', 'disabled');
			}
		});
	});

	tbl_add_leads.on('change', 'tbody tr .checkbox', function() {
		$(this).parents('tr').toggleClass('active');
		var set = $(this).parents('tr').find('input[type="checkbox"]');
		
		if(set.is(':checked')){
			$("#btn_add_campaign").prop('disabled', false);
		}else{
			$("#btn_add_campaign").prop('disabled', 'disabled');
		}
				

	});

	jQuery(document).on('change', '#filter_status', function(e){
    	e.preventDefault();
    	var status = $(this).val();
    	$('#tbl_add_leads').DataTable().destroy();
    	func_tbl_leads(status);

    });	

    jQuery(document).on('click', '#btn_add_campaign', function(e){
    	e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to add this selected leads to campaigns?",
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

				$("input:checkbox[name=group_id_checkbox]:checked").each(function(){
				    console.log($(this).val());

				    $.ajax({
				        url: "/auto_dialer/add_leads_to_campaign",
				        type: "POST",
				        data: 'file_id={{$file_id}}&leads_id='+$(this).val()+'&campaign_name={{$campaigns->campaign_name}}',
				        headers: {
				           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				        },
				        dataType: "JSON",
				        success: function(data){
				            //KTApp.unblockPage();
				            jQuery("#tbl_add_leads").dataTable()._fnAjaxUpdate();		           
				                                    
				           }
				        });
				});
				get_total_added_leads();
				KTApp.unblockPage();

            	

		             
	        }
	    });

    });	


    jQuery(document).on('click', '#btn_add_all_campaign', function(e){
    	e.preventDefault();
    	Swal.fire({
	        title: "Are you sure you want to add this all leads to campaigns?",
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
				    url: "/auto_dialer/add_all_leads_to_campaign",
				    type: "POST",
				    data: 'file_id={{$file_id}}&campaign_name={{$campaigns->campaign_name}}',
				        headers: {
				           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    dataType: "JSON",
				    success: function(data){
				            //KTApp.unblockPage();
				            get_total_added_leads();
				            jQuery("#tbl_add_leads").dataTable()._fnAjaxUpdate();		           
				                                    
				    }
				});

				KTApp.unblockPage();

            	

		             
	        }
	    });

    });	




});
</script>
@endpush