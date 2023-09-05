@extends('templates.default')
@section('title', 'Pullout Accounts\'s')
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
									<i class="fas fa-sign-out-alt text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Pullout the accounts that you need to remove in leads.</h3>
							</div>
							<div class="card-toolbar">
								<a  href="#" data-toggle="modal" data-target="#modal_pullout_logs" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
							        <i class="fas fa-file-import"></i> Download Pullout Logs
							    </a>
							
							</div>
						</div>

						<div class="card-body">
							
							<div class="alert alert-custom alert-white alert-shadow fade show mb-5" role="alert">
								<div class="alert-icon">
									<i class="flaticon-warning"></i>
								</div>
								<div class="alert-text">Note: Once you pullout account here it will permanently delete. Even the ptp and call history of the account will also be deleted. It's recommended to backup the CRM database in case you incorrectly pullout the accounts.</div>
							</div>
							
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="manual-tab" data-toggle="tab" href="#manual">
										<span class="nav-icon">
											<i class="far fa-list-alt"></i>
										</span>
										<span class="nav-text">Manual Pullout Accounts</span>
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="import-tab" data-toggle="tab" href="#import" aria-controls="import">
										<span class="nav-icon">
											<i class="fas fa-file-import"></i>
										</span>
										<span class="nav-text">Import to Pullout</span>
									</a>
								</li>
															
							</ul>
							
							<div class="tab-content mt-5" id="myTabContent">
								<div class="tab-pane fade active show" id="manual" role="tabpanel" aria-labelledby="manual-tab">
									@include('pages.pullout.manual')
								</div>
								<div class="tab-pane fade" id="import" role="tabpanel" aria-labelledby="import-tab">
									@include('pages.pullout.import')
								</div>
							</div>	
							

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
<div class="modal fade" id="modal_pullout_logs" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal_pullout_logs" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pullout Logs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
            	<div class="alert alert-custom alert-white alert-shadow fade show mb-5" role="alert">
					<div class="alert-icon">
						<i class="flaticon-warning"></i>
					</div>
					<div class="alert-text">See bulk files that have previously been exported. Files are available to download for 4 days.</div>
				</div>
                <!--begin: Datatable-->
				<table id="tbl_pullout_logs" class="table table-bordered table-hover table-checkable">
					<thead>
						<tr>
							<th></th>
							<th>File ID</th>
							<th>Status</th>
							<th>Pullout Type</th>
							<th>Created By</th>
							<th>Created Date</th>
						</tr>
					</thead>
				</table>
				<!--end: Datatable-->

            </div>
            <div class="modal-footer">
            	<button type="button" id="btn-refresh-pullout-logs" class="btn btn-light-primary font-weight-bold">Reload</button>
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {
	var tbl_leads = $('#tbl_leads');
	var tbl_pullout_logs = $('#tbl_pullout_logs');
	var select_import_groups = $("#select_import_groups").val();
	// single file upload


	tbl_pullout_logs.DataTable({
		responsive: true,
		searchDelay: 500,
		processing: true,
		serverSide: true,
		pageLength: 5,
		searching: false,
		lengthChange: false,
		ajax: {
			    url: "/pullout/getPulloutLogs",
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
			    {data: 'pullout_type',orderable: false},
			    {data: 'user',orderable: false},
			    {data: 'created_at',orderable: false},
			],
		});

	function func_tbl_leads(groups, collector, status){
		
		tbl_leads.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/pullout/getLeadsList",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
				          // parameters for custom backend script demo
				            "groups" : groups,
				            "collector" : collector,
				            "status" : status,
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
							$("#btn_pullout_accounts").prop('disabled', false);
						}
						else {
							$(this).prop('checked', false);
							$(this).closest('tr').removeClass('active');
							$("#btn_pullout_accounts").prop('disabled', 'disabled');
						}
					});
				});

				tbl_leads.on('change', 'tbody tr .checkbox', function() {
					$(this).parents('tr').toggleClass('active');
					var set = $(this).parents('tr').find('input[type="checkbox"]');
					if(set.is(':checked')){
						$("#btn_pullout_accounts").prop('disabled', false);
					}else{
						$("#btn_pullout_accounts").prop('disabled', 'disabled');
					}
					

				});
		

		KTApp.unblockPage();
	}

	func_tbl_leads(0,0,'');

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

	function getStatus(group){
		$.ajax({
		        url: "/leads/get_group_status",
		        type: "POST",
		        data: 'group='+group,
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		        
			      $('#filter_status').empty().append('<option value="">Select Status</option>');
			      
			      jQuery.each(data.get_data, function(k,val){     
	                  	$('#filter_status').append($('<option>', { 
	                        value: val.name,
	                        text : val.name 
	                  }));
                  
                  });
		                                    
		       }
		 });
	}

	jQuery(document).on('click', '#btn_pullout_all_accounts', function(e){
      	var groups 	= $("#filter_groups").val();
      	var groups_name = $("#filter_groups option:selected").text();

      	Swal.fire({
	        title: "Are you sure, you want to pullout all the accounts from "+groups_name+"?",
	        text: "This will be permanently remove and wont be able to revert this! - you will able to downloads the exported pullout accounts by clicking Yes.",
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
				    url: "/pullout/update_pullout_all",
				    type: "POST",
				    data: 'group_id='+groups+'&groups_name='+groups_name,
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
		                            jQuery("#tbl_leads").dataTable()._fnAjaxUpdate();
		                            jQuery("#tbl_pullout_logs").dataTable()._fnAjaxUpdate();
		                            $('#modal_pullout_logs').modal('toggle')
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

	

	jQuery(document).on('change', '#select_import_groups', function(e){
		$("#div-upload-imported-pullout").css('display','');
		$('#file_upload_pullout').dropzone({
        url: "/pullout/import_pullout", // Set the url for your upload script location
        paramName: "file", // The name that will be used to transfer the file
        headers: {
		      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		params: {
         group_id: $("#select_import_groups").val()
    	},
        maxFiles: 1,
        maxFilesize: 5, // MB
        addRemoveLinks: true,
        acceptedFiles: "text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        accept: function(file, done) {
            KTApp.blockPage({
                overlayColor: '#000000',
                state: 'primary',
                 message: 'Processing...'
            });
           done();
        },success: function(file, response){
			      //Here you can get your response.
			     if(response.error == 'false'){
			      	KTApp.unblockPage();
			      	swal.fire({
		                text: response.msg,
		                icon: "success",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
		                customClass: {
		                    confirmButton: "btn font-weight-bold btn-light-primary"
		                    },onClose: function(e) {
		                       
		                    }
		                }).then(function() {
		                    KTUtil.scrollTop();
		                });

			      }else{
			      	KTApp.unblockPage();
			      	swal.fire({
		                text: response.msg,
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
	});

	jQuery(document).on('change', '#filter_groups', function(e){
      var groups 	= $(this).val();
      var collector = $("#filter_collector").val();
      var status 	= $("#filter_status").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

        if(groups == ''){
        	$("#btn_pullout_all_accounts").prop('disabled', 'disabled');
        }else{
        	$("#btn_pullout_all_accounts").prop('disabled', false);
        }
       	
        getUserList(groups);
        getStatus(groups);
		$('#tbl_leads').DataTable().destroy();
		func_tbl_leads(groups,collector,status);
    	
	});

	jQuery(document).on('change', '#filter_collector', function(e){
      var collector = $(this).val();
      var groups 	= $("#filter_groups").val();
      var status 	= $("#filter_status").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	   $('#tbl_leads').DataTable().destroy();
		func_tbl_leads(groups,collector,status);
    	
	});

	jQuery(document).on('change', '#filter_status', function(e){
       var collector = $("#filter_collector").val();
       var groups 	= $("#filter_groups").val();
       var status 	= $(this).val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	   $('#tbl_leads').DataTable().destroy();
		func_tbl_leads(groups,collector,status);

    });	

	jQuery(document).on('click', '#btn-refresh-pullout-logs', function(e){
       jQuery("#tbl_pullout_logs").dataTable()._fnAjaxUpdate();
    	
	});

	jQuery(document).on('click', '#btn_pullout_accounts', function(e){

	Swal.fire({
	        title: "Are you sure, you want to pullout this selected accounts?",
	        text: "This will be permanently remove and wont be able to revert this! - you will able to downloads the exported pullout accounts by clicking Yes.",
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
	         var groups 	= $("#filter_groups").val();
			  $.ajax({
				    url: "/pullout/update_pullouts",
				    type: "POST",
				    data: 'id='+searchIDs+'&group_id='+groups,
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
		                            jQuery("#tbl_leads").dataTable()._fnAjaxUpdate();
		                            jQuery("#tbl_pullout_logs").dataTable()._fnAjaxUpdate();
		                            $('#modal_pullout_logs').modal('toggle')
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