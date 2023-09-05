<!--begin::Card-->
										<div class="card card-custom gutter-b example example-compact">
											<!--begin::Form-->
											<form class="form">
												<div class="card-body">
													
													@for ($i = 1; $i < 80; $i++)
													<div class="form-group row">
														<div class="col-lg-6">
															<label>{{$temp_uploads['data'.$i]}}</label>
															<textarea class="form-control form-control-lg" disabled="disabled">{{$temp_datas['data'.$i]}}</textarea>
														</div>
														@php
															$i++;
														@endphp
														<div class="col-lg-6">
															<label>{{$temp_uploads['data'.$i]}}</label>
															<textarea class="form-control form-control-lg" disabled="disabled">{{$temp_datas['data'.$i]}}</textarea>
														</div>
													</div>
													@endfor




												</div>
											</form>
											<!--end::Form-->
										</div>
									<!--end::Card-->