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

	//Auto Dialer
    //This route is for the Auto Dialer all the POST and GET from is here.
    ##This line is for the GET.
   	Route::get('/auto_dialer',['uses' => 'AutoDialerController@index','as' => 'pages.auto_dialer.index']);
   	Route::get('/auto_dialer/create',['uses' => 'AutoDialerController@create','as' => 'pages.auto_dialer.create']);
   	Route::get('/auto_dialer/dialer/{file_id}',['uses' => 'AutoDialerController@dialer','as' => 'pages.auto_dialer.dialer']);
   	Route::get('/auto_dialer/edit/{file_id}',['uses' => 'AutoDialerController@edit','as' => 'pages.auto_dialer.edit']);
   	Route::get('/auto_dialer/add_leads/{file_id}',['uses' => 'AutoDialerController@add_leads','as' => 'pages.auto_dialer.add_leads']);
    Route::get('/auto_dialer/view_assign/{id}/{file_id}',['uses' => 'AutoDialerController@view_assign','as' => 'pages.auto_dialer.view_assign']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/auto_dialer/getCampaign',['uses' => 'AutoDialerController@getCampaign'])->name('auto_dialer.getCampaign');
    Route::post('/auto_dialer/getAddLeads',['uses' => 'AutoDialerController@getAddLeads'])->name('auto_dialer.getAddLeads');
    Route::post('/auto_dialer/getCampaignCollectors',['uses' => 'AutoDialerController@getCampaignCollectors'])->name('auto_dialer.getCampaignCollectors');
    Route::post('/auto_dialer/store',['uses' => 'AutoDialerController@store']);
    Route::post('/auto_dialer/update',['uses' => 'AutoDialerController@update']);
    Route::post('/auto_dialer/delete',['uses' => 'AutoDialerController@delete']);
    Route::post('/auto_dialer/add_leads_to_campaign',['uses' => 'AutoDialerController@add_leads_to_campaign']);
    Route::post('/auto_dialer/add_all_leads_to_campaign',['uses' => 'AutoDialerController@add_all_leads_to_campaign']);
    Route::post('/auto_dialer/get_total_added_leads',['uses' => 'AutoDialerController@get_total_added_leads']);
    Route::post('/auto_dialer/add_collectors_to_campaign',['uses' => 'AutoDialerController@add_collectors_to_campaign']);
    Route::post('/auto_dialer/activate_campaign',['uses' => 'AutoDialerController@activate_campaign']);
    Route::post('/auto_dialer/disabled_campaign',['uses' => 'AutoDialerController@disabled_campaign']);
    Route::post('/auto_dialer/reset_campaign',['uses' => 'AutoDialerController@reset_campaign']);
    Route::post('/auto_dialer/pause_campaign',['uses' => 'AutoDialerController@pause_campaign']);
    Route::post('/auto_dialer/manager_call',['uses' => 'AutoDialerController@manager_call']);
    Route::post('/auto_dialer/getLeadsAssign',['uses' => 'AutoDialerController@getLeadsAssign']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Insights
    //This route is for the Insights all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/auto_dialer/insights/{file_id}',['uses' => 'InsightsController@insights','as' => 'pages.auto_dialer.insights']);

    Route::get('/auto_dialer/export_callsummary_dialer/{start_date?}/{end_date?}/{file_id?}',['uses' => 'InsightsController@export_callsummary_dialer',
        'as' => 'pages.auto_dialer.export_callsummary_dialer']);

    Route::get('/auto_dialer/export_collectorperforme_dialer/{start_date?}/{end_date?}/{file_id?}',['uses' => 'InsightsController@export_collectorperforme_dialer',
        'as' => 'pages.auto_dialer.export_collectorperforme_dialer']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/insights/get_leads_status',['uses' => 'InsightsController@get_leads_status']);
    Route::post('/insights/get_dialer_status',['uses' => 'InsightsController@get_dialer_status']);
    Route::post('/insights/getCallLogsList',['uses' => 'InsightsController@getCallLogsList'])->name('insights.getCallLogsList');
    Route::post('/insights/getAgentPerformance',['uses' => 'InsightsController@getAgentPerformance'])->name('insights.getAgentPerformance');
    #######################################################################################
    #######################################################################################


    //AGENT Dialer
    //This route is for the AGENT Dialer all the POST and GET from is here.
    ##This line is for the GET.
   	Route::get('/agent_dialer/{file_id}',['uses' => 'AgentDialerController@index','as' => 'pages.agent.auto_dialer']);
    // Route::get('/agent_dialer/{file_id}',['uses' => 'AgentDialerController@index','as' => 'error.404']);
    Route::get('/agent_dialer/view_leads_data/{file_id}/{user_id}/{leads_id}',['uses' => 'AgentDialerController@view_leads_data','as' => 'pages.agent.view_leads_data']);
    Route::get('/agent_dialer/{file_id}/search_account_data',['uses' => 'AgentDialerController@search_account_data']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/agent_dialer/logged_status',['uses' => 'AgentDialerController@logged_status']);
    Route::post('/agent_dialer/check_campaign_login',['uses' => 'AgentDialerController@check_campaign_login']);
    Route::post('/agent_dialer/get_leads_data',['uses' => 'AgentDialerController@get_leads_data']);
    Route::post('/agent_dialer/agent_call',['uses' => 'AgentDialerController@agent_call']);
    Route::post('/agent_dialer/pause_status',['uses' => 'AgentDialerController@pause_status']);
    Route::post('/agent_dialer/break_time_status',['uses' => 'AgentDialerController@break_time_status']);
    Route::post('/agent_dialer/get_call_status_view',['uses' => 'AgentDialerController@get_call_status_view']);

    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Previous Data from Auto Dialer
    //This route is for the Auto Dialer all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/previous_data/{file_id}/{leads_id}',['uses' => 'AgentDialerController@previous_data','as' => 'pages.agent.previous']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/previous_data/get_previous_leads_data',['uses' => 'AgentDialerController@get_previous_leads_data']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Previous Data from Auto Dialer
    //This route is for the Auto Dialer all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/status_dialer/{group_id}/{statuses}/{date}',['uses' => 'StatusDialerController@status_dialer','as' => 'pages.agent.status_dialer']);
    Route::get('/status_dialer/view_leads_data/{collector_id}/{date}/{statuses}/{group_id}/{leads_id}',['uses' => 'StatusDialerController@view_leads_data','as' => 'pages.agent.view_leads_data']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/status_dialer/get_leads_data_with_filter',['uses' => 'StatusDialerController@get_leads_data_with_filter']);
    Route::post('/status_dialer/agent_call',['uses' => 'StatusDialerController@agent_call']);
    Route::post('/status_dialer/access_campaign_status',['uses' => 'StatusDialerController@access_campaign_status']);
    
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

});
?>