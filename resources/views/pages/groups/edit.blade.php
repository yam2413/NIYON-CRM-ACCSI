<form class="form" id="edit_groups_form_{{$groups->id}}">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Edit Groups Information ({{$groups->name}})</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                <div class="mb-2">
	                	 <input type="hidden" class="form-control" name="id" value="{{ $groups->id }}"> 

	                  <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Group Name *</label>
                             <input type="text" class="form-control" name="name" value="{{$groups->name}}"> 
                             <span class="form-text text-muted"></span>
                           </div>

                    </div>

                    <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Description *</label>
                             <textarea class="form-control" name="description">{{$groups->description}}</textarea>
                             <span class="form-text text-muted"></span>
                           </div>

                    </div>

                    <div class="form-group row">

                        <div class="col-lg-12">
                          <label>Color Palette </label>
                          <input id="simple-color-picker-{{$groups->id}}" name="color_palette" type="text" class="form-control" value="{{$groups->color_palette}}"/>
                           <span class="form-text text-muted"></span>
                         </div>

                  </div>

                    <div class="form-group row">

                          <div class="col-lg-12">
                            <label>Members *</label>
                             <select class="form-control select2" id="kt_select2_{{$groups->id}}" name="users[]" style="width: 100%" multiple="multiple">
                                @foreach ($users as $user)
                                  <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                @endforeach
                              </select>
                             <span class="form-text text-muted"></span>
                           </div>

                    </div>

                   


	                   
	              	</div>


	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_save_edit_groups_form_{{$groups->id}}" class="btn btn-primary font-weight-bold">Submit</button>
	            </div>
	        </div>
    	</form>
<script type="text/javascript">
KTUtil.ready(function() {

  $('#kt_select2_{{$groups->id}}').select2({
   placeholder: "Choose"
  });

  $('#simple-color-picker-{{$groups->id}}').colorpicker();

   $("#kt_select2_{{$groups->id}}").select2({
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


  

 jQuery(document).on('click', '#btn_save_edit_groups_form_{{$groups->id}}', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('edit_groups_form_{{$groups->id}}'),
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
		                url: "/groups/update",
		                type: "POST",
		                data: $("#edit_groups_form_{{$groups->id}}").serialize(),
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

		                            	jQuery("#tbl_groups").dataTable()._fnAjaxUpdate();
		                                $("#edit_{{$groups->id}}_groups_modal").modal('toggle');
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