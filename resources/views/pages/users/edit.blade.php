<form class="form" id="edit_user_form_{{$users->id}}">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Edit Personal Information ({{$users->name}})</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                <div class="mb-2">
	                	 <input type="hidden" class="form-control" name="id" value="{{ $users->id }}"> 

	                  <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Coll Code</label>
                             <input type="text" class="form-control" name="coll" value="{{ $users->coll }}"> 
                             <span class="form-text text-muted"></span>
                           </div>

                       </div>

                      <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Extension <strong style="color: red;">*</strong></label>
                             <input type="text" class="form-control" name="extension" value="{{ $users->extension }}"> 
                             <span class="form-text text-muted"></span>
                           </div>

                       </div>

	                  <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Username <strong style="color: red;">*</strong></label>
                             <input type="text" class="form-control" name="email" value="{{ $users->email }}"> 
                             <span class="form-text text-muted"></span>
                           </div>

                      </div>

	                  <div class="form-group row">

                          <div class="col-lg-12">
                            <label>First Name <strong style="color: red;">*</strong></label>
                             <input type="text" class="form-control" name="firstname" value="{{ $users->firstname }}"> 
                             <span class="form-text text-muted"></span>
                           </div>

                       </div>

                       <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Last Name <strong style="color: red;">*</strong></label>
                             <input type="text" class="form-control" name="lastname" value="{{ $users->lastname }}"> 
                             <span class="form-text text-muted"></span>
                           </div>

                       </div>

                    @if (Auth::user()->level == 0)
                      <div class="form-group row">
                        <div class="col-lg-12">
                          <label>Group <strong style="color: red;">*</strong></label>
                           <select class="form-control" name="groups" style="width: 100%">
                            <option value="">Choose</option>
                            @foreach ($groups as $group)
                                  <option value="{{$group->id}}" @if($group->id == $users->group) selected @endif>{{$group->name}}</option>
                                @endforeach
                           </select>
                           <span class="form-text text-muted"></span>
                         </div>
                      </div>
                    @endif

	                  <div class="form-group row">
	                      <div class="col-lg-12">
	                        <label>Access Level <strong style="color: red;">*</strong></label>
	                         <select class="form-control" name="level" style="width: 100%">
	                         	
	                         	@if (Auth::user()->level == 0)
                                  <option value="">Choose</option>
                                  <option value="0" @if($users->level == '0') selected @endif>System Administrator</option>
                                  <option value="1" @if($users->level == '1') selected @endif>Admin</option>
                                  <option value="2" @if($users->level == '2') selected @endif>Manager</option>
                                @endif
            					<option value="3" @if($users->level == '3') selected @endif>User</option>
	                         </select>
	                         <span class="form-text text-muted"></span>
	                       </div>
	                   </div>


	                   
	              	</div>


	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_save_edit_user_form_{{$users->id}}" class="btn btn-primary font-weight-bold">Submit</button>
	            </div>
	        </div>
    	</form>
<script type="text/javascript">
KTUtil.ready(function() {

 $('#kt_select1_{{$users->id}}').select2({
   placeholder: "Choose"
  });

  $('#kt_select2_{{$users->id}}').select2({
   placeholder: "Choose"
  });

 jQuery(document).on('click', '#btn_save_edit_user_form_{{$users->id}}', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('edit_user_form_{{$users->id}}'),
        {
         fields: {
         	email: {
						validators: {
							notEmpty: {
								message: 'Email is required'
							},
						}
					},
            coll: {
                validators: {
                    notEmpty: {
                      message: 'Coll Code is required'
                    },
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
            extension: {
                validators: {
                    notEmpty: {
                          message: 'Extension is required'
                    },
                }
            },
            groups: {
                validators: {
                    notEmpty: {
                          message: 'Group is required'
                    },
                }
            },
            level: {
                validators: {
                    notEmpty: {
                          message: 'Access level is required'
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
		                url: "/users/update",
		                type: "POST",
		                data: $("#edit_user_form_{{$users->id}}").serialize(),
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

		                            	jQuery("#tbl_users").dataTable()._fnAjaxUpdate();
		                                $("#edit_{{$users->id}}_users_modal").modal('toggle');
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