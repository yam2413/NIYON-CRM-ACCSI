@extends('templates.default')
@section('title', 'Call Logs Reports')
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
													<div class="text-dark-50 font-size-lg mt-2">This table shows the list of call logs made in the leads. You can also download the recordings</div>
												</div>
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
											<option value="0">All Groups</option>
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
							<table id="tbl_call_logs" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Call Recording</th>
										<th>Name</th>
										<th>Leads Status</th>
										<th>Dial No.</th>
										<th>Extension</th>
										<th>Collector</th>
										<th>Group</th>
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
 var date 		= start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');

 $('#call_date .form-control').val(start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD'));

	function generateLineChart(groups,status,callback){
			
			$.ajax({
		            url: "/call_logs/CallLogsStatisticsGraph",
		            type: "POST",
		            data: 'groups='+groups+'&status='+status,
		            headers: {
		             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		            },
		            dataType: "JSON",
		            success: function(data){
		            
		            	var options = {
							series: [{
								name: "Total",
								data: data.total
							}],
							chart: {
								height: 350,
								type: 'line',
								zoom: {
									enabled: false
								}
							},
							dataLabels: { 	
								enabled: false
							},
							stroke: {
								curve: 'straight'
							},
							grid: {
								row: {
									colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
									opacity: 0.5
								},
							},
							xaxis: {
								categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
							},
							colors: [primary]
						};

						callback(options);
		            	
		            }
		    
		     	});

				
	}
	
	

	

		// generateLineChart(0,0,function(callback){
		// 	const apexChart = "#chart_1";
		// 	var options = callback;
		// 	console.log(callback);
		// 	var chart = new ApexCharts(document.querySelector(apexChart), callback);
		// 	chart.render();
		// });
		

	function func_table_reports(groups, status, collector, date){
		var tbl_call_logs = $('#tbl_call_logs');
		tbl_call_logs.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/call_logs/getCallLogsList",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
			          // parameters for custom backend script demo
			            "groups" : groups,
			            "status" : status,
			            "collector" : collector,
			            "date" : date,
			       },
				  },
			order: [[ 7, "desc" ]],
			columns: [
				        
				    {data: 'action',orderable: false},
				    {data: 'full_name',orderable: false},
				    {data: 'status',orderable: false},
				    {data: 'contact_no',orderable: false},
				    {data: 'extension',orderable: false},
				    {data: 'call_by',orderable: false},
				    {data: 'assign_group',orderable: false},
				    {data: 'created_at',orderable: false},
				],
			});
		KTApp.unblockPage();
	}

	func_table_reports(0,0,0,date);


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

	jQuery(document).on('change', '#filter_groups', function(e){
      var groups 		= $(this).val();
      var status 		= $("#filter_status").val();
      var collector 	= $("#filter_collector").val();
      var date 		= $('#call_date .form-control').val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

       getUserList(groups);
       getStatus(groups);
		 $('#tbl_call_logs').DataTable().destroy();
		 func_table_reports(groups, status, collector, date);
    	
	});

	jQuery(document).on('change', '#filter_status', function(e){
      var status 		= $(this).val();
      var groups 		= $("#filter_groups").val();
      var collector 	= $("#filter_collector").val();
      var date 		= $('#call_date .form-control').val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	   $('#tbl_call_logs').DataTable().destroy();
		func_table_reports(groups, status, collector, date);

		// generateLineChart(groups,status,function(callback){
		//  	$("#chart_1").empty();
		// 	const apexChart = "#chart_1";
		// 	var options = callback;
		// 	console.log(callback);
		// 	var chart = new ApexCharts(document.querySelector(apexChart), callback);
		// 	chart.render();
		// });
    	
	});

	jQuery(document).on('change', '#filter_collector', function(e){
      var collector 	= $(this).val();
      var groups 		= $("#filter_groups").val();
      var date 		= $('#call_date .form-control').val();
      var status 		= $("#filter_status").val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });
	   
	   $('#tbl_call_logs').DataTable().destroy();
		func_table_reports(groups, status, collector, date);
    	
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
        	var date 		= start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD');
        	var groups 		= $("#filter_groups").val();
        	var collector 	= $("#filter_collector").val();
      	var status 		= $("#filter_status").val();

            $('#call_date .form-control').val(date);
            $('#tbl_call_logs').DataTable().destroy();
            func_table_reports(groups, status, collector, date);
    });	


});
</script>
@endpush