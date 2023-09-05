@extends('templates.default')
@section('title', 'Bulk Update Leads Status')
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
									<i class="fas fa-exchange-alt text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">View all the account and change the status.</h3>
							</div>
							<div class="card-toolbar">
								 <a  href="{{ route('pages.leads_status.import') }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
					                <i class="fas fa-file-import"></i> Import Account
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">
							<!--begin::Search Form-->
							

							<form class="mb-15">
								<div class="row mb-6">

									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Groups:</label>
										<select id="filter_groups" class="form-control form-control-lg">
											<option value="0">Select Group</option>
											@if(Auth::user()->level == '0')
															
												@foreach ($groups as $key => $group)
													<option value="{{$group->id}}">{{$group->name}}</option>
												@endforeach
																	 		
											@else
												<option value="{{Auth::user()->group}}">{{\App\Models\Groups::usersGroup(Auth::user()->group)}}</option>
											@endif
										</select>
									</div>
									
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Collector:</label>
										<select id="filter_collector" class="form-control form-control-lg">
											<option value="0">Select Collector</option>
										</select>
									</div>
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Status Date:</label>
										<select id="filter_status" class="form-control form-control-lg">
											<option value="0">Select Status</option>
										</select>
									</div>
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Status Date:</label>
										<div class='input-group' id='demograph_date'>
											<input type='text' class="form-control" readonly name="demograph_date"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
											<div class="input-group-append">
												<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
											</div>
										</div>
									</div>
								</div>
							</form>
							<!--end::Search Form-->
							<!--begin: Datatable-->
							<div class="alert alert-dark" role="alert">
								<p>The list of account will depend on filter category.</p>
												
							</div>
							<hr>
							<div class="row">

								<div class="col-xl-3">
									<button type="button" id="btn_update_accounts" class="btn btn-secondary btn-block" style="display: none;">
										<i class="fas fa-sign-out-alt"></i> Update Selected Account
									</button>					
								</div>

								<div class="col-xl-3">
									<button type="button" id="btn_update_all_accounts" class="btn btn-secondary btn-block" disabled="true">
										<i class="fas fa-save"></i> Update All Account
									</button>					
								</div>	

											

							</div>
							<hr>
							<table id="tbl_leads" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Record ID</th>
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

 var start = moment();
 var end = moment();
 var date = start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
 $('#demograph_date .form-control').val(date);

	var tbl_leads = $('#tbl_leads');
	function func_tbl_leads(groups, collector, status, date){
		
		tbl_leads.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/leads_status/getLeadsList",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
				          // parameters for custom backend script demo
				            "groups" : groups,
				            "collector" : collector,
				            "status" : status,
				            "date" : date,
				       },
				  },
			order: [[ 6, "desc" ]],
			headerCallback: function(thead, data, start, end, display) {
					thead.getElementsByTagName('th')[0].innerHTML = `
	                    <label class="checkbox checkbox-single">
	                        <input type="checkbox" value="" class="group-checkable"/>
	                        <span></span>
	                    </label>`;
					},
			columns: [
				        
				    {data: 'id'},
				    {data: 'status',orderable: false},
				    {data: 'assign_user',orderable: false},
				    {data: 'account_number',orderable: false},
				    {data: 'full_name',orderable: false},
				    {data: 'assign_group',orderable: false},
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

			tbl_leads.on('change', '.group-checkable', function() {
					var set = $(this).closest('table').find('td:first-child .checkable');
					var checked = $(this).is(':checked');

					$(set).each(function() {
						if (checked) {
							$(this).prop('checked', true);
							$(this).closest('tr').addClass('active');
							//$("#btn_update_accounts").prop('disabled', false);
							$("#btn_update_accounts").css('display', '');
							$("#btn_update_all_accounts").prop('disabled', 'disabled');
						}
						else {
							$(this).prop('checked', false);
							$(this).closest('tr').removeClass('active');
							//$("#btn_update_accounts").prop('disabled', 'disabled');
							$("#btn_update_accounts").css('display', 'none');
							$("#btn_update_all_accounts").prop('disabled', false);
						}
					});
				});

				tbl_leads.on('change', 'tbody tr .checkbox', function() {
					$(this).parents('tr').toggleClass('active');
					var set = $(this).parents('tr').find('input[type="checkbox"]');
					if(set.is(':checked')){
						//$("#btn_update_accounts").prop('disabled', false);
						$("#btn_update_all_accounts").prop('disabled', 'disabled');
						$("#btn_update_accounts").css('display', '');
					}else{
						//$("#btn_update_accounts").prop('disabled', 'disabled');
						$("#btn_update_all_accounts").prop('disabled', false);
						$("#btn_update_accounts").css('display', 'none');
					}
					

				});
		

		KTApp.unblockPage();
	}

	func_tbl_leads(0,0,0, date);

	function getUserList(group){
		$.ajax({
		    url: "/reassign/get_user_list",
		    type: "POST",
		    data: 'group='+group,
		    headers: {
		       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
		    dataType: "JSON",
		    success: function(data){
		        $('#filter_collector').empty().append('<option value="0">Select Collector</option>');
		        jQuery.each(data.get_data, function(k,val){     
                $('#filter_collector').append($('<option>', { 
                        value: val.id,
                        text : val.name 
                    }));
                  
                });
		                                    
		    }
		 });
	}

	function getStatus(group, callback){
		$.ajax({
		        url: "/leads/get_group_status",
		        type: "POST",
		        data: 'group='+group+'&except=BEST TIME TO CALL',
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		        
			      $('#filter_status').empty();
			      
			      jQuery.each(data.get_data, function(k,val){  
			      		if(val.name == 'NEW'){
			      			callback(val.name);
			      		}   
	                  	$('#filter_status').append($('<option>', { 
	                        value: val.name,
	                        text : val.name 
	                   }));

	                  	$('#new_status').append($('<option>', { 
	                        value: val.name,
	                        text : val.name 
	                   }));
	                  	
                  
                  });
		                                    
		       }
		 });
	}

	jQuery(document).on('change', '#filter_groups', function(e){
      var groups 	= $(this).val();
      var collector = $("#filter_collector").val();
      var status 	= $("#filter_status").val();
      var date 		= $('#demograph_date .form-control').val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

        if(groups == ''){
        	$("#btn_update_all_accounts").prop('disabled', 'disabled');
        }else{
        	$("#btn_update_all_accounts").prop('disabled', false);
        }
       	
        getUserList(groups);
        //getStatus(groups);
        getStatus(groups,function(callback){
        	$('#tbl_leads').DataTable().destroy();
			func_tbl_leads(groups, collector, callback, date);
		});
        
		
    	
	});

	jQuery(document).on('change', '#filter_collector', function(e){
      var collector = $(this).val();
      var groups 	= $("#filter_groups").val();
      var status 	= $("#filter_status").val();
      var date 		= $('#demograph_date .form-control').val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	   $('#tbl_leads').DataTable().destroy();
		func_tbl_leads(groups, collector, status, date);
    	
	});

	jQuery(document).on('change', '#filter_status', function(e){
       var collector 	= $("#filter_collector").val();
       var groups 		= $("#filter_groups").val();
       var status 		= $(this).val();
       var date 		= $('#demograph_date .form-control').val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	    $('#tbl_leads').DataTable().destroy();
		func_tbl_leads(groups, collector, status, date);

    });

    $('#demograph_date').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',

            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
        	var date 		= start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
        	var collector 	= $("#filter_collector").val();
	        var groups 		= $("#filter_groups").val();
	        var status 		= $("#filter_status").val();
            $('#demograph_date .form-control').val(date);

            $('#tbl_leads').DataTable().destroy();
			func_tbl_leads(groups, collector, status, date);
    });

    jQuery(document).on('click', '#btn_update_accounts', function(e){

		$("#modal-show-form-status").modal('toggle');
		$("#type_value").val('selected');

	});	

	jQuery(document).on('click', '#btn_update_all_accounts', function(e){

		$("#modal-show-form-status").modal('toggle');
		$("#type_value").val('all');

	});	


	jQuery(document).on('click', '#save-new-statuses', function(e){
		var groups 		= $("#filter_groups").val();
	    var new_status 	= $("#new_status").val();

	    var date 		= $('#demograph_date .form-control').val();
        var collector 	= $("#filter_collector").val();
	    var status 		= $("#filter_status").val();
	    var type_value 	= $("#type_value").val();

	    if(type_value == 'selected'){
	    	var txt_warn = "Are you sure, you want to update the status of this selected accounts to "+new_status+" ?";
	    }else{
	    	var txt_warn = "Are you sure, you want to update the status of all accounts to "+new_status+" ?";
	    }

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
		             
	         var searchIDs = $("input:checkbox[name=group_id_checkbox]:checked").map(function(){
			      return $(this).val();
			 }).get(); // <----
	         console.log(searchIDs);
	          

			  $.ajax({
				    url: "/leads_status/update_status",
				    type: "POST",
				    data: 'id='+searchIDs+'&group_id='+groups+'&new_status='+new_status+'&old_status='+status+'&date='+date+'&collector='+collector+'&type='+type_value+'&file_id=0',
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
		                            $("#btn_update_accounts").css('display', 'none');
		                            $("#type_value").val('');
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