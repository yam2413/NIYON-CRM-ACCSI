<!--begin::Card-->
	<div class="card card-custom gutter-b example example-compact">
		<!--begin::Form-->
		<h3>Outstanding Balance: {{number_format((float)$crm_leads->outstanding_balance,2)}}</h3>
		<form class="form" id="add_new_entry_form">
			<div class="card-body">
				<h1>New Entry</h1>
				<p class="mb-0">
					<span class="card-icon">
						<i class="fas fa-info-circle text-primary"></i>
					</span>
					Update call and account status, set new PTP date and amount.
				</p>
				<hr>
				<div class="mb-2">

		                {{-- <div class="form-group row">

				            <div class="col-lg-6">
				                <label>Place of Call <span style="color:red;">*</span></label>
				                <select class="form-control select2" name="place_call" style="width: 100%;">
							        <option value="">Blank</option>
							        <option value="Personal">Personal</option>
							        <option value="Business">Business</option>
							        <option value="Others">Others</option>
							    </select>
				                <span class="form-text text-muted"></span>
				            </div>

				            <div class="col-lg-6">
				                <label>Contact Type <span style="color:red;">*</span></label>
				                <select class="form-control select2" name="contact_type" style="width: 100%;">
							        <option value="">Blank</option>
							        <option value="Primary">Primary</option>
							        <option value="Spouse">Spouse</option>
							        <option value="Authorize Contact Person">Authorize Contact Person</option>
							        <option value="Leave Message">Leave Message</option>
							    </select>
				                <span class="form-text text-muted"></span>
				            </div>

				        </div> --}}


				        <div class="form-group row">

				            <div class="col-lg-12">
				                <label>Call Status <span style="color:red;">*</span></label>
				                <select class="form-control select2" name="call_status" id="call_status" style="width: 100%;">
				                	<option value="">Select Call Status</option>
							        <option value="Answered">Answered</option>
							        <option value="Busy">Busy</option>
							        <option value="Not Getting Service">Not Getting Service</option>
							        <option value="Just Ringing">Just Ringing</option>
							        <option value="Hang up/Cannot be reached">Hang up/Can't be reached</option>
							    </select>
				                <span class="form-text text-muted"></span>
				            </div>
				            
				        </div>

				        <div class="form-group row" id="div-show-ptp-status">

				            
				            
				        </div>

				        <div class="form-group row" id="div-show-ptp-fields">

				            
				            
				        </div>

				        <div class="form-group row" id="div-show-best-time">

				            
				            
				        </div>

				        <div class="form-group row">

				            <div class="col-lg-12">
				                <label>Remarks <span style="color:red;">*</span></label>
				                <textarea name="remarks" class="form-control" rows="5"></textarea>
				                <span class="form-text text-muted"></span>
				            </div>
				            
				        </div>

				        @if(env('FEATURE_SMS') == 'true')
				        <div class="form-group row">

				            <div class="col-lg-6">
				                <label>Send SMS</label>
				                <input data-switch="true" type="checkbox" id="send_sms_ptp" data-on-text="Yes" data-handle-width="70" data-off-text="No" data-on-color="primary" />
				                <span class="form-text text-muted"></span>
				            </div>

				            <div class="col-lg-6" id="div-show-sms-mobile-ptp">
				              
				            </div>
				            
				        </div>
				        @endif


				        @if(env('FEATURE_EMAIL') == 'true')
				        <div class="form-group row">

				            <div class="col-lg-12">
				                <label>Send Email</label>
				                <input data-switch="true" type="checkbox" id="send_email_ptp" data-on-text="Yes" data-handle-width="70" data-off-text="No" data-on-color="primary" />
				                <span class="form-text text-muted"></span>
				            </div>
				            
				        </div>
				        @endif




	              	</div>

	              	<div id="div-call-status-flag"></div>

													
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-lg-9 ml-lg-auto">
						<button type="button" class="btn btn-primary btn-lg btn-block" id="btn_save_new_entry">
							<i class="fas fa-save"></i> Save
					</div>
				</div>
			</div>
		</form>
		<!--end::Form-->
	</div>
<!--end::Card-->