@extends('templates.default')
@section('title', 'Update Leads')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
								<div class="card card-custom gutter-b">
									<div class="card-header">
										<div class="card-title">
											<h3 class="card-label">Update Leads</h3>
										</div>
										<div class="card-toolbar">
										 <a  href="#" data-toggle="modal" data-target="#modal_upload_logs" class="btn btn-sm btn-light-primary font-weight-bold">
							                <i class="fas fa-file-import"></i> Upload Logs
							            </a>
							
									</div>
									</div>
									<!--begin::Form-->
									<form>
										<div class="card-body">

											<div class="alert alert-dark" role="alert">
												<p><i class="fas fa-info-circle"></i> In this module, You can upload an existing account to update the latest loan details of the account. Including the total amount , Oustanding Amount , Past Due and Etc.</p>
											</div>

											<div class="alert alert-dark" role="alert">
												<p>Notes:</p>
												<ol>
													<li> Please select an Excel File (up to 5 MB).</li>
													<li> Only 25 Column from the excile file will be include.</li>
													<li> System will automatically select the first row as header.</li>
													<li> Make sure the column with date it is formatted by YYYY-MM-DD .</li>
													<li> The column with mobile number must be start with 09 with 11 digit (Example: 09171234567).</li>
												</ol>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12 text-lg-right"></label>
												<div class="col-lg-6 col-md-9 col-sm-12">
													<div class="dropzone dropzone-default" id="kt_dropzone_1">
														<div class="dropzone-msg dz-message needsclick">
															<h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
															<span class="dropzone-msg-desc"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
									<!--end::Form-->
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
<div class="modal fade" id="modal_upload_logs" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal_upload_logs" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Logs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                			<div class="alert alert-dark" role="alert">
								<p>Notes:</p>
									<ul>
										<li><span class="label label-xl label-warning label-inline mr-2">In Progress</span> - The uploaded file is uploading in the server.</li>
										<li><span class="label label-xl label-primary label-inline mr-2">Validation&nbsp;</span> - The uploaded file is ready to validate the column by assign the right fields and validate the right data type of each column.</li>
										<li><span class="label label-xl label-secondary label-inline mr-2">Synching&nbsp;&nbsp;&nbsp;</span> - The uploaded file is inserting in the leads database.</li>
										<li><span class="label label-xl label-success label-inline mr-2">Success&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> - The uploaded file is successfully added in the leads database.</li>
										<li><span class="label label-xl label-danger label-inline mr-2">Cancelled&nbsp;</span> - The uploaded file is cancelled.</li>
										<li><a href="#" class="btn btn-success btn-sm mr-3"><i class="fas fa-file-download" title="Download Success"></i><a/> - Total No. of success. you can download by clicking the button icon.</li>
										<li><a href="#" class="btn btn-danger btn-sm mr-3"><i class="fas fa-file-download" title="Download Error"></i><a/> - Total No. of error. you can download by clicking the button icon.</li>
									</ul>
							</div>
                <!--begin: Datatable-->
							<table id="tbl_upload_logs" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Action</th>
										<th>File ID</th>
										<th>Upload Type</th>
										<th>Status</th>
										<th>Total Uploads</th>
										<th>Total Success</th>
										<th>Total Error</th>
										<th>Upload By</th>
										<th>Created Date</th>
									</tr>
								</thead>
							</table>
							<!--end: Datatable-->

            </div>
            <div class="modal-footer">
            	<button type="button" id="btn-refresh-upload-logs" class="btn btn-light-primary font-weight-bold">Refresh</button>
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

	// single file upload
    $('#kt_dropzone_1').dropzone({
        url: "/file_uploads/upload_file", // Set the url for your upload script location
        paramName: "file", // The name that will be used to transfer the file
        headers: {
		      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
		                       jQuery("#tbl_upload_logs").dataTable()._fnAjaxUpdate();
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


    var tbl_upload_logs = $('#tbl_upload_logs');
	tbl_upload_logs.DataTable({
		responsive: true,
		searchDelay: 500,
		processing: true,
		serverSide: true,
		pageLength: 5,
		searching: true,
		ajax: {
			    url: "/file_uploads/getUploadLogs",
			    type: 'POST',
			    headers: {
			        'X-CSRF-TOKEN': '{{ csrf_token() }}'
			    },
			  },
		order: [[ 8, "desc" ]],
		columns: [
			        
			    {data: 'action',orderable: false},
			    {data: 'file_id',orderable: false},
			    {data: 'upload_type',orderable: false},
			    {data: 'status',orderable: false},
			    {data: 'total_upload',orderable: false},
			    {data: 'total_success',orderable: false},
			    {data: 'total_error',orderable: false},
			    {data: 'user',orderable: false},
			    {data: 'created_at',orderable: false},
			],
		});

	jQuery(document).on('click', '#btn-refresh-upload-logs', function(e){
       jQuery("#tbl_upload_logs").dataTable()._fnAjaxUpdate();
    	
	});

	jQuery(document).on('click', '.btn_undo_upload', function(e){

	var file_id = $(this).attr('id');
	Swal.fire({
	        title: "Are you sure to undo this uploaded files in leads?",
	        text: "You wont be able to revert this and this file will considered as cancel status",
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
		        url: "/file_uploads/undo_upload_file",
		        type: "POST",
		        data: 'file_id='+file_id,
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
		                         jQuery("#tbl_upload_logs").dataTable()._fnAjaxUpdate();  
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