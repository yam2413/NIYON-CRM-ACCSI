@extends('templates.default')
@section('title', 'Update Campaign '.$campaigns->campaign_name)
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
											<h3 class="card-label">Update Campaign</h3>
										</div>
									</div>
									<div class="card-body">
										
										<!--begin::Form-->
											<form class="form" id="new_form_campaign">
												@csrf
												<input type="hidden" name="id" value="{{ $campaigns->id }}">
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
														<label class="col-form-label text-right col-lg-3 col-sm-12">Campaign Name *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" name="campaign_name" placeholder="" value="{{ $campaigns->campaign_name }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>
{{-- 
													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Select account One day before promise date.</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<input data-switch="true" id="one_day_before"  @if($campaigns->one_day_before == '1') checked="checked" @endif  type="checkbox" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
															<span class="form-text text-muted">If this enabled, all the selected leads with status PTP, BPTP and BP will be called One before their promise date.</span>
															<span class="form-text text-muted">By default the selected leads with status PTP, BPTP and BP will be call on the day promise date or After the promise date.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Prioritize New Leads.</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<input data-switch="true" id="prioritize_new_leads" @if($campaigns->prioritize_new_leads == '1') checked="checked" @endif type="checkbox" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
															<span class="form-text text-muted">If this enabled, all the selected leads with status New will be prioritize in the call until the last assign New leads.</span>
															<span class="form-text text-muted">By default will be randomized call status.</span>
														</div>
													</div> --}}

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Auto Assign Account.</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<input data-switch="true" id="auto_assign" @if($campaigns->auto_assign == '1') checked="checked" @endif  type="checkbox" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
															<span class="form-text text-muted">If this enabled, all the selected leads with COLL CODE will be automatic assign to the collector.</span>
														</div>
													</div>

													{{-- <div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Filter By Endo Date</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<select name="filter_endo_date" class="form-control">
																<option value="1" @if($campaigns->filter_endo_date == '1') selected @endif>Ascending order</option>
																<option value="2" @if($campaigns->filter_endo_date == '2') selected @endif>Descending order</option>
															</select>
															<span class="form-text text-muted">Ascending order means to arrange numbers in increasing order, that is, from smallest to largest</span>
															<span class="form-text text-muted">Descending order begins with the greatest or largest and ends with the least or smallest The states are listed in descending order of population size.</span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Filter By Cycle Day</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<select name="filter_cycle_day" class="form-control">
																<option value="1" @if($campaigns->filter_cycle_day == '1') selected @endif>High to Low Value</option>
																<option value="2" @if($campaigns->filter_cycle_day == '2') selected @endif>Low to High Value</option>
															</select>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													 --}}
													 <div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Filter By Outstanding Balance</label>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<select name="filter_outstanding_balance" class="form-control">
																<option value="1" @if($campaigns->filter_outstanding_balance == '1') selected @endif>High to Low Value</option>
																<option value="2" @if($campaigns->filter_outstanding_balance == '2') selected @endif>Low to High Value</option>
															</select>
															<span class="form-text text-muted"></span>
														</div>
													</div>

													@if (Auth::user()->level == 0)
														<div class="form-group row">
															<label class="col-form-label text-right col-lg-3 col-sm-12">Group *</label>
															<div class="col-lg-9 col-md-9 col-sm-12">
																<select class="form-control select2" name="groups">
																	<option></option>
																	@foreach ($groups as $group)
																		<option value="{{$group->id}}" @if($group->id == $campaigns->group) selected @endif>{{$group->name}}</option>
																	@endforeach
																	
																</select>
																<span class="form-text text-muted"></span>
															</div>
														</div>
													@endif

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">Start Time *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" id="start_time" name="start_time" placeholder="" value="{{ $campaigns->start_time }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-form-label text-right col-lg-3 col-sm-12">End Time *</label>
														<div class="col-lg-9 col-md-9 col-sm-12">
															<input type="text" class="form-control" id="end_time" name="end_time" placeholder="" value="{{ $campaigns->end_time }}" />
															<span class="form-text text-muted"></span>
														</div>
													</div>

													

												</div>
												<div class="card-footer">
													<div class="row">
														<div class="col-lg-9 ml-lg-auto">
															<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-campaign"><i class="fa fa-save"></i> Save</button>
															<button type="reset" class="btn btn-light-primary font-weight-bold" id="clear-campaign"><i class="flaticon-cancel"></i> Cancel
															</button>
															<a href="{{ route('pages.auto_dialer.dialer', ['file_id' => $file_id]) }}" class="btn btn-light-primary font-weight-bold" data-dismiss="modal"><i class="flaticon2-back"></i> Back</a>
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

$('.select2').select2({
   placeholder: "Select a group"
});
$('[data-switch=true]').bootstrapSwitch();
$('#start_time, #end_time').timepicker();

jQuery(document).on('click', '#save-campaign', function(e){
    e.preventDefault();

    var validation;


    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_campaign'),
        {
         fields: {
            campaign_name: {
                validators: {
                    notEmpty: {
                      message: 'Campaign name is required'
                    },
                }
            },
            start_time: {
                validators: {
                    notEmpty: {
                      message: 'Start time is required'
                    },
                }
            },
            end_time: {
                validators: {
                    notEmpty: {
                          message: 'End time is required'
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

                     var one_day_before = $('#one_day_before').bootstrapSwitch('state');
                     var prioritize_new_leads = $('#prioritize_new_leads').bootstrapSwitch('state');
                     var auto_assign = $('#auto_assign').bootstrapSwitch('state');

                     $.ajax({
		                url: "/auto_dialer/update",
		                type: "POST",
		                data: $("#new_form_campaign").serialize()+'&one_day_before='+one_day_before+'&prioritize_new_leads='+prioritize_new_leads+'&auto_assign='+auto_assign,
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