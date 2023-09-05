@extends('templates.default')
@section('title', 'System Logs Reports')
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
													<div class="text-dark-50 font-size-lg mt-2">This table shows the system logs in the crm. it contains the Add, Edit or Delete action.</div>
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
							 <hr>
							 <!--begin: Search Form-->
							<form class="mb-15">
								<div class="row mb-6">
									
									<div class="col-lg-3 mb-lg-0 mb-6">
										<label>Filter Collector:</label>
										<select id="filter_collector" class="form-control">
											<option value="0">Select Collector</option>
											 	@foreach ($users as $user)
											 		<option value="{{$user->id}}">{{$user->name}}</option>
											 	@endforeach
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
							<table id="tbl_system_logs" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Name</th>
										<th>Actions</th>
										<th>Created At</th>
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
      var date 		= $('#call_date .form-control').val();
      var groups 		= '0';
      var status 		= '0';

      // 	if(collector == ''){
      // 		collector = 0;
      // 	}

    	// var excel = '{{ route('pages.reports.system_logs.export_system_logs', ['date' => ':date', 'collector' => ':collector']) }}';
      //       excel = excel.replace(':date', date);
      //       excel = excel.replace(':collector', collector);

      //   $("#export_reports").attr('href',excel);
      //   window.location = excel;
      KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });
       console.log(collector);

        $.ajax({
		        url: "/call_status/export_reports",
		        type: "POST",
		        data: 'report_type=system_logs&groups='+groups+'&collector='+collector+'&status='+status+'&date='+date,
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
	

	function func_table_reports(collector,date){
		var tbl_system_logs = $('#tbl_system_logs');
		tbl_system_logs.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/system_logs/getSystemLogsList",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
			          // parameters for custom backend script demo
			            "collector" : collector,
			            "date" : date,
			       },
				  },
			order: [[ 2, "desc" ]],
			columns: [
				        
				    {data: 'name',orderable: false},
				    {data: 'actions',orderable: false},
				    {data: 'created_at',orderable: false},
				],
			});
		KTApp.unblockPage();
	}

	func_table_reports(0,date);

	jQuery(document).on('change', '#filter_collector', function(e){
      var collector = $(this).val();
      var date = $('#call_date .form-control').val();

       KTApp.blockPage({
          overlayColor: '#000000',
          state: 'primary',
          message: 'Processing...'
       });

	   
	   $('#tbl_system_logs').DataTable().destroy();
		func_table_reports(collector,date);

    	
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
      		var collector = $("#filter_collector").val();

            $('#call_date .form-control').val(date);
            $('#tbl_system_logs').DataTable().destroy();
            func_table_reports(collector,date);
    });	





	


});
</script>
@endpush