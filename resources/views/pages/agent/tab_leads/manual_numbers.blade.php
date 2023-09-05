<!--begin::Card-->
	<div class="card card-custom gutter-b example example-compact">
		<!--begin::Form-->
		<form class="form" id="add_manual_numbers_form">
			<div class="card-body">
				<p class="mb-0">
					<span class="card-icon">
						<i class="fas fa-info-circle text-primary"></i>
					</span>
					Note: Any changes must click save button.
				</p>
				<hr>
				<div id="kt_repeater_manual_numbers">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label text-right">Contacts:</label>
							<div data-repeater-list="manual_numbers" class="col-lg-10">

								@if (count($manual_numbers) > 0)

									@foreach ($manual_numbers as $manual_number)
									<div data-repeater-item="" class="form-group row align-items-center">
										<div class="col-md-3">
											<label>Contact Type:</label>
											<input type="text" class="form-control" name="field_name" placeholder="" value="{{$manual_number->field_name}}" />
											<div class="d-md-none mb-2"></div>
										</div>
										<div class="col-md-3">
											<label>Number:</label>
											<input type="number" class="form-control" name="contact_no" placeholder="Enter contact number" value="{{$manual_number->contact_no}}"/>
											<div class="d-md-none mb-2"></div>
										</div>
										<div class="col-md-4">
											<a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
											<i class="la la-trash-o"></i>Delete</a>
										</div>

									</div>
									@endforeach

								@else
									<div data-repeater-item="" class="form-group row align-items-center">
										<div class="col-md-3">
											<label>Contact Type:</label>
											<input type="text" class="form-control" name="field_name" placeholder="" />
											<div class="d-md-none mb-2"></div>
										</div>
										<div class="col-md-3">
											<label>Number:</label>
											<input type="number" class="form-control" name="contact_no" placeholder="Enter contact number" />
											<div class="d-md-none mb-2"></div>
										</div>
										<div class="col-md-4">
											<a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
											<i class="la la-trash-o"></i>Delete</a>
										</div>

									</div>
								@endif
								

								

							</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label text-right"></label>
						<div class="col-lg-4">
							<a href="javascript:;" data-repeater-create="" class="btn btn-sm font-weight-bolder btn-light-primary">
								<i class="la la-plus"></i>Add
							</a>
						</div>
					</div>
				</div>

													
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-lg-9 ml-lg-auto">
						<button type="submit" class="btn btn-primary btn-lg btn-block" id="save-manual-numbers"><i class="fa fa-save"></i> Save</button>
					</div>
				</div>
			</div>
		</form>
		<!--end::Form-->
	</div>
<!--end::Card-->