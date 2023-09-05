@extends('templates.default')
@section('title', 'Call Status Reports')
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">

	<!--begin::Container-->
	<div class="container">

		<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
					<div class="card card-custom">
						
						{{-- <div class="card-header">

							<div class="card-title">
								<h3 class="card-label"></h3>
								
								
							</div>
						</div> --}}
						
						<div class="card-body">
							 <div class="row">
								<div class="col-xl-12">
									<!--begin::Mixed Widget 10-->
									<div class="card card-custom gutter-b" style="height: 150px">
										<!--begin::Body-->
											<div class="card-body d-flex align-items-center justify-content-between flex-wrap">
												<div class="mr-2">
													<h3 class="font-weight-bolder">@yield('title')</h3>
													<div class="text-dark-50 font-size-lg mt-2">This table shows the call status in the leads.</div>
												</div>
												<a href="#" id="export_reports" class="btn btn-success" data-toggle="tooltip" data-theme="dark" title="Download this report using excel file.">
													<i class="far fa-file-excel"></i> Download
												</a>
											</div>
										<!--end::Body-->
									</div>
									<!--end::Mixed Widget 10-->
								</div>
							</div>

							<!--begin: Search Form-->
							<form class="mb-15">
								<div class="row mb-6">
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Groups:</label>
										<select id="filter_groups" class="form-control form-control-solid">
											<option value="0">Select Groups</option>
											@if(Auth::user()->level == '0')
															
												@foreach ($groups as $key => $group)
													<option value="{{$group->id}}">{{$group->name}}</option>
												@endforeach
																	 		
											@else
												<option value="{{Auth::user()->group}}">{{\App\Models\Groups::usersGroup(Auth::user()->group)}}</option>
											@endif
										</select>
									</div>
									
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Status:</label>
										<select id="filter_status" class="form-control">
											<option value="0">Select status</option>
										</select>
									</div>
									
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Collector:</label>
										<select id="filter_collector" class="form-control">
											<option value="0">Select Collector</option>
										</select>
									</div>
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Status Date:</label>
										<div class='input-group' id='call_date'>
											<input type='text' class="form-control" readonly name="call_date"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
											<div class="input-group-append">
												<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
											</div>
										</div>
									</div>
								</div>
							</form>
							<!--begin: Datatable-->

							 
							<!--begin::Card-->
							<div class="card card-custom gutter-b">
								<!--begin::Header-->
								<div class="card-header h-auto">
								<!--begin::Title-->
									<div class="card-title py-5">
										<h3 class="card-label">Call Status Line Graph</h3>
									</div>
									<!--end::Title-->
									<div class="card-toolbar">
													
									</div>
								</div>
								<!--end::Header-->
								<div class="card-body">
									<!--begin::Chart-->
									<div id="chart_1"></div>
									<!--end::Chart-->
								</div>
							</div>
							<!--end::Card-->

							

							<!--begin: Datatable-->
							<table id="tbl_call_status" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Full Name</th>
										<th>Account Number</th>
										<th>PTP Date</th>
										<th>PTP Amount</th>
										<th>Remarks</th>
										<th>Collector</th>
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


	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
@endsection

@push('scripts')
<script type="text/javascript">
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

 var start 		= moment();
 var end 		= moment();
 var date 		= start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
 var status 	= $("#filter_status").val();
 var collector 	= $("#filter_collector").val();
 var groups 	= $("#filter_groups").val();
 $('#call_date .form-control').val(date);

 function generateLineChart(groups, collector, date, status,callback){
			
		$.ajax({
	            url: "/call_status/CallStatusGraph",
	            type: "POST",
	            data: 'groups='+groups+'&collector='+collector+'&date='+date+'&status='+status,
	            headers: {
	             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
	            dataType: "JSON",
	            success: function(data){

					var options = {
                            series: [{
                                name: 'TOTAL',
                                data: data.total
                            }],
                            chart: {
                                type: 'bar',
                                height: 350
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '55%',
                                    endingShape: 'rounded'
                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                show: true,
                                width: 2,
                                colors: ['transparent']
                            },
                            xaxis: {
                                categories: data.categories,
                            },
                            yaxis: {
                                title: {
                                    text: ''
                                }
                            },
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function (val) {
                                        return val
                                    }
                                }
                            },
                            colors: [primary]
                        };

					callback(options);
	            	
	            }
	    
	     	});

			
}
		generateLineChart(groups,collector,date,status,function(callback){
			const apexChart = "#chart_1";
			var options = callback;
			console.log(callback);
			var chart = new ApexCharts(document.querySelector(apexChart), options);
			chart.render();
		});


	jQuery(document).on('click', '#export_reports', function(e){
    	e.preventDefault();
    	
    	var collector = $("#filter_collector").val();
      	var groups 	  = $("#filter_groups").val();
      	var date 	  = $('#call_date .form-control').val();
      	var status 	  = $("#filter_status").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

        $.ajax({
		        url: "/call_status/export_reports",
		        type: "POST",
		        data: 'report_type=call_status&groups='+groups+'&collector='+collector+'&status='+status+'&date='+date,
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		        		KTApp.unblockPage();
			      	swal.fire({
		               text: data.msg,
		               icon: "success",
		               buttonsStyling: false,
		               confirmButtonText: "Ok, got it!",
		               customClass: {
		                  confirmButton: "btn font-weight-bold btn-light-primary"
		               },onClose: function(e) {

		                 
		               }
		               }).then(function() {
		                  KTUtil.scrollTop();
		               });
		                                    
		       }
		 });

    });


	function func_table_reports(groups, collector, date, status){
			var tbl_call_status = $('#tbl_call_status');
			tbl_call_status.DataTable({
				responsive: true,
				searchDelay: 500,
				processing: true,
				serverSide: true,
				searching: true,
				ajax: {
					    url: "/call_status/getCallStatusList",
					    type: 'POST',
					    headers: {
					        'X-CSRF-TOKEN': '{{ csrf_token() }}'
					    },
					    data: {
				          // parameters for custom backend script demo
				            "groups" : groups,
				            "collector" : collector,
				            "date" : date,
				            "status" : status,
				       },
					  },
				order: [[ 2, "desc" ]],
				columns: [
					        
					    {data: 'full_name',orderable: false},
					    {data: 'account_number',orderable: false},
					    {data: 'payment_date',orderable: false},
					    {data: 'ptp_amount',orderable: false},
					    {data: 'remarks',orderable: false},
					    {data: 'assign_user',orderable: false},
					],
				});
			KTApp.unblockPage();
	}

	//Load the table reports
	func_table_reports(groups,collector,date,status);

	function getUserList(group){
		$.ajax({
		        url: "/summary_calls/get_user_list",
		        type: "POST",
		        data: 'group='+group,
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		          $('#filter_collector').empty().append('<option value="0">Select Collector</option>');
		          jQuery.each(data.get_data, function(k,val){     
                    $('#filter_collector').append($('<option>', { 
                        value: val.id,
                        text : val.name 
                    }));
                  
                  });
		                                    
		       }
		 });
	}

	function getStatus(group){
		$.ajax({
		        url: "/leads/get_group_status",
		        type: "POST",
		        data: 'group='+group+'&except=none',
		        headers: {
		           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        dataType: "JSON",
		        success: function(data){
		        
			      $('#filter_status').empty().append('<option value="0">Select Status</option>');
			      
			      jQuery.each(data.get_data, function(k,val){     
	                  	$('#filter_status').append($('<option>', { 
	                        value: val.name,
	                        text : val.name 
	                  }));
                  
                  });
		                                    
		       }
		 });
	}

	jQuery(document).on('change', '#filter_status', function(e){
	      var collector = $("#filter_collector").val();
	      var groups 	= $("#filter_groups").val();
	      var date 		= $('#call_date .form-control').val();
	      var status 	= $(this).val();

	       KTApp.blockPage({
	          overlayColor: '#000000',
	          state: 'primary',
	          message: 'Processing...'
	       });

		   
		   $('#tbl_call_status').DataTable().destroy();
		   func_table_reports(groups, collector, date, status);

		   generateLineChart(groups, collector, date, status,function(callback){
			   	$("#chart_1").empty();
				const apexChart = "#chart_1";
				var options = callback;
				console.log(callback);
				var chart = new ApexCharts(document.querySelector(apexChart), options);
				chart.render();
			});
	    	
	});

	jQuery(document).on('change', '#filter_groups', function(e){
      var groups 	= $("#filter_groups").val();
      var collector = $("#filter_collector").val();
      var date 		= $('#call_date .form-control').val();
      var status 	= $("#filter_status").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

       getUserList(groups);
       getStatus(groups);
	   $('#tbl_call_status').DataTable().destroy();
	   func_table_reports(groups, collector, date, status);

	   generateLineChart(groups, collector, date, status,function(callback){
			   	$("#chart_1").empty();
				const apexChart = "#chart_1";
				var options = callback;
				console.log(callback);
				var chart = new ApexCharts(document.querySelector(apexChart), options);
				chart.render();
		});
    	
	});

	jQuery(document).on('change', '#filter_collector', function(e){
      var collector = $(this).val();
      var groups = $("#filter_groups").val();
      var date = $('#call_date .form-control').val();
      var status 	= $("#filter_status").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	   $('#tbl_call_status').DataTable().destroy();
	   func_table_reports(groups, collector, date, status);
	   generateLineChart(groups, collector, date, status,function(callback){
			$("#chart_1").empty();
			const apexChart = "#chart_1";
			var options = callback;
			console.log(callback);
			var chart = new ApexCharts(document.querySelector(apexChart), options);
			chart.render();
		});
    	
	});

	$('#call_date').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',

            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
        	var date = start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
        	var groups = $("#filter_groups").val();
      		var collector = $("#filter_collector").val();
      		var status 	= $("#filter_status").val();

            $('#call_date .form-control').val(date);
            $('#tbl_call_status').DataTable().destroy();
	   		func_table_reports(groups, collector, date, status);
	   		generateLineChart(groups, collector, date, status,function(callback){
			   	$("#chart_1").empty();
				const apexChart = "#chart_1";
				var options = callback;
				console.log(callback);
				var chart = new ApexCharts(document.querySelector(apexChart), options);
				chart.render();
			});
    });


	


});
</script>
@endpush