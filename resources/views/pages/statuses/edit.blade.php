<form class="form" id="edit_statuses_form_{{$statuses->id}}">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Edit Status ({{$statuses->status_name}})</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                <div class="mb-2">
	                	 <input type="hidden" class="form-control" name="id" value="{{ $statuses->id }}"> 

	                  <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Status Name</label>
                             <input type="text" class="form-control" name="statuses_name" value="{{ $statuses->status_name }}"> 
                             <span class="form-text text-muted"></span>
                           </div>

                       </div>

                      <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Description</label>
                             <textarea class="form-control" name="description">{{ $statuses->description }}</textarea>
                             <span class="form-text text-muted"></span>
                           </div>

                       </div>
                    
                      <div class="form-group row">
                        <div class="col-lg-12">
                          <label>Group <strong style="color: red;">*</strong></label>
                           <select class="form-control" name="groups" style="width: 100%">
                            <option value="">Choose</option>
                            @foreach ($groups as $group)
                                  <option value="{{$group->id}}" @if($group->id == $statuses->group) selected @endif>{{$group->name}}</option>
                                @endforeach
                           </select>
                           <span class="form-text text-muted"></span>
                         </div>
                      </div>
                    



	                   
	              	</div>


	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_save_edit_statuses_form_{{$statuses->id}}" class="btn btn-primary font-weight-bold">Submit</button>
	            </div>
	        </div>
    	</form>
<script type="text/javascript">
KTUtil.ready(function() {

 $('#kt_select1_{{$statuses->id}}').select2({
   placeholder: "Choose"
  });

  $('#kt_select2_{{$statuses->id}}').select2({
   placeholder: "Choose"
  });

 jQuery(document).on('click', '#btn_save_edit_statuses_form_{{$statuses->id}}', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('edit_statuses_form_{{$statuses->id}}'),
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
		                url: "/statuses/update",
		                type: "POST",
		                data: $("#edit_statuses_form_{{$statuses->id}}").serialize(),
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

		                            	jQuery("#tbl_statuses").dataTable()._fnAjaxUpdate();
		                                $("#edit_{{$statuses->id}}_statuses_modal").modal('toggle');
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