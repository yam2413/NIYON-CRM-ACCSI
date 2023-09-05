@extends('templates.default')
@section('title', 'Summary of Calls Reports')
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
						
						<div class="card-body">

							<div class="row">
								<div class="col-xl-12">
									<!--begin::Mixed Widget 10-->
									<div class="card card-custom gutter-b" style="height: 150px">
										<!--begin::Body-->
											<div class="card-body d-flex align-items-center justify-content-between flex-wrap">
												<div class="mr-2">
													<h3 class="font-weight-bolder">@yield('title')</h3>
													<div class="text-dark-50 font-size-lg mt-2">This table shows the summary of calls in the leads. it contains the Total Calls, Processed, and Amount</div>
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
											<option value="0">Select All</option>
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

							<!--begin::Card-->
							<div class="card card-custom gutter-b">
								<!--begin::Header-->
								<div class="card-header h-auto">
									<!--begin::Title-->
									<div class="card-title py-5">
										<h3 class="card-label">Summary of Calls Chart</h3>
									</div>
									<!--end::Title-->
									<div class="card-toolbar">
													
									</div>
								</div>
									<!--end::Header-->
								<div class="card-body">
									<!--begin::Chart-->
									<div id="chart_12" class="d-flex justify-content-center"></div>
									<!--end::Chart-->
								</div>
							</div>
							<!--end::Card-->
							
							<!--begin: Datatable-->
							<table id="tbl_summary_calls" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>No. of Calls</th>
										<th>No. of Processed Accounts</th>
										<th>Total PTP Amount</th>
										<th>Collector</th>
										<th>Group</th>
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

 var start = moment();
 var end = moment();
 var date = start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
 $('#call_date .form-control').val(date);

	jQuery(document).on('click', '#export_reports', function(e){
    	e.preventDefault();
    	
    	var collector 	= $("#filter_collector").val();
      var groups 		= $("#filter_groups").val();
      var date 		= $('#call_date .form-control').val();
      var status 	  	= 0;
		
		KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

        $.ajax({
		        url: "/call_status/export_reports",
		        type: "POST",
		        data: 'report_type=summary_calls&groups='+groups+'&collector='+collector+'&status='+status+'&date='+date,
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


	function generatePieChart(groups,collector,date,callback){
			
			$.ajax({
		            url: "/summary_calls/SummaryCallsPie",
		            type: "POST",
		            data: 'groups='+groups+'&collector='+collector+'&date='+date,
		            headers: {
		             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		            },
		            dataType: "JSON",
		            success: function(data){
		         
						var options = {
								series: data.total,
								chart: {
									width: 380,
									type: 'pie',
								},
								labels: ['No. of Calls', 'No. of PTP'],
								responsive: [{
									breakpoint: 480,
									options: {
										chart: {
											width: 200
										},
										legend: {
											position: 'bottom'
										}
									}
								}],
								colors: [primary, success, warning, danger, info]
							};

						callback(options);
		            	
		            }
		    
		     	});

				
	}

	generatePieChart(0,0,date,function(callback){
			const apexChart = "#chart_12";
			var options = callback;
			console.log(callback);
			var chart = new ApexCharts(document.querySelector(apexChart), options);
			chart.render();
	});


		

	function func_table_reports(groups,collector,date){
		var tbl_summary_calls = $('#tbl_summary_calls');
		tbl_summary_calls.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/summary_calls/getSummaryCallsList",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
			          // parameters for custom backend script demo
			            "groups" : groups,
			            "collector" : collector,
			            "date" : date,
			       },
				  },
			order: [[ 2, "desc" ]],
			columns: [
				        
				    {data: 'no_calls',orderable: false},
				    {data: 'no_ptp',orderable: false},
				    {data: 'total_ptp_amount',orderable: false},
				    {data: 'name',orderable: false},
				    {data: 'assign_group',orderable: false},
				],
			});
		KTApp.unblockPage();
	}

	func_table_reports(0,0,date);


	jQuery(document).on('click', '.btn_download_recordings', function(e){
     		
     	var id = $(this).attr('id');


   		Swal.fire({
	        title: "Are you sure?",
	        text: "You wont be able to revert this!",
	        icon: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Yes, Update group list"
	    }).then(function(result) {
	        if (result.value) {
	        KTApp.blockPage({
	           overlayColor: '#000000',
	           state: 'primary',
	           message: 'Processing...'
	        });    
	       
            $.ajax({
		        url: "/call_logs/download_recordings",
		        type: "POST",
		        data: 'id='+id,
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
		                    console.log('on close event fired!');
		                    //jQuery("#tbl_groups").dataTable()._fnAjaxUpdate();
			                            }
						}).then(function() {
							KTUtil.scrollTop();
						});
		           
		                                    
		           }
		        });
		             

            	

		             
	        }
	    });
    	
	});

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
		          $('#filter_collector').empty().append('<option value="">Select Collector</option>');
		          jQuery.each(data.get_data, function(k,val){     
                    $('#filter_collector').append($('<option>', { 
                        value: val.id,
                        text : val.name 
                    }));
                  
                  });
		                                    
		       }
		 });
	}


	jQuery(document).on('change', '#filter_groups', function(e){
      var groups = $(this).val();
      var collector = $("#filter_collector").val();
      var date = $('#call_date .form-control').val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

         getUserList(groups);
		 $('#tbl_summary_calls').DataTable().destroy();
		 func_table_reports(groups,collector,date);

		 generatePieChart(groups,collector,date,function(callback){
            	$("#chart_12").empty();
				const apexChart = "#chart_12";
				var options = callback;
				var chart = new ApexCharts(document.querySelector(apexChart), options);
				chart.render();
		});
    	
	});

	jQuery(document).on('change', '#filter_collector', function(e){
      var collector = $(this).val();
      var groups = $("#filter_groups").val();
      var date = $('#call_date .form-control').val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	   $('#tbl_summary_calls').DataTable().destroy();
		func_table_reports(groups,collector,date);

		generatePieChart(groups,collector,date,function(callback){
            	$("#chart_12").empty();
				const apexChart = "#chart_12";
				var options = callback;
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

            $('#call_date .form-control').val(date);
            $('#tbl_summary_calls').DataTable().destroy();
            func_table_reports(groups,collector,date);

            generatePieChart(groups,collector,date,function(callback){
            	$("#chart_12").empty();
				const apexChart = "#chart_12";
				var options = callback;
				var chart = new ApexCharts(document.querySelector(apexChart), options);
				chart.render();
			});
    });	


});
</script>
@endpush