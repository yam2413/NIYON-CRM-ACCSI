@extends('templates.default')
@section('title', 'Create User')
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
											<h3 class="card-label">Create Group</h3>
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_groups">
												@csrf
												<div class="card-body">

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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Group Name *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="name" placeholder="" value="" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Description *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<textarea class="form-control" name="description"></textarea>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Members</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<select class="form-control select2" id="kt_select2_6" name="users[]" style="width: 100%" multiple="multiple">
																<option value="">Choose</option>
															</select>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Color Palette</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input id="simple-color-picker" name="color_palette" type="text" class="form-control" value="#8f3596"/>
															<span class="form-text text-muted"></span>
														</div>
													</div>
													
													

												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-groups"><i class="fa fa-save"></i> Submit</button>
															<button type="reset" class="btn btn-light-primary font-weight-bold" id="clear-groups"><i class="flaticon-cancel"></i> Cancel
															</button>
															<a href="{{ route('pages.groups.index') }}" class="btn btn-light-primary font-weight-bold" data-dismiss="modal"><i class="flaticon2-back"></i> Back</a>
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
// Class definition
var KTSelect2 = function() {
 // Private functions
 var demos = function() {
  // basic
   // input group and left alignment setup
		  $('#kt_daterangepicker_2').daterangepicker({
		   buttonClasses: ' btn',
		   applyClass: 'btn-primary',
		   cancelClass: 'btn-secondary'
		  }, function(start, end, label) {
		   $('#kt_daterangepicker_2 .form-control').val( start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD'));
		  });

  // loading remote data

  function formatRepo(repo) {
   if (repo.loading) return repo.text;
   var markup = "<div class='select2-result-repository clearfix'>" +
    "<div class='select2-result-repository__meta'>" +
    "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";
   if (repo.description) {
    markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
   }
   markup += "<div class='select2-result-repository__statistics'>" +
    "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
    "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
    "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
    "</div>" +
    "</div></div>";
   return markup;
  }

  function formatRepoSelection(repo) {
   return repo.full_name || repo.text;
  }

  $("#kt_select2_6").select2({
   placeholder: "Search users name",
   allowClear: true,
   ajax: {
    url: "/groups/getUsers",
    dataType: 'json',
    delay: 250,
    data: function(params) {
     return {
      q: params.term, // search term
      page: params.page
     };
    },
    processResults: function(data, params) {
     // parse the results into the format expected by Select2
     // since we are using custom formatting functions we do not need to
     // alter the remote JSON data, except to indicate that infinite
     // scrolling can be used
     params.page = params.page || 1;

     return {
      results: data.items,
      pagination: {
       more: (params.page * 30) < data.total_count
      }
     };
    },
    cache: true
   },
   escapeMarkup: function(markup) {
    return markup;
   }, // let our custom formatter work
   minimumInputLength: 1,
  });

  // custom styles

  
 }

 // Public functions
 return {
  init: function() {
   demos();
  }
 };
}();
KTUtil.ready(function() {
KTSelect2.init();
$('#simple-color-picker').colorpicker();
jQuery(document).on('click', '#save-groups', function(e){
    e.preventDefault();

    var validation;

    const passwordMeter = document.getElementById('passwordMeter');
    
    const randomNumber = function(min, max) {
         return Math.floor(Math.random() * (max - min + 1) + min);
    };
    

    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_groups'),
        {
         fields: {

            name: {
                validators: {
                    notEmpty: {
                      message: 'Group name is required'
                    },
                }
            },
            description: {
                validators: {
                    notEmpty: {
                      message: 'Description is required'
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
		                url: "/groups/store",
		                type: "POST",
		                data: $("#new_form_groups").serialize(),
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