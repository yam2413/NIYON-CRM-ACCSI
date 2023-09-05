@extends('templates.default')
@section('title', 'Email Log Reports')
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
								<ul class="nav nav-tabs nav-bold nav-tabs-line">
										<li class="nav-item">
											 <div class='input-group' id='call_date'>
												<input type='text' class="form-control" readonly name="call_date"  placeholder="YYYY-MM-DD / YYYY-MM-DD"/>
												<div class="input-group-append">
													<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
												</div>
											 </div>
										</li>
									</ul>
								
							</div>
							<div class="card-toolbar">
								 <ul class="nav nav-tabs nav-bold nav-tabs-line">
										
										<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
										<li class="nav-item">
											<a href="#" id="export_reports" class="btn btn-success" data-toggle="tooltip" data-theme="dark" title="Download this report using excel file.">
											    <i class="far fa-file-excel"></i> Download
											</a>
										</li>
									</ul>
					
							</div>
						</div>
						
						<div class="card-body">
							 <p class="mb-0"><span class="card-icon">
									<i class="fas fa-info-circle text-primary"></i>
								</span>This table shows the email logs in the crm.
							 </p>
							
							<!--Begin::Row-->
								<div class="row">
									<div class="col-xl-3">
										<!--begin::Stats Widget 25-->
										<div class="card card-custom bg-light-success card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<i class="flaticon2-send-1"></i>
												<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block" id="demo-success">0</span>
												<span class="font-weight-bold text-muted font-size-sm">Total Success</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 25-->
									</div>
									<div class="col-xl-3">
										<!--begin::Stats Widget 26-->
										<div class="card card-custom bg-light-danger card-stretch gutter-b">
											<!--begin::ody-->
											<div class="card-body">
												<i class="flaticon-warning-sign"></i>
												<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block" id="demo-error">0</span>
												<span class="font-weight-bold text-muted font-size-sm">Total Error</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 26-->
									</div>
									<div class="col-xl-3">
										<!--begin::Stats Widget 28-->
										<div class="card card-custom bg-light-warning card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<i class="flaticon-reply"></i>
												<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block" id="demo-processing">0</span>
												<span class="font-weight-bold text-muted font-size-sm">Processing</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stat: Widget 28-->
									</div>
								</div>
								<!--End::Row-->

							<!--begin: Datatable-->
							<table id="tbl_email_logs" class="table table-bordered table-hover table-checkable">
								<thead>
									<tr>
										<th>Borrower Name</th>
										<th>To</th>
										<th>body</th>
										<th>Status</th>
										<th>Added By</th>
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
    	
      	var date = $('#call_date .form-control').val();

    	var excel = '{{ route('pages.reports.email.export_email_logs', ['date' => ':date']) }}';
            excel = excel.replace(':date', date);

        $("#export_reports").attr('href',excel);
        window.location = excel;

    });

    var DEMOGRAPH_GROUPS = function(date) {
			KTApp.blockPage({
	           overlayColor: '#000000',
	           state: 'primary',
	           message: 'Processing...'
	        });

	       jQuery.ajax({
	          url: "/email_logs/getDemographEmail",
	          type: "POST",
	          data: 'date='+date,
	          headers: {
		             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		            },
	          dataType: "json",
	          success: function(data) {
	          	 KTApp.unblockPage();
	          	 $("#demo-success").text(data.success);
	          	 $("#demo-error").text(data.error);
	          	 $("#demo-processing").text(data.processing);
	          	 // $("#demo-comment_post").text(data.comment_post);
	          	 // $("#demo-unique_active").text(data.unique_active);
	          	 // $("#demo-unique_inactive").text(data.unique_inactive);
	          	 // $("#demo-unique_active_mob_chat").text(data.unique_active_mob_chat);
	          	 // $("#demo-unique_active_mob_app").text(data.unique_active_mob_app);
	          },
	          error: function(data){


	          }
	        });
	        
	}

	DEMOGRAPH_GROUPS(date);

	function func_table_reports(date){
		var tbl_email_logs = $('#tbl_email_logs');
		tbl_email_logs.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			searching: true,
			ajax: {
				    url: "/email_logs/getEmailLogsList",
				    type: 'POST',
				    headers: {
				        'X-CSRF-TOKEN': '{{ csrf_token() }}'
				    },
				    data: {
			          // parameters for custom backend script demo
			            "date" : date,
			       },
				  },
			order: [[ 5, "desc" ]],
			columns: [
				        
				    {data: 'full_name',orderable: false},
				    {data: 'to',orderable: false},
				    {data: 'body',orderable: false},
				    {data: 'status',orderable: false},
				    {data: 'user',orderable: false},
				    {data: 'created_at',orderable: false},
				],
			});
		KTApp.unblockPage();
	}

	func_table_reports(date);

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

            $('#call_date .form-control').val(date);
            $('#tbl_email_logs').DataTable().destroy();
            func_table_reports(date);
            DEMOGRAPH_GROUPS(date);
    });	





	


});
</script>
@endpush