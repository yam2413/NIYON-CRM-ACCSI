<!--begin::Form-->
									<form>
										<div class="card-body">

											<div class="alert alert-dark" role="alert">
												<p>Notes: Make sure to download this template. The account number will basis of the pullout of the accounts.</p>
												<a  href="{{ asset('Pullout_Template.xlsx') }}" class="btn btn-secondary btn-shadow-hover font-weight-bold mr-2">
											        <i class="fas fa-file-excel"></i> Download Template
											    </a>
											</div>

											<div class="row">

												<div class="col-xl-3">
														<select id="select_import_groups" class="form-control form-control-lg">
																<option value="">Select Groups</option>
															@foreach ($groups as $key => $group)
																<option value="{{$group->id}}">{{$group->name}}</option>
															 @endforeach
														</select>						
												</div>

												
												 

																		

											</div>
											<hr>
											<div class="form-group row" id="div-upload-imported-pullout" style="display: none;">
												<label class="col-form-label col-lg-3 col-sm-12 text-lg-right"></label>
												<div class="col-lg-6 col-md-9 col-sm-12">
													<div class="dropzone dropzone-default" id="file_upload_pullout">
														<div class="dropzone-msg dz-message needsclick">
															<h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
															<span class="dropzone-msg-desc"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
									<!--end::Form-->