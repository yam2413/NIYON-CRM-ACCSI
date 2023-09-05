<div class="row">
		
		<div class="col-lg-6">
			

			<!--begin::Card-->
			<div class="card card-custom gutter-b example example-compact">
				<!--begin::Form-->
					<form class="form">
						<div class="card-body">
							
							<div class="form-group row">
								
								<div class="col-lg-12">
									<label><strong>Account Status</strong></label>:
									 @if($crm_leads->status == 'BEST TIME TO CALL')
									 	<div class="h1">{{$crm_leads->status}}</div>
									 	<small>Call me at: {{date("F d, Y g:i a", strtotime($crm_leads->best_time_to_call))}}</small>
									 @else
									 	<div class="h1">{{$crm_leads->status}}</div>
									 @endif
								</div>
							</div>
							<div class="form-group row">
								
								<div class="col-lg-12">
									<label><strong>Account Number/Card Number</strong></label>
									<input type="text" class="form-control form-control-lg" disabled="disabled" value="{{$crm_leads->account_number}}" />
								</div>
							</div>

							<div class="form-group row">
								
								<div class="col-lg-12">
									<label><strong>Outstanding Balance</strong></label>
									<input type="text" class="form-control form-control-lg" disabled="disabled" value="{{number_format((float)$crm_leads->outstanding_balance,2)}}" />
								</div>
							</div>

							
							@foreach ($file_headers as $file_header)
								@php
									$label_name = str_replace('_',' ',strtoupper($file_header->field_name));
								@endphp
								<div class="form-group">
									<label><strong>{{ $label_name }}</strong></label>

									@if($file_header->data_type == 'amount')
										<input type="text" class="form-control form-control-lg" disabled="disabled" value="@if($temp_datas[$file_header->order_no] == '') {{number_format($temp_datas[$file_header->order_no], 2)}} @else 0 @endif" />
									
									@elseif(strpos(strtolower($label_name), 'remarks') !== false)
										<textarea class="form-control form-control-lg" disabled="disabled">{{$temp_datas[$file_header->order_no]}}</textarea>

									@elseif(strpos(strtolower($label_name), 'address') !== false)
										<textarea class="form-control form-control-lg" disabled="disabled">{{$temp_datas[$file_header->order_no]}}</textarea>

									@else
										<input type="text" class="form-control form-control-lg" disabled="disabled" value="{{$temp_datas[$file_header->order_no]}}" />
									@endif
									
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

	<div class="col-lg-6">

			<!--begin::Card-->
				<div class="card card-custom">
											
											<div class="card-header">

												<div class="card-title">
													<span class="card-icon">
														<i class="fas fa-history text-primary"></i>
													</span>
													<h3 class="card-label">View and manage account payments</h3>
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
{{-- <div class="modal fade" id="modal_call_again" tabindex="-1" role="dialog" aria-labelledby="modal_call_again" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    	<form class="form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Manual Call</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                <div class="mb-2">

	                	<select id="call_contact_no" class="form-control">

								@foreach ($contact_no_lists as $key => $contact_no_list)
									@if($contact_no_list == '' || $contact_no_list == '--')
										@php
											continue;
										@endphp
									@endif
									<option value="{{$contact_no_list}}">{{$contact_no_list}} ({{$key}})</option>
								@endforeach
						</select>
		                



	              	</div>

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="manual_call" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6"><i class="fas fa-phone-alt"></i> Call</button>
	            </div>
	        </div>
	    </form>
    </div>
</div> --}}


<!-- Modal-->
{{-- <div class="modal fade" id="modal_add_entry" tabindex="-1" role="dialog" aria-labelledby="modal_add_entry" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    	<form class="form" id="add_new_entry_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-edit icon-2x mr-5"></i> Add New Entry</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
	                
	                 

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
	                <button type="button" id="btn_save_new_entry" class="btn btn-primary font-weight-bold"><i class="fas fa-save"></i> Save</button>
	            </div>
	        </div>
	    </form>
    </div>
</div> --}}