@extends('templates.default')
@section('title', 'Re-assign')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
					<div class="card card-custom card-sticky" id="kt_page_sticky_card">
						
						<div class="card-header">

							<div class="card-title">
								<span class="card-icon">
									<i class="fas fa-users-cog text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Re-assign the leads account to another collector.</h3>
							</div>
						</div>
						
						<div class="card-body">

							<div class="row">

								<div class="col-xl-3">
									<label>Filter Group</label>
									<select id="filter_groups" class="form-control">
											<option value="">Filter By Group</option>
										@foreach ($groups as $key => $group)
									        <option value="{{$group->id}}">{{$group->name}}</option>
									    @endforeach
									</select>
								</div>

								<div class="col-xl-3">
									<label>Filter Collector</label>
									<select id="filter_collector" class="form-control">
										<option value="">Select Collector</option>
									</select>
								</div>
							</div>


							<div class="card card-custom">
								<div class="card-header">

									<div class="card-title">
										<button type="button" id="btn_reassign_leads" data-toggle="modal" data-target="#modal_reassign_leads" class="btn btn-sm btn-success font-weight-bold" disabled="disabled">
											<i class="flaticon2-refresh-button"></i> Re-Assign
										</button>
									</div>
										
						        	


								</div>
							
							<!--begin: Datatable-->
							<table id="tbl_leads" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Record ID</th>
										<th>Account Status</th>
										<th>Collector</th>
										<th>Account Number</th>
										<th>Name</th>
										<th>Group</th>
										<th>Created Date</th>
									</tr>
								</thead>
							</table>
							<!--end: Datatable-->
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
<div class="modal fade" id="modal_reassign_leads" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    	<form class="form" id="reassign_new_collector_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Select New Collector</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
					<div class="mb-2">

	                   <div class="form-group row">
	                      <div class="col-lg-12">
	                        <label>New Collector</label>
	                        <select id="new_collector" name="new_collector" class="form-control">
								<option value="">Select Collector</option>
							</select>
	                         <span class="form-text text-muted"></span>
	                       </div>
	                   </div>
	              	</div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="save-reassign-new-collector" class="btn btn-primary font-weight-bold">Submit</button>
	            </div>
	        </div>
    	</form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
KTUtil.ready(function() {

var tbl_leads = $('#tbl_leads');

function func_tbl_leads(groups, collector){
	
	tbl_leads.DataTable({
		responsive: true,
		searchDelay: 500,
		processing: true,
		serverSide: true,
		searching: true,
		ajax: {
			    url: "/reassign/getLeadsReAssign",
			    type: 'POST',
			    headers: {
			        'X-CSRF-TOKEN': '{{ csrf_token() }}'
			    },
			    data: {
			          // parameters for custom backend script demo
			            "groups" : groups,
			            "collector" : collector,
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
						$("#btn_reassign_leads").prop('disabled', false);
					}
					else {
						$(this).prop('checked', false);
						$(this).closest('tr').removeClass('active');
						$("#btn_reassign_leads").prop('disabled', 'disabled');
					}
				});
			});

			tbl_leads.on('change', 'tbody tr .checkbox', function() {
				$(this).parents('tr').toggleClass('active');
				var set = $(this).parents('tr').find('input[type="checkbox"]');
				if(set.is(':checked')){
					$("#btn_reassign_leads").prop('disabled', false);
				}else{
					$("#btn_reassign_leads").prop('disabled', 'disabled');
				}
				

			});
	

	KTApp.unblockPage();
}
func_tbl_leads(0,0);

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
		          $('#filter_collector').empty().append('<option value="">Select Collector</option>');
		          jQuery.each(data.get_data, function(k,val){     
                    $('#filter_collector').append($('<option>', { 
                        value: val.id,
                        text : val.name 
                    }));
                  
                  });
		                                    
		       }
		 });
	}

	function getNewUserList(group, user){
		$.ajax({
		        url: "/reassign/get_new_user_list",
		        type: "POST",
		        data: 'group='+group+'&user='+user,
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		          $('#new_collector').empty().append('<option value="">Select New Collector</option>');
		          jQuery.each(data.get_data, function(k,val){     
                    $('#new_collector').append($('<option>', { 
                        value: val.id,
                        text : val.name 
                    }));
                  
                  });
		                                    
		       }
		 });
	}

	jQuery(document).on('change', '#filter_groups', function(e){
      var groups = $(this).val();
      var collector = $("#filter_collector").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

         getUserList(groups);
		 $('#tbl_leads').DataTable().destroy();
		 func_tbl_leads(groups,collector);
    	
	});

	jQuery(document).on('change', '#filter_collector', function(e){
      var collector = $(this).val();
      var groups = $("#filter_groups").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	   $('#tbl_leads').DataTable().destroy();
		func_tbl_leads(groups,collector);
    	
	});

	jQuery(document).on('click', '#btn_reassign_leads', function(e){
		var groups = $("#filter_groups").val();
		var collector = $("#filter_collector").val();
		getNewUserList(groups, collector);
	});

	jQuery(document).on('click', '#save-reassign-new-collector', function(e){
    e.preventDefault();

    var validation;
    validation = FormValidation.formValidation(
        KTUtil.getById('reassign_new_collector_form'),
        {
         fields: {
            new_collector: {
                validators: {
                    notEmpty: {
                      message: 'New Collector is required'
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

                     var new_collector = $("#new_collector").val();

                     $("input:checkbox[name=group_id_checkbox]:checked").each(function(){
					    console.log($(this).val());

					    $.ajax({
					        url: "/reassign/update_reassign",
					        type: "POST",
					        data: 'leads_id='+$(this).val()+'&new_collector='+new_collector,
					        headers: {
					           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					        },
					        dataType: "JSON",
					        success: function(data){
					            //KTApp.unblockPage();
					            jQuery("#tbl_leads").dataTable()._fnAjaxUpdate();
					             $('#modal_reassign_leads').modal('toggle');
					           
					                                    
					           }
					        });
					});

					KTApp.unblockPage();
                    

                   
                }

            });
	});





});
</script>
@endpush