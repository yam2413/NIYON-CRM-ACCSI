@extends('templates.default')
@section('title', 'Dialer Settings')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-8">

					<!--begin::Card-->
					<div class="card card-custom">
						<div class="card-header">
							<div class="card-title">
								Auto Dialer Settings
							</div>
						</div>

						<div class="card-body">
										
							<!--begin::Form-->
							<form class="form" id="new_form_auto_dialer">
								@csrf
								<div class="card-body">


									<div class="form-group row">
										<label class="col-form-label text-right col-lg-5 col-sm-12">Allow to confirm a call before the collector start the dial. <button type="button" class="btn btn-icon btn-circle btn-sm mr-2" data-toggle="popover" title="Image Reference from Auto Dialer" data-html="true" data-content='<img alt="Logo" src="{{ asset('media/bg/confirm_call_auto_dialer.jpg') }}"  />'><i class="fas fa-info-circle"></i></button></label>
										<div class="col-lg-3 col-md-6 col-sm-12">
											<input data-switch="true" id="dialer_confirm_call" @if(env('DIALER_CONFIRM_CALL') == 'true') checked="checked" @endif  type="checkbox" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
											<span class="form-text text-muted"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label text-right col-lg-5 col-sm-12">Allow the collector to use pause time.</label>
										<div class="col-lg-3 col-md-6 col-sm-12">
											<input data-switch="true" id="dialer_coll_pause"  @if(env('DIALER_COLL_PAUSE') == 'true') checked="checked" @endif type="checkbox" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
											<span class="form-text text-muted"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label text-right col-lg-5 col-sm-12">Allow the manager to create campaign.</label>
										<div class="col-lg-3 col-md-6 col-sm-12">
											<input data-switch="true" id="dialer_m_allow_campaign" @if(env('DIALER_M_ALLOW_CAMPAIGN') == 'true') checked="checked" @endif type="checkbox" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
											<span class="form-text text-muted"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label text-right col-lg-5 col-sm-12">Allow the manager acess the voice monitoring.</label>
										<div class="col-lg-3 col-md-6 col-sm-12">
											<input data-switch="true" id="dialer_m_access_voice_m" @if(env('DIALER_M_ACCESS_VOICE_M') == 'true') checked="checked" @endif type="checkbox" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" />
											<span class="form-text text-muted"></span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label text-right col-lg-5 col-sm-12">
										How many minutes/sec before the next call engage.
										</label>
										<div class="col-lg-3 col-md-9 col-sm-12">
											<select class="form-control" id="dialer_timecall_engage" name="dialer_timecall_engage">
												<option value="1000" @if(env('DIALER_TIMECALL_ENGAGE') == '1000') selected @endif>10 Seconds</option>
												<option value="30000" @if(env('DIALER_TIMECALL_ENGAGE') == '30000') selected @endif>30 Seconds</option>
												<option value="45000" @if(env('DIALER_TIMECALL_ENGAGE') == '45000') selected @endif>45 Seconds</option>
												<option value="60000" @if(env('DIALER_TIMECALL_ENGAGE') == '60000') selected @endif>1 Minute</option>
											</select>
											<span class="form-text text-muted"></span>
										</div>
									</div>

								</div>

								<div class="card-footer">
									<div class="row">
										<div class="col-lg-9 ml-lg-auto">
											<button type="submit" class="btn btn-primary font-weight-bold mr-2" id="save-auto-dialer">
												<i class="fa fa-save"></i> Save
											</button>
											<button type="reset" class="btn btn-light-primary font-weight-bold" id="clear-auto-dialer">
												<i class="flaticon-cancel"></i> Cancel
											</button>
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

jQuery(document).on('click', '#save-auto-dialer', function(e){
    e.preventDefault();

    var validation;
    
    validation = FormValidation.formValidation(
        KTUtil.getById('new_form_auto_dialer'),
        {
         fields: {
                    
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

                    var dialer_confirm_call = $('#dialer_confirm_call').bootstrapSwitch('state');
                	var dialer_coll_pause = $('#dialer_coll_pause').bootstrapSwitch('state');
                	var dialer_m_allow_campaign = $('#dialer_m_allow_campaign').bootstrapSwitch('state');
                	var dialer_m_access_voice_m = $('#dialer_m_access_voice_m').bootstrapSwitch('state');


                     $.ajax({
		                url: "/dialer/update",
		                type: "POST",
		                data: $("#new_form_auto_dialer").serialize()+'&dialer_confirm_call='+dialer_confirm_call+'&dialer_coll_pause='+dialer_coll_pause+'&dialer_m_allow_campaign='+dialer_m_allow_campaign+'&dialer_m_access_voice_m='+dialer_m_access_voice_m,
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