@extends('templates.default')
@section('title', 'Statuses')
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
									<i class="fas fa-bell text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Add multiple status in groups.</h3>
							</div>
							<div class="card-toolbar">
								 <a  href="#" class="btn btn-primary btn-block btn-shadow-hover font-weight-bold mr-2" data-toggle="modal" data-target="#modal_statuses_import">
					                <i class="fas fa-file-import"></i> Import
					            </a>
					             <a  href="#" class="btn btn-primary btn-block btn-shadow-hover font-weight-bold mr-2" data-toggle="modal" data-target="#modal_statuses_create">
					                <i class="flaticon2-plus"></i> Add Status
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">
							
							<!--begin: Datatable-->
							<table id="tbl_statuses" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th></th>
										<th>Status Name</th>
										<th>Description</th>
										<th>Group</th>
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

<!-- Modal-->
<div class="modal fade" id="modal_statuses_create" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal_statuses_create" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="form" id="new_form_statuses">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-form-label text-right col-lg-3 col-sm-12">Status Name *</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <input type="text" class="form-control" name="statuses_name" placeholder="" value="" />
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-right col-lg-3 col-sm-12">Description</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                           <textarea class="form-control" name="description"></textarea>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label text-right col-lg-3 col-sm-12">Assign Group *</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <select class="form-control select2" name="groups">
                                <option></option>
                                @foreach ($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                                
                            </select>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="button" id="save-new-statuses" class="btn btn-primary font-weight-bold">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal-->
<div class="modal fade" id="modal_statuses_import" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal_statuses_create" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="form" id="new_form_statuses">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-dark" role="alert">
						<p>
							Notes: Make sure to download this template.
						</p>
						
						<a  href="{{ asset('Import_Statuses.xlsx') }}" class="btn btn-secondary btn-shadow-hover font-weight-bold mr-2">
							<i class="fas fa-file-excel"></i> Download Template
						</a>
					</div>

					<div class="col-xl-9">
						<select id="select_import_groups" class="form-control form-control-lg">
								<option value="">Select Groups</option>
									@foreach ($groups as $key => $group)
										<option value="{{$group->id}}">{{$group->name}}</option>
								@endforeach
						</select>						
					</div>
					<hr>
					
					<div class="form-group row" id="div-upload-imported-statuses" style="display: none;">
						<label class="col-form-label col-lg-3 col-sm-12 text-lg-right"></label>
							<div class="col-lg-6 col-md-9 col-sm-12">
								<div class="dropzone dropzone-default" id="file_upload_statuses">
									<div class="dropzone-msg dz-message needsclick">
										<h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
										<span class="dropzone-msg-desc"></span>
									</div>
								</div>
							</div>
					</div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

$('.select2').select2({
   placeholder: "Select a group"
});

var tbl_statuses = $('#tbl_statuses');
tbl_statuses.DataTable({
	responsive: true,
	searchDelay: 500,
	processing: true,
	serverSide: true,
	searching: true,
	ajax: {
		    url: "/statuses/getStatuses",
		    type: 'POST',
		    headers: {
		        'X-CSRF-TOKEN': '{{ csrf_token() }}'
		    },
		  },
	order: [[ 5, "desc" ]],
	columns: [
		        
		    {data: 'action',orderable: false},
		    {data: 'status_name',orderable: false},
		    {data: 'description',orderable: false},
		    {data: 'group',orderable: false},
		    {data: 'added_by',orderable: false},
		    {data: 'created_at',orderable: false},
		],
	});	

jQuery(document).on('click', '.edit_statuses', function(e){
		var id = $(this).attr('id');
		$( "#modaly_body_"+id ).load( "/statuses/edit/"+id, function() {});
});

jQuery(document).on('change', '#select_import_groups', function(e){
		$("#div-upload-imported-statuses").css('display','');
		$('#file_upload_statuses').dropzone({
        url: "/statuses/import_statuses", // Set the url for your upload script location
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
		                       jQuery("#tbl_statuses").dataTable()._fnAjaxUpdate();
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

jQuery(document).on('click', '.delete_statuses', function(e){
		var id = $(this).attr('id');
		 Swal.fire({
	        title: "Are you sure? You won't be able to revert this!",
	        text: "",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes, delete it!"
	    }).then(function(result) {
	        if (result.value) {

	        	$.ajax({
		            url: "/statuses/delete",
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
			                                jQuery("#tbl_statuses").dataTable()._fnAjaxUpdate();
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

jQuery(document).on('click', '#save-new-statuses', function(e){
    e.preventDefault();

    var validation;

    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_statuses'),
        {
         fields: {
            statuses_name: {
                validators: {
                    notEmpty: {
                      message: 'Status Name is required'
                    },
                }
            },
            groups: {
                validators: {
                    notEmpty: {
                          message: 'Assign Groups is required'
                    },
                }
            },
                    
         },

        plugins: { //Learn more: https://formvalidation.io/guide/plugins
            trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
            bootstrap: new FormValidation.plugins.Bootstrap(),
                    // Validate fields when clicking the Submit button
            submitButton: new FormValidation.plugins.SubmitButton(),
             }
                   
            }
        );

            validation.validate().then(function(status) {

                if (status == 'Valid') {


                     KTApp.blockPage({
                      overlayColor: '#000000',
                      state: 'primary',
                      message: 'Processing...'
                     });


                     $.ajax({
		                url: "/statuses/store",
		                type: "POST",
		                data: $("#new_form_statuses").serialize(),
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

		                            	location.reload();
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