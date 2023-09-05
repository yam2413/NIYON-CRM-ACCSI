@extends('templates.default')
@section('title', 'Create File Header')
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
											<h3 class="card-label">Create File Header for {{$groups->name}}</h3>
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
                                        <form>
                                            <div class="card-body">
    
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
    
    // single file upload
    $('#kt_dropzone_1').dropzone({
        url: "/groups/upload_file", // Set the url for your upload script location
        paramName: "file", // The name that will be used to transfer the file
        headers: {
		      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		params: {
         group_id: '{{$id}}'
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
                               var get_campaign = '{{ route('pages.groups.validate', ['id' => $id]) }}';
            				   //get_campaign = get_campaign.replace(':file_id', response.uniq_id);
				               window.location = get_campaign;
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
</script>
@endpush