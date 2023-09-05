<!--begin::Card-->
	<div class="card card-custom gutter-b example example-compact">
		<!--begin::Form-->
		<h3>Outstanding Balance: {{number_format((float)$crm_leads->outstanding_balance,2)}}</h3>
		<form class="form" id="add_manual_numbers_form">
			<div class="card-body">
				<hr>
				
					<div class="form-group row">

				        <div class="col-lg-12">
				            <label><strong>LIST OF AVAILABLE CONTACT NO. <span style="color:red;">*</span></strong></label>
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
				            <span class="form-text text-muted"></span>
				        </div>
				            
				    </div>

													
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-lg-9 ml-lg-auto">
						<button type="button" class="btn btn-primary btn-lg btn-block" id="manual_call"><i class="fas fa-phone-alt"></i> Call</button>
					</div>
				</div>
			</div>
		</form>
		<!--end::Form-->
	</div>
<!--end::Card-->