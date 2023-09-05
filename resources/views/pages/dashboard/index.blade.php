@extends('templates.default')
@section('title', 'Dashboard')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">
					
						<!--begin::Card-->
								<div class="card card-custom gutter-b">
									<div class="card-header">
										<div class="card-title">
											<span class="card-icon">
												<i class="far fa-file-alt text-primary icon-3x mr-5"></i>
											</span>
											<h3 class="card-label">Demographics</h3>
										</div>
										<div class="card-toolbar">
											<ul class="nav nav-tabs nav-bold nav-tabs-line">
												<li class="nav-item">
													 
												</li>
												<li class="nav-item">
													 
												</li>
											</ul>
										</div>
									</div>
									
									<div class="card-body">
										<!--begin::Search Form-->
										<div class="mb-7">
											<div class="row align-items-center">
												<div class="col-lg-9 col-xl-8">
													<div class="row align-items-center">

														<div class="col-md-4 my-2 my-md-0">
															<div class="d-flex align-items-center">
																<label class="mr-3 mb-0 d-none d-md-block">Filter Groups:</label>
																<select id="filter_groups" class="form-control form-control-solid">
																	 	@if(Auth::user()->level == '0')
																	 		@foreach ($groups as $key => $group)
																                <option value="{{$group->id}}">{{$group->name}}</option>
																            @endforeach
																	 		
																	 	@else
																	 		<option value="{{Auth::user()->group}}">{{\App\Models\Groups::usersGroup(Auth::user()->group)}}</option>
																	 	@endif
															</select>
															</div>
														</div>

														<div class="col-md-4 my-2 my-md-0">
															<div class="d-flex align-items-center">
																<label class="mr-3 mb-0 d-none d-md-block">Filter Date:</label>
																<div class='input-group' id='demograph_date'>
																	<input type='text' class="form-control" readonly name="demograph_date"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
																	<div class="input-group-append">
																		<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
																	</div>
																 </div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--end::Search Form-->
										<div class="row" id="div-show-leads-demographics">

												

										</div>
									</div>
								</div>
								<!--end::Card-->

						




					
				</div>
			</div>

			<div class="row">
				
				<div class="col-xl-6">
					<!--begin::Charts Widget 4-->
					<div class="card card-custom card-stretch gutter-b">
						<!--begin::Header-->
						<div class="card-header h-auto border-0">
							<div class="card-title py-5">
								<h3 class="card-label">
									<span class="d-block text-dark font-weight-bolder">Account Process for the year {{date('Y')}}</span>
								</h3>
							</div>
							<div class="card-toolbar">
								<ul class="nav nav-pills nav-pills-sm nav-dark-75" role="tablist">
									<li class="nav-item">
										{{-- <select class="form-control" id="year-audience">
																@php
																	$x = 2020;
																	$year_now = date('Y');
																@endphp
																@for ($i = 0; $i < 12; $i++)
																	<option value="{{ $x }}" @if($x == $year_now) selected @endif>{{ $x }}</option>
																	@php
																		$x++;
																	@endphp
																	
																@endfor
																
										</select> --}}
									</li>
								</ul>
							</div>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body">
							<div id="kt_leads_annual_chart"></div>
						</div>
						<!--end::Body-->
					</div>
					<!--end::Charts Widget 4-->
				</div>

				@if(Auth::user()->level != '3')
					<div class="col-xl-6">
					<!--begin::Advance Table Widget 1-->
								<div class="card card-custom gutter-b">
									<!--begin::Header-->
									<div class="card-header border-0 py-5">
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label font-weight-bolder text-dark">Top 5 Collectors</span>
											<span class="text-muted mt-3 font-weight-bold font-size-sm">It shows the top 5 list who are more engaged in the process.</span>
										</h3>
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body py-0">
										<!--begin::Table-->
										<div class="table-responsive">
											<table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
												<thead>
													<tr class="text-left">
														<th style="min-width: 200px"></th>
														<th style="min-width: 150px">Assign Leads</th>
														<th style="min-width: 150px">Assign Auto Dialer</th>
														<th style="min-width: 150px">Process</th>
													</tr>
												</thead>
												<tbody>

													@foreach ($agent_stats as $agent_stat)
														@php
															$group = \App\Models\Groups::usersGroup($agent_stat->group);
														@endphp
														<tr>
															<td class="pl-0">
																<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ $agent_stat->name }}</a>
																<span class="text-muted font-weight-bold text-muted d-block">{{ $group }}</span>
															</td>
															<td>
																<span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $agent_stat->total_assign }}</span>
															</td>
															<td>
																<span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $agent_stat->total_assign_dialer }}</span>
															</td>
															<td>
																<span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $agent_stat->total_process }}</span>
															</td>
															
														</tr>
													@endforeach
													
													
												</tbody>
											</table>
										</div>
										<!--end::Table-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Advance Table Widget 1-->
				</div>
				@elseif(Auth::user()->level == '3')
					<div class="col-xl-6">
					<!--begin::Advance Table Widget 1-->
								<div class="card card-custom gutter-b">
									<!--begin::Header-->
									<div class="card-header border-0 py-5">
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label font-weight-bolder text-dark">Recent Calls</span>
										</h3>
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body py-0">
										<!--begin::Table-->
										<div class="table-responsive">
											<table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
												<thead>
													<tr class="text-left">
														<th style="min-width: 200px">Borrower Name</th>
														<th style="min-width: 150px">Dial No</th>
														<th style="min-width: 150px">Call Date</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($agent_call_logs as $agent_call_log)
														
														<tr>
															<td class="pl-0">
																<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ $agent_call_log->full_name }}</a>
															</td>
															<td>
																<span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $agent_call_log->contact_no }}</span>
															</td>
															<td>
																<span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $agent_call_log->created_at->diffForHumans() }}</span>
															</td>
															
														</tr>
													@endforeach
													
													
													
												</tbody>
											</table>
										</div>
										<!--end::Table-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Advance Table Widget 1-->
				</div>

				@endif
			</div>
		<!--end::Row-->


	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
@endsection

@push('scripts')
<script type="text/javascript">
"use strict";
// Webpack support
if (typeof module !== 'undefined') {
    module.exports = KTWidgets;
}
KTUtil.ready(function() {
const primary = '#6993FF';
const success = '#1BC5BD';
const info = '#8950FC';
const warning = '#FFA800';
const danger = '#F64E60';

var arrows;
 if (KTUtil.isRTL()) {
  arrows = {
   leftArrow: '<i class="la la-angle-right"></i>',
   rightArrow: '<i class="la la-angle-left"></i>'
  }
 } else {
  arrows = {
   leftArrow: '<i class="la la-angle-left"></i>',
   rightArrow: '<i class="la la-angle-right"></i>'
  }
 }

 var start = moment().startOf('month');
 var end = moment().endOf('month');
 var date = start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
 
 $('#demograph_date .form-control').val(date);


 function get_leads_status(group_id, date){
		jQuery.ajax({
			url: "/dashboard/get_leads_status",
			type: "POST",
			data: 'group_id='+group_id+'&date='+date,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success: function(data) {
				$("#div-show-leads-demographics").html('');
				jQuery.each(data.get_data, function(k,val){
					
					var link = '{{ route('pages.dashboard.view_status', ['statuses' => ':statuses']) }}';
								 link = link.replace(':statuses', val.status_name);

					var div_best_time = `<div class="col-xl-2">
											<!--begin::Tiles Widget 11-->
											<div class="card card-custom bg-dark gutter-b" style="height: 140px">
												<div class="card-body">
													<div class="text-inverse-success font-weight-bolder font-size-h2 mt-3">`+val.total_count+`</div>
													<a href="`+link+`" class="text-inverse-success font-weight-bold font-size-lg mt-1">`+val.status_name+`</a>
												</div>
											</div>
											<!--end::Tiles Widget 11-->
										</div>`;     
			           $("#div-show-leads-demographics").append(div_best_time);      
		        });
					
					
			},
			error: function(data){



					

			}
		}); 
 }
var filter_groups = $("#filter_groups").val();
get_leads_status(filter_groups, date);

 // var DEMOGRAPH_LEADS_STATUS = function(date) {
// 			KTApp.blockPage({
// 	           overlayColor: '#000000',
// 	           state: 'primary',
// 	           message: 'Processing...'
// 	        });

// 	       jQuery.ajax({
// 	          url: "/dashboard/getDemographStatusLeads",
// 	          type: "POST",
// 	          data: 'date='+date,
// 	          headers: {
// 		             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
// 		            },
// 	          dataType: "json",
// 	          success: function(data) {
// 	          	 KTApp.unblockPage();
// 	          	 $("#demo-new_lead").html(data.new_lead);
// 	          	 $("#demo-ptp").html(data.ptp);
// 	          	 $("#demo-bptp").html(data.bptp);
// 	          	 $("#demo-bp").html(data.bp);
// 	          	 $("#demo-paid").html(data.paid);

// 	          },
// 	          error: function(data){


// 	          }
// 	        });
	        
 // }

 // DEMOGRAPH_LEADS_STATUS(date);

 function generateLineChart(year,callback){
    	$.ajax({
		    url: "/dashboard/LeadsStatisticsGraph",
		    type: "POST",
		    data: 'year='+year,
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
		    dataType: "JSON",
		    success: function(data){
		            
		            	var options = {
					            series: [{
					                name: 'Total Account Process',
					                data: data.total
					            }],
					            chart: {
					                type: 'area',
					                height: 350,
					                toolbar: {
					                    show: false
					                }
					            },
					            plotOptions: {},
					            legend: {
					                show: false
					            },
					            dataLabels: {
					                enabled: false
					            },
					            fill: {
					                type: 'solid',
					                opacity: 1
					            },
					            stroke: {
					                curve: 'smooth'
					            },
					            xaxis: {
					                categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
					                axisBorder: {
					                    show: false,
					                },
					                axisTicks: {
					                    show: false
					                },
					                labels: {
					                    style: {
					                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
					                        fontSize: '12px',
					                        fontFamily: KTApp.getSettings()['font-family']
					                    }
					                },
					                crosshairs: {
					                    position: 'front',
					                    stroke: {
					                        color: KTApp.getSettings()['colors']['theme']['light']['success'],
					                        width: 1,
					                        dashArray: 3
					                    }
					                },
					                tooltip: {
					                    enabled: true,
					                    formatter: undefined,
					                    offsetY: 0,
					                    style: {
					                        fontSize: '12px',
					                        fontFamily: KTApp.getSettings()['font-family']
					                    }
					                }
					            },
					            yaxis: {
					                labels: {
					                    style: {
					                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
					                        fontSize: '12px',
					                        fontFamily: KTApp.getSettings()['font-family']
					                    }
					                }
					            },
					            states: {
					                normal: {
					                    filter: {
					                        type: 'none',
					                        value: 0
					                    }
					                },
					                hover: {
					                    filter: {
					                        type: 'none',
					                        value: 0
					                    }
					                },
					                active: {
					                    allowMultipleDataPointsSelection: false,
					                    filter: {
					                        type: 'none',
					                        value: 0
					                    }
					                }
					            },
					            tooltip: {
					                style: {
					                    fontSize: '12px',
					                    fontFamily: KTApp.getSettings()['font-family']
					                },
					                y: {
					                    formatter: function (val) {
					                        return val
					                    }
					                }
					            },
					            colors: [KTApp.getSettings()['colors']['theme']['base']['success'], KTApp.getSettings()['colors']['theme']['base']['warning']],
					            grid: {
					                borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
					                strokeDashArray: 4,
					                yaxis: {
					                    lines: {
					                        show: true
					                    }
					                }
					            },
					            markers: {
					                colors: [KTApp.getSettings()['colors']['theme']['light']['success'], KTApp.getSettings()['colors']['theme']['light']['warning']],
					                strokeColor: [KTApp.getSettings()['colors']['theme']['light']['success'], KTApp.getSettings()['colors']['theme']['light']['warning']],
					                strokeWidth: 3
					            }
					        };

						callback(options);
		            	
		            }
		    
		    });

    }


 var year_annual = 0;

 generateLineChart(year_annual,function(callback){
		var element = document.getElementById("kt_leads_annual_chart");
		var options = callback;
        if (!element) {
            return;
        }
        var chart = new ApexCharts(element, options);
        chart.render();

 });

 jQuery(document).on('change', '#filter_groups', function(e){
		var filter_groups = $(this).val();
		var date = $('#demograph_date .form-control').val();
		get_leads_status(filter_groups, date);
 });

 $('#demograph_date').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',

            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')]
            }
        }, function(start, end, label) {
        	var date = start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
        	var filter_groups = $("#filter_groups").val();
            $('#demograph_date .form-control').val(date);
            //DEMOGRAPH_LEADS_STATUS(date);
            get_leads_status(filter_groups, date);
    });	





});
</script>
@endpush