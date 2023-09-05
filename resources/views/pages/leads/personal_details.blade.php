<!--begin::Card-->
	<div class="card card-custom gutter-b example example-compact">
		<!--begin::Form-->
		<form class="form" id="update_personal_details_form">
			<div class="card-body">
													
				<div class="form-group row">
					<div class="col-lg-6">
						<label>Name</label>
						<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->full_name}}" />
					</div>
					<div class="col-lg-6">
						<label>Birth Day</label>
						<input type="text" class="form-control" disabled="disabled" value="{{$crm_leads->birthday}}" />
					</div>
				</div>

				<div class="form-group row">
					<div class="col-lg-6">
						<label>Address</label>
						<textarea class="form-control" rows="3" name="pd_address">{{$crm_leads->address}}</textarea>
					</div>
					<div class="col-lg-6">
						<label>Email</label>
						<input type="text" class="form-control" name="pd_email" value="{{$crm_leads->email}}" />
					</div>
				</div>

				<div class="form-group row">
					<div class="col-lg-6">
						<label>House Number</label>
						<input type="text" class="form-control contact_no" name="pd_home_no" value="{{$crm_leads->home_no}}" />
					</div>
					<div class="col-lg-6">
						<label>Business Number</label>
						<input type="text" class="form-control contact_no" name="pd_business_no" value="{{$crm_leads->business_no}}" />
					</div>
				</div>

				<div class="form-group row">
					<div class="col-lg-6">
						<label>Cellphone Number</label>
						<input type="text" class="form-control contact_no" name="pd_cp_no" value="{{$crm_leads->cellphone_no}}" />
					</div>
					<div class="col-lg-6">
						<label>Other Phone Number 1</label>
						<input type="text" class="form-control contact_no" name="pd_other_no1" value="{{$crm_leads->other_phone_1}}" />
					</div>
				</div>

				<div class="form-group row">
					<div class="col-lg-6">
						<label>Other Phone Number 2</label>
						<input type="text" class="form-control contact_no" name="pd_other_no2" value="{{$crm_leads->other_phone_2}}" />
					</div>
					<div class="col-lg-6">
						<label>Other Phone Number 3</label>
						<input type="text" class="form-control contact_no" name="pd_other_no3" value="{{$crm_leads->other_phone_3}}" />
					</div>
				</div>

				<div class="form-group row">
					<div class="col-lg-6">
						<label>Other Phone Number 4</label>
						<input type="text" class="form-control contact_no" name="pd_other_no4" value="{{$crm_leads->other_phone_4}}" />
					</div>
					<div class="col-lg-6">
						<label>Other Phone Number 5</label>
						<input type="text" class="form-control contact_no" name="pd_other_no5" value="{{$crm_leads->other_phone_5}}" />
					</div>
				</div>

													
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-lg-9 ml-lg-auto">
						<button type="submit" class="btn btn-primary btn-lg btn-block" id="save-personal-details"><i class="fa fa-save"></i> Save</button>
					</div>
				</div>
			</div>
		</form>
		<!--end::Form-->
	</div>
<!--end::Card-->