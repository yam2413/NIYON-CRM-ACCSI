@extends('templates.default')
@section('title', 'Insights')
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
						
						<div class="card-header">

							<div class="card-title">
								<span class="card-icon">
									<i class="fas fa-chart-line text-primary icon-3x mr-5"></i>
								</span>
								<h3 class="card-label">Review Campaign activity and access reports on leads, collectors and more.</h3>
							</div>
							<div class="card-toolbar">
								 <a  href="{{ route('pages.auto_dialer.dialer', ['file_id' => $file_id]) }}" class="btn btn-primary btn-shadow-hover font-weight-bold mr-2">
					                <i class="flaticon2-back"></i> Back
					            </a>
					
							</div>
						</div>
						
						<div class="card-body">

							<div class="row">

								@include('pages.auto_dialer.demograph_insights')


							</div>


							
						</div>

					</div>
					<!--end::Card-->


				</div>





			</div>
		<!--end::Row-->
			<hr>
			<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
					<div class="card card-custom">
						
						<div class="card-header">

							<div class="card-title">
								<h3 class="card-label">Leads Status</h3>
							</div>
						</div>
						
						<div class="card-body">

							<div class="row">

								@include('pages.auto_dialer.leads_insights')

							</div>


							
						</div>

					</div>
					<!--end::Card-->


				</div>


			</div>
		<!--end::Row-->

			<hr>
			<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
					<div class="card card-custom">
						
						<div class="card-header">

							<div class="card-title">
								<h3 class="card-label">Call Logs Summary</h3>
							</div>
							<div class="card-toolbar">
								<ul class="nav nav-tabs nav-bold nav-tabs-line">
								 	<li class="nav-item">
										<div class="form-group row">
											<label class="col-form-label text-right col-lg-4 col-sm-12">Date Range</label>
											<div class="col-lg-8 col-md-9 col-sm-12">
												<div class='input-group' id='date_call_logs_summary'>
													<input type='text' class="form-control" readonly name="date_call_logs_summary"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
													<div class="input-group-append">
														<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
						
							</div>
						</div>
						
						<div class="card-body">
							<div class="row">
								<div class="col-xl-3">
									<button type="button" id="btn-export-call-logs" class="btn btn-primary btn-sm btn-block"><i class="fas fa-file-excel"></i> 
									Download Excel
									</button>
								</div>
							</div>
							<!--begin: Datatable-->
							<table id="tbl_call_logs_summary" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th></th>
										<th>Name</th>
										<th>Lead Status</th>
										<th>Call Disposition</th>
										<th>Dial No.</th>
										<th>Collector</th>
										<th>Agent Disposition</th>
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

			<hr>
			<!--begin::Row-->
			<div class="row">
				<div class="col-lg-12">

					<!--begin::Card-->
					<div class="card card-custom">
						
						<div class="card-header">

							<div class="card-title">
								<h3 class="card-label font-weight-bold">Collector Performance</h3>
							</div>
							<div class="card-toolbar">
								 <ul class="nav nav-tabs nav-bold nav-tabs-line">
										<li class="nav-item">
											<div class="form-group row">
												<label class="col-form-label text-right col-lg-4 col-sm-12">Date Range</label>
												 <div class="col-lg-8 col-md-9 col-sm-12">
													 <div class='input-group' id='date_agent_performance'>
														<input type='text' class="form-control" readonly name="date_agent_performance"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
														<div class="input-group-append">
															<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
														</div>
													 </div>
												</div>
											</div>
										</li>
									</ul>
						
							</div>
						</div>
						
						<div class="card-body">
							<div class="row">
								<div class="col-xl-3">
									<button type="button" id="btn-export-agent-performance" class="btn btn-primary btn-sm btn-block"><i class="fas fa-file-excel"></i> 
									Download Excel
									</button>
								</div>
							</div>
							<!--begin: Datatable-->
							<table id="tbl_agent_performance" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Name</th>
										<th>Assign Leads</th>
										<th>Dial</th>
										<th>Process</th>
										<th>Total Pause</th>
										<th>Answered <button class="btn btn-icon btn-circle btn-sm mr-2" data-toggle="tooltip" data-theme="dark" title="Total Number of Answered call based on agent disposition"><i class="fas fa-info-circle"></i></button></th>
										<th>Busy <button class="btn btn-icon btn-circle btn-sm mr-2" data-toggle="tooltip" data-theme="dark" title="Total Number of Busy status based on agent disposition"><i class="fas fa-info-circle"></i></button></th>
										<th>Not Getting Service <button class="btn btn-icon btn-circle btn-sm mr-2" data-toggle="tooltip" data-theme="dark" title="Total Number of Not Getting Service status based on agent disposition"><i class="fas fa-info-circle"></i></button></th>
										<th>Just Ringing <button class="btn btn-icon btn-circle btn-sm mr-2" data-toggle="tooltip" data-theme="dark" title="Total Number of Just Ringing status call based on agent disposition"><i class="fas fa-info-circle"></i></button></th>
										<th>Hang up/Can't be reached <button class="btn btn-icon btn-circle btn-sm mr-2" data-toggle="tooltip" data-theme="dark" title="Total Number of Hang up/Can't be reached status call based on agent disposition"><i class="fas fa-info-circle"></i></button></th>
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

	var start = moment().subtract(29, 'days');
	var end = moment();

	var tbl_call_logs_summary = $('#tbl_call_logs_summary');
	var tbl_agent_performance = $('#tbl_agent_performance');



 	$('#date_agent_performance .form-control').val(start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD'));
 	$('#date_call_logs_summary .form-control').val(start.format('YYYY-MM-DD') + ' | ' + end.format('YYYY-MM-DD'));

	function func_tbl_call_logs_summary(start_date, end_date){
		tbl_call_logs_summary.DataTable({
		responsive: true,
		searchDelay: 500,
		processing: true,
		serverSide: true,
		searching: true,
		ajax: {
			    url: "/insights/getCallLogsList",
			    type: 'POST',
			    headers: {
			        'X-CSRF-TOKEN': '{{ csrf_token() }}'
			    },
			     data: {
				    // parameters for custom backend script demo
				    "file_id" : "{{$file_id}}",
				    "start_date" : start_date,
				    "end_date" : end_date,
				},
			  },
		order: [[ 7, "desc" ]],
		columns: [
			    {data: 'action',orderable: false},
			    {data: 'full_name',orderable: false},
			    {data: 'status',orderable: false},
			    {data: 'call_status',orderable: false},
			    {data: 'contact_no',orderable: false},
			    {data: 'call_by',orderable: false},
			    {data: 'agent_status',orderable: false},
			    {data: 'created_at',orderable: false},
			],
		});
	}

	function func_tbl_agent_performance(start_date, end_date){
		tbl_agent_performance.DataTable({
		responsive: true,
		searchDelay: 500,
		processing: true,
		serverSide: true,
		searching: true,
		ajax: {
			    url: "/insights/getAgentPerformance",
			    type: 'POST',
			    headers: {
			        'X-CSRF-TOKEN': '{{ csrf_token() }}'
			    },
			     data: {
				    // parameters for custom backend script demo
				    "file_id" : "{{$file_id}}",
				    "start_date" : start_date,
				    "end_date" : end_date,
				},
			  },
		order: [[ 2, "desc" ]],
		columns: [
			    {data: 'name',orderable: false},
			    {data: 'total_assign',orderable: false},
			    {data: 'total_dial',orderable: true},
			    {data: 'total_process',orderable: true},
			    {data: 'total_pause',orderable: false},
			    {data: 'total_answered',orderable: false},
			    {data: 'total_busy',orderable: false},
			    {data: 'total_not_getting_service',orderable: false},
			    {data: 'total_just_ringing',orderable: false},
			    {data: 'total_hangup',orderable: false},
			],
		});
	}


	function get_leads_status(){
		jQuery.ajax({
		    url: "/insights/get_leads_status",
		    type: "POST",
		    data: 'file_id={{$file_id}}',
		    headers: {
			    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		    dataType: "json",
		    success: function(data) {
		        KTApp.unblockPage();
		        $("#demo-new_leads").html(data.new_leads);
		        $("#demo-ptp").html(data.ptp);
		        $("#demo-bptp").html(data.bptp);
		        $("#demo-bp").html(data.bp);
		        $("#demo-paid").html(data.paid);
		    },
		    error: function(data){


		   }
		});
	}

	function get_dialer_status(){
		jQuery.ajax({
		    url: "/insights/get_dialer_status",
		    type: "POST",
		    data: 'file_id={{$file_id}}',
		    headers: {
			    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		    dataType: "json",
		    success: function(data) {
		        KTApp.unblockPage();
		        $("#demo-total_dial").html(data.total_dial);
		        $("#demo-total_process").html(data.total_process);
		        $("#demo-total_pending_accounts").html(data.total_pending_accounts);
		        $("#demo-total_pause").html(data.total_pause);
		        $("#demo-total_answered").html(data.total_answered);
		        $("#demo-total_no_answered").html(data.total_no_answered);
		        
		    },
		    error: function(data){


		   }
		});
	}


	func_tbl_call_logs_summary(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
	func_tbl_agent_performance(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
	get_leads_status();
	get_dialer_status();

	$('#date_agent_performance').daterangepicker({
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

            $('#date_agent_performance .form-control').val(date);
            $('#tbl_agent_performance').DataTable().destroy();
            func_tbl_agent_performance(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });

    $('#date_call_logs_summary').daterangepicker({
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

            $('#date_call_logs_summary .form-control').val(date);
            $('#tbl_call_logs_summary').DataTable().destroy();
            func_tbl_call_logs_summary(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });

    jQuery(document).on('click', '#btn-export-call-logs', function(e){
    	e.preventDefault();
    	
    	KTApp.blockPage({
            overlayColor: '#000000',
            state: 'primary',
            message: 'Processing...'
        });
      	var date 		= $('#date_call_logs_summary .form-control').val();
      	var start_date 	= start.format('YYYY-MM-DD');
      	var end_date 	= end.format('YYYY-MM-DD');

      	if(date != ''){
      		var date_space = date.replace(/ /g, '');
      		var dates = date_space.split("|");
      		console.log(date_space);
      		start_date 	= dates[0];
      		end_date 	= dates[1];
      	}


    	var excel = '{{ route('pages.auto_dialer.export_callsummary_dialer', ['start_date' => ':start_date', 'end_date' => ':end_date', 'file_id' => $file_id]) }}';
            excel = excel.replace(':start_date', start_date);
            excel = excel.replace(':end_date', end_date);

        // $("#btn-export-call-logs").attr('href',excel);
        window.location = excel;
        KTApp.unblockPage();

    });	

    jQuery(document).on('click', '#btn-export-agent-performance', function(e){
    	e.preventDefault();
    	KTApp.blockPage({
            overlayColor: '#000000',
            state: 'primary',
            message: 'Processing...'
        });

      	var date 		= $('#date_call_logs_summary .form-control').val();
      	var start_date 	= start.format('YYYY-MM-DD');
      	var end_date 	= end.format('YYYY-MM-DD');

      	if(date != ''){
      		var date_space = date.replace(/ /g, '');
      		var dates = date_space.split("|");
      		console.log(date_space);
      		start_date 	= dates[0];
      		end_date 	= dates[1];
      	}


    	var excel = '{{ route('pages.auto_dialer.export_collectorperforme_dialer', ['start_date' => ':start_date', 'end_date' => ':end_date', 'file_id' => $file_id]) }}';
            excel = excel.replace(':start_date', start_date);
            excel = excel.replace(':end_date', end_date);

        // $("#btn-export-call-logs").attr('href',excel);
        window.location = excel;
        KTApp.unblockPage();

    });		
});
</script>
@endpush