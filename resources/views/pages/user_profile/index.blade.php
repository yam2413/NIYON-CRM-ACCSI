@extends('templates.default')
@section('title', $user->name)
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Profile Overview-->
		<div class="d-flex flex-row">
			<!--begin::Aside-->
				@include('pages.user_profile.aside')
			<!--end::Aside-->
			
			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">
				
				<!--begin::Card-->
								<div class="card card-custom">
									<div class="card-header">
										<div class="card-title">
											<h3 class="card-label">My Personal Information</h3>
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_users">
												@csrf
												<div class="card-body">
													<input type="hidden" name="id" value="{{$user->id}}">
													<div class="alert alert-custom alert-light-danger d-none" role="alert" id="edit_form_user_msg">
														<div class="alert-icon">
															<i class="flaticon2-information"></i>
														</div>
														<div class="alert-text font-weight-bold">Oh snap! Change a few things up and try submitting again.</div>
														<div class="alert-close">
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span>
																	<i class="ki ki-close"></i>
																</span>
															</button>
														</div>
													</div>

													<div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">My Avatar</label>
                                                    <div class="col-lg-12 col-xl-8">
                                                        <div class="image-input image-input-outline" id="kt_profile_avatar">
                                                            <div class="image-input-wrapper" style="background-image: url(@if($user->avatar == null) {{asset('media/users/default.jpg')}} @else {{asset(Storage::url($user->avatar))}} @endif)"></div>
                                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                                <input type="file" id="avatar" name="avatar" accept=".png, .jpg, .jpeg" />
                                                                <input type="hidden" name="Profile_avatar_remove" />
                                                            </label>
                                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                            </span>
                                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                            </span>
                                                        </div>
                                                        <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                                    </div>
                                                </div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Extension No</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="extension" placeholder="" value="{{$user->extension}}" disabled="disabled" />
															<span class="form-text text-muted">Extension no. will use for connecting your softphone on PBX.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Email *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="email" class="form-control" name="email" placeholder="" value="{{$user->email}}" />
															<span class="form-text text-muted">This will be the username of the owner.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">First Name *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="firstname" placeholder="" value="{{$user->firstname}}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Last Name *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="tex" class="form-control" name="lastname" placeholder="" value="{{$user->lastname}}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Group</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="tex" class="form-control" placeholder="" value="{{$group_name}}" disabled="disabled" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">User Level</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="tex" class="form-control" placeholder="" value="{{$level}}" disabled="disabled" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-users"><i class="fa fa-save"></i> Update</button>
														</div>
													</div>
												</div>
											</form>
											<!--end::Form-->

									</div>
								</div>
								<!--end::Card-->

			</div>
			<!--end::Profile Overview-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Entry-->
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

var avatar = new KTImageInput('kt_profile_avatar');

	jQuery(document).on('click', '#save-users', function(e){
    e.preventDefault();

    var validation;

    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_users'),
        {
         fields: {
         	email: {
						validators: {
							notEmpty: {
								message: 'Email is required'
							},
							// emailAddress: {
							// 	message: 'The value is not a valid email address'
							// }
						}
					},
            lastname: {
                validators: {
                    notEmpty: {
                      message: 'Last name is required'
                    },
                }
            },
            firstname: {
                validators: {
                    notEmpty: {
                      message: 'First name is required'
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

                  //    var data = $("#new_form_users").serialize();
	                 // // You need to use standart javascript object here
	                 // var formDatax = new FormData(formx);

                     
                     // var formx = $("#new_form_users")[0];   
                  	 var form_data = new FormData(); 

                  	 var file_data = $('#avatar').prop('files')[0];                 
                  	 form_data.append('file', file_data);

                  	 var formx = $('#new_form_users').serializeArray();
					$.each(formx,function(key,input){
					    form_data.append(input.name,input.value);
					});

                     $.ajax({
		                url: "/user_profile/update",
		                type: "POST",
		                data: form_data,
		                headers: {
		                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		                },
		                dataType: "JSON",
		                contentType: false,
	                    cache: false,
	                    processData:false,
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