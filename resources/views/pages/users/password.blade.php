<form class="form" id="edit_pass_form_{{$users->id}}">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Change Password ({{$users->name}})</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                <div class="mb-2">
	                	 <input type="hidden" class="form-control" name="id" value="{{ $users->id }}"> 


                      <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Password <strong style="color: red;">*</strong></label>
                             <input type="password" class="form-control" name="password" value=""> 
                             <span class="form-text text-muted"></span>
                           </div>

                       </div>
	                   
	              	</div>


	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_save_edit_pass_form_{{$users->id}}" class="btn btn-primary font-weight-bold">Update</button>
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

 jQuery(document).on('click', '#btn_save_edit_pass_form_{{$users->id}}', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('edit_pass_form_{{$users->id}}'),
        {
         fields: {
         	 password: {
                validators: {
                    notEmpty: {
                          message: 'password is required'
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

            passwordStrength: new FormValidation.plugins.PasswordStrength({
                            field: 'password',
                            message: 'The password is weak',
                            minimalScore: 1,
                            onValidated: function(valid, message, score) {
                            switch (score) {
                                case 0:
                                    passwordMeter.style.width = randomNumber(1, 20) + '%';
                                    passwordMeter.style.backgroundColor = '#ff4136';
                                case 1:
                                    passwordMeter.style.width = randomNumber(20, 40) + '%';
                                    passwordMeter.style.backgroundColor = '#ff4136';
                                    break;
                                case 2:
                                    passwordMeter.style.width = randomNumber(40, 60) + '%';
                                    passwordMeter.style.backgroundColor = '#ff4136';
                                    break;
                                case 3:
                                    passwordMeter.style.width = randomNumber(60, 80) + '%';
                                    passwordMeter.style.backgroundColor = '#ffb700';
                                    break;
                                case 4:
                                    passwordMeter.style.width = '100%';
                                    passwordMeter.style.backgroundColor = '#19a974';
                                    break;
                                default:
                                    break;
                            }
                        },
                      }),
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
		                url: "/users/update_pass",
		                type: "POST",
		                data: $("#edit_pass_form_{{$users->id}}").serialize(),
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
		                                $("#edit_{{$users->id}}_pass_modal").modal('toggle');
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