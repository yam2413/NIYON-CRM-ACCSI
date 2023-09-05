<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web','cors','auth']], function (){
	//Leads
    //This route is for the Leads. all the POST and GET from Leads is here.
    ##This line is for the GET.
    Route::get('/leads',['uses' => 'LeadsController@index','as' => 'pages.leads.index']);
    Route::get('/leads/profile/{profile_id}',['uses' => 'LeadsController@profile','as' => 'pages.leads.profile']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/leads/getLeads',['uses' => 'LeadsController@getLeads'])->name('leads.getLeads');
    Route::post('/leads/getPTPHistories',['uses' => 'LeadsController@getPTPHistories'])->name('leads.getPTPHistories');
    Route::post('/leads/getCallLogs',['uses' => 'LeadsController@getCallLogs'])->name('leads.getCallLogs');
    Route::post('/leads/getLoanHistory',['uses' => 'LeadsController@getLoanHistory'])->name('leads.getLoanHistory');
    Route::post('/leads/manual_call',['uses' => 'LeadsController@manual_call'])->name('leads.manual_call');
    Route::post('/leads/set_as_priority',['uses' => 'LeadsController@set_as_priority'])->name('leads.set_as_priority');
    Route::post('/leads/update_reassign',['uses' => 'LeadsController@update_reassign'])->name('leads.update_reassign');
    Route::post('/leads/store_ptp',['uses' => 'LeadsController@store_ptp'])->name('leads.store_ptp');
    Route::post('/leads/send_email',['uses' => 'LeadsController@send_email'])->name('leads.send_email');
    Route::post('/leads/send_sms',['uses' => 'LeadsController@send_sms'])->name('leads.send_sms');
    Route::post('/leads/update_personal_details',['uses' => 'LeadsController@update_personal_details'])->name('leads.update_personal_details');
    Route::post('/leads/get_group_status',['uses' => 'LeadsController@get_group_status']);
    Route::post('/leads/store_manual_number',['uses' => 'LeadsController@store_manual_number']);
    Route::post('/leads/get_contact_no',['uses' => 'LeadsController@get_contact_no']);
    Route::post('/leads/get_best_time_to_call',['uses' => 'LeadsController@get_best_time_to_call']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Leads Reassign
    //This route is for the Leads Reassign. all the POST and GET from Leads Reassign is here.
    ##This line is for the GET.
    Route::get('/reassign',['uses' => 'ReassignController@index','as' => 'pages.leads.reassign.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/reassign/getLeadsReAssign',['uses' => 'ReassignController@getLeadsReAssign'])->name('reassign.getLeadsReAssign');
    Route::post('/reassign/get_user_list',['uses' => 'ReassignController@get_user_list'])->name('reassign.get_user_list');
    Route::post('/reassign/get_new_user_list',['uses' => 'ReassignController@get_new_user_list'])->name('reassign.get_new_user_list');
    Route::post('/reassign/update_reassign',['uses' => 'ReassignController@update_reassign'])->name('reassign.update_reassign');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Leads File Upload
    //This route is for the Leads Reassign. all the POST and GET from Leads Reassign is here.
    ##This line is for the GET.
    Route::get('/file_uploads',['uses' => 'FileUploadController@index','as' => 'pages.file_uploads.index']);
    Route::get('/file_uploads/check_uploads/{uniq_id}',['uses' => 'FileUploadController@check_uploads','as' => 'pages.file_uploads.check_uploads']);
    Route::get('/file_uploads/check_uploads/{uniq_id}/view_uploaded',['uses' => 'FileUploadController@view_uploaded','as' => 'pages.file_uploads.view_uploaded']);
    Route::get('/export_new_leads_logs/{file_id?}/{type?}/{upload_type?}',['uses' => 'FileUploadController@export_new_leads_logs','as' => 'pages.file_uploads.export_new_leads_logs']);
    Route::get('/export_file_template/{group_id?}/{file_name?}',['uses' => 'FileUploadController@export_file_template','as' => 'pages.file_uploads.export_file_template']);

    Route::get('/update_leads',['uses' => 'FileUploadController@view_update_leads','as' => 'pages.file_uploads.update_leads.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/file_uploads/upload_file',['uses' => 'FileUploadController@upload_file'])->name('file_uploads.upload_file');
    Route::post('/file_uploads/upload_file_specify',['uses' => 'FileUploadController@upload_file_specify'])->name('file_uploads.upload_file_specify');
    Route::post('/file_uploads/cancel_upload_file',['uses' => 'FileUploadController@cancel_upload_file'])->name('file_uploads.cancel_upload_file');
    Route::post('/file_uploads/getUploadedLeads',['uses' => 'FileUploadController@getUploadedLeads'])->name('file_uploads.getUploadedLeads');
    Route::post('/file_uploads/getUploadLogs',['uses' => 'FileUploadController@getUploadLogs'])->name('file_uploads.getUploadLogs');
    Route::post('/file_uploads/sync_leads',['uses' => 'FileUploadController@sync_leads'])->name('file_uploads.sync_leads');
    Route::post('/file_uploads/undo_upload_file',['uses' => 'FileUploadController@undo_upload_file'])->name('file_uploads.undo_upload_file');
    Route::post('/file_uploads/data_type_upload_file',['uses' => 'FileUploadController@data_type_upload_file'])->name('file_uploads.data_type_upload_file');
    
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Leads Paid Accounts
    //This route is for the Paid Accounts. all the POST and GET from Paid Accounts is here.
    ##This line is for the GET.
    Route::get('/upload_paids',['uses' => 'PaidController@index','as' => 'pages.file_uploads.paids.index']);
    Route::get('/upload_paids/check_uploads/{uniq_id}',['uses' => 'PaidController@check_uploads','as' => 'pages.file_uploads.paids.check_uploads']);
    Route::get('/upload_paids/check_uploads/{uniq_id}/view_uploaded',['uses' => 'PaidController@view_uploaded','as' => 'pages.file_uploads.paids.view_uploaded']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/upload_paids/getPaidLogs',['uses' => 'PaidController@getPaidLogs'])->name('upload_paids.getPaidLogs');
    Route::post('/upload_paids/upload_paid_file',['uses' => 'PaidController@upload_paid_file'])->name('upload_paids.upload_paid_file');
    Route::post('/upload_paids/data_type_upload_file',['uses' => 'PaidController@data_type_upload_file'])->name('upload_paids.data_type_upload_file');
    Route::post('/upload_paids/getUploadedPaidLeads',['uses' => 'PaidController@getUploadedPaidLeads'])->name('upload_paids.getUploadedPaidLeads');
    Route::post('/upload_paids/sync_paids_leads',['uses' => 'PaidController@sync_paids_leads'])->name('upload_paids.sync_paids_leads');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

    //Report Logs Reports
    //This route is for the Call Logs Reports all the POST and GET from Call Logs Reports is here.
    ##This line is for the GET.
    Route::get('/report_logs',['uses' => 'ReportsController@view_report_logs','as' => 'pages.reports.report_logs.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/report_logs/get_Report_Logs',['uses' => 'ReportsController@get_Report_Logs'])->name('report_logs.get_Report_Logs');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Leads Call Logs Reports
    //This route is for the Call Logs Reports all the POST and GET from Call Logs Reports is here.
    ##This line is for the GET.
   	Route::get('/call_logs',['uses' => 'ReportsController@view_call_logs','as' => 'pages.reports.call_logs.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/call_logs/getCallLogsList',['uses' => 'ReportsController@getCallLogsList'])->name('call_logs.getCallLogsList');
    Route::post('/call_logs/download_recordings',['uses' => 'ReportsController@download_recordings'])->name('call_logs.download_recordings');
    Route::post('/call_logs/CallLogsStatisticsGraph',['uses' => 'ReportsController@CallLogsStatisticsGraph'])->name('call_logs.CallLogsStatisticsGraph');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Leads Summary of Calls Reports
    //This route is for the Summary of Calls Reports all the POST and GET from Summary of Calls Reports is here.
    ##This line is for the GET.
   	Route::get('/summary_calls',['uses' => 'ReportsController@view_summary_calls','as' => 'pages.reports.summary_calls.index']);
   	Route::get('/summary_calls/export_summary_calls/{date?}/{group?}/{collector?}',['uses' => 'ReportsController@export_summary_calls',
        'as' => 'pages.reports.summary_calls.export_summary_calls']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/summary_calls/getSummaryCallsList',['uses' => 'ReportsController@getSummaryCallsList'])->name('summary_calls.getSummaryCallsList');
    Route::post('/summary_calls/get_user_list',['uses' => 'ReportsController@get_user_list'])->name('summary_calls.get_user_list');
    Route::post('/summary_calls/SummaryCallsPie',['uses' => 'ReportsController@SummaryCallsPie'])->name('summary_calls.SummaryCallsPie');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

    //Leads Call Status Reports
    //This route is for the Call Status Reports all the POST and GET from Call Status Reports is here.
    ##This line is for the GET.
   	Route::get('/call_status',['uses' => 'ReportsController@view_call_status','as' => 'pages.reports.call_status.index']);
   	Route::get('/call_status/export_call_status/{date?}/{group?}/{collector?}/{status?}',['uses' => 'ReportsController@export_call_status','as' => 'pages.reports.call_status.export_call_status']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
   	Route::post('/call_status/getCallStatusList',['uses' => 'ReportsController@getCallStatusList'])->name('call_status.getCallStatusList');
    Route::post('/call_status/CallStatusGraph',['uses' => 'ReportsController@CallStatusGraph'])->name('call_status.CallStatusGraph');
    Route::post('/call_status/export_reports',['uses' => 'ReportsController@export_reports']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Leads Logs Reports
    //This route is for the Leads Logs Reports all the POST and GET from Call Status Reports is here.
    ##This line is for the GET.
   	Route::get('/leads_logs',['uses' => 'ReportsController@view_leads_logs','as' => 'pages.reports.leads_logs.index']);
    Route::get('/leads_logs/export_leads_logs/{date?}/{collector?}',['uses' => 'ReportsController@export_leads_logs','as' => 'pages.reports.leads_logs.export_leads_logs']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
   	Route::post('/leads_logs/getLeadsLogsList',['uses' => 'ReportsController@getLeadsLogsList'])->name('leads_logs.getLeadsLogsList');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

    //Leads Status
    //This route is for the Leads Logs Reports all the POST and GET from Call Status Reports is here.
    ##This line is for the GET.
    Route::get('/leads_status',['uses' => 'LeadsStatusController@index','as' => 'pages.leads_status.index']);
    Route::get('/leads_status/import',['uses' => 'LeadsStatusController@import_account_status','as' => 'pages.leads_status.import']);
    Route::get('/leads_status/view_import/{file_id?}/{group_id?}',['uses' => 'LeadsStatusController@view_import','as' => 'pages.leads_status.view_import']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/leads_status/update_status',['uses' => 'LeadsStatusController@update_status'])->name('leads_logs.update_status');
    Route::post('/leads_status/getLeadsList',['uses' => 'LeadsStatusController@getLeadsList'])->name('leads_logs.getLeadsList');
    Route::post('/leads_status/upload_account_status',['uses' => 'LeadsStatusController@upload_account_status'])->name('leads_logs.upload_account_status');
    Route::post('/leads_status/get_imported_list',['uses' => 'LeadsStatusController@get_imported_list'])->name('leads_logs.get_imported_list');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    

});

?>