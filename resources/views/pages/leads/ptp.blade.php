<!--begin::Row-->
	<div class="row">
		<div class="col-lg-4">


			<!--begin::Card-->
			<div class="card card-custom gutter-b example example-compact">
				<!--begin::Form-->
					<form class="form">
						<div class="card-body">
							<div class="form-group row">
								
								<div class="col-lg-12">
									<label>Account Number/Card Number</label>
									<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->account_number}}" />
								</div>
							</div>

							<div class="form-group row">
								
								<div class="col-lg-12">
									<label>Outstanding Balance</label>
									<input type="text" class="form-control" disabled="disabled" value="{{number_format($crm_leads->outstanding_balance,2)}}" />
								</div>
							</div>

							@foreach ($file_headers as $file_header)

								<div class="form-group row">
									
									<div class="col-lg-12">
										<label>{{ str_replace('_',' ',strtoupper($file_header->field_name)) }}</label>

										@switch($file_header->data_type)
											@case('amount')
												<input type="text" class="form-control" disabled="disabled" value="{{number_format($temp_datas[$file_header->order_no], 2)}}" />
											@break
												
											@default
												<input type="text" class="form-control" disabled="disabled" value="{{$temp_datas[$file_header->order_no]}}" />
										@endswitch
										
										
									</div>
								</div>
								
							@endforeach
							
							
							{{-- <div class="form-group row">
								<div class="col-lg-12">
									<label>Total Loan Amount(Grand Total)</label>
									<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->loan_amount}}" />
								</div>
							</div>
																
							<div class="form-group row">
								<div class="col-lg-12">
									<label>Outstanding Balance</label>
									<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->outstanding_balance}}" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-12">
									<label>Promise to Pay Date</label>
									<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->payment_date}}" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-12">
									<label>Promise to Pay Amount</label>
									<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->ptp_amount}}" />
								</div>
							</div>

							<div class="form-group row">
								
								<div class="col-lg-12">
									<label>Endo Date</label>
									<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->endo_date}}" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-12">
									<label>Due Date</label>
									<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->due_date}}" />
								</div>
							</div> --}}

							

						</div>
					</form>
				<!--end::Form-->
			</div>
			<!--end::Card-->
	</div>

	<div class="col-lg-8">

			<!--begin::Card-->
				<div class="card card-custom">
											
											<div class="card-header">

												<div class="card-title">
													<span class="card-icon">
														<i class="fas fa-history text-primary"></i>
													</span>
													<h3 class="card-label">View and manage account payments</h3>
												</div>
												<div class="card-toolbar">
													<a href="#" id="button_ask_to_call" class="btn btn-light-primary font-weight-bold"><i class="fas fa-plus"></i> Add New Entry</a>
												</div>
											</div>
											
											<div class="card-body">
												
												<!--begin: Datatable-->
												<table id="tbl_ptp_histories" class="table table-bordered table-hover table-checkable">
													<thead>
														<tr>
															<th>Added By</th>
															<th>Disposition</th>
															<th>PTP Date</th>
															<th>Payment Amount</th>
															<th>Remarks</th>
															<th>Created Date</th>
														</tr>
													</thead>
												</table>
												<!--end: Datatable-->
												
											</div>

				</div>
			<!--end::Card-->
			
	</div>


</div>
<!--end::Row-->


<!-- Modal-->
<div class="modal fade" id="modal_add_entry" tabindex="-1" role="dialog" aria-labelledby="modal_add_entry" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    	<form class="form" id="add_new_entry_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Add New Entry</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
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
							        <option value="Hang up/Can't be reached">Hang up/Can't be reached</option>
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

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_save_new_entry" class="btn btn-primary font-weight-bold"><i class="fas fa-save"></i> Save</button>
	            </div>
	        </div>
	    </form>
    </div>
</div>