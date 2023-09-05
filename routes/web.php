<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['web','cors']], function (){

    ##Home
    Route::get('/', ['uses' => 'HomeController@index','as'   => 'home']);
        
    ##Login
    Route::get('/login',['uses' => 'LoginController@login','as' => 'auth.login']);
    Route::get('/signout',['uses' => 'LoginController@getSignout','as' => 'auth.signout']);
    Route::post('/user_login',['uses' => 'LoginController@user_login','middleware' => ['guest']]);


});

Route::group(['middleware' => ['web','cors','auth']], function (){

	//Dashboard
    //This route is for the Dashboard. all the POST and GET from Dashboard is here.
    ##This line is for the GET.
    Route::get('/dashboard',['uses' => 'DashboardController@index','as' => 'pages.dashboard.index']);
    Route::get('/dashboard/view_status/{statuses}',['uses' => 'DashboardController@view_status','as' => 'pages.dashboard.view_status']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/dashboard/getDemographStatusLeads',['uses' => 'DashboardController@getDemographStatusLeads'])->name('dashboard.getDemographStatusLeads');
    Route::post('/dashboard/LeadsStatisticsGraph',['uses' => 'DashboardController@LeadsStatisticsGraph'])->name('dashboard.LeadsStatisticsGraph');
    Route::post('/dashboard/get_leads_status',['uses' => 'DashboardController@get_leads_status']);
    Route::post('/dashboard/getLeads',['uses' => 'DashboardController@getLeads']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

    //Call Monitoring
    //This route is for the Dashboard. all the POST and GET from Dashboard is here.
    ##This line is for the GET.
    Route::get('/call_monitoring',['uses' => 'DashboardController@call_monitoring','as' => 'pages.dashboard.call_monitoring']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/call_monitoring/get_call_monitoring',['uses' => 'DashboardController@get_call_monitoring']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Manage User
    //This route is for the Manage User. all the POST and GET from Manage User is here.
    ##This line is for the GET.
    Route::get('/users',['uses' => 'UsersController@index','as' => 'pages.users.index']);
    Route::get('/users/create',['uses' => 'UsersController@create','as' => 'pages.users.create']);
    Route::get('/users/edit/{id}',['uses' => 'UsersController@edit','as' => 'pages.users.edit']);
    Route::get('/users/edit_pass/{id}',['uses' => 'UsersController@edit_pass','as' => 'pages.users.password']);

    Route::get('/my_team',['uses' => 'UsersController@my_team','as' => 'pages.my_team.index']);

    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/users/store',['uses' => 'UsersController@store']);
    Route::post('/users/update',['uses' => 'UsersController@update']);
    Route::post('/users/delete',['uses' => 'UsersController@delete']);
    Route::post('/users/update_pass',['uses' => 'UsersController@update_pass']);
    Route::post('/users/getUsers',['uses' => 'UsersController@getUsers'])->name('users.getUsers');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Manage User
    //This route is for the Manage User. all the POST and GET from Manage User is here.
    ##This line is for the GET.
    Route::get('/user_profile',['uses' => 'UserProfileController@index','as' => 'pages.user_profile.index']);
    Route::get('/change_password',['uses' => 'UserProfileController@change_password','as' => 'pages.user_profile.change_password']);
    Route::get('/my_activity',['uses' => 'UserProfileController@my_activity','as' => 'pages.user_profile.my_activity']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/user_profile/update',['uses' => 'UserProfileController@update']);
    Route::post('/my_activity/getMyactivity',['uses' => 'UserProfileController@getMyactivity'])->name('groups.getMyactivity');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Manage Groups
    //This route is for the Manage Groups. all the POST and GET from Manage Groups is here.
    ##This line is for the GET.
    Route::get('/groups',['uses' => 'GroupsController@index','as' => 'pages.groups.index']);
    Route::get('/groups/edit/{id}',['uses' => 'GroupsController@edit','as' => 'pages.groups.edit']);
    Route::get('/groups/create',['uses' => 'GroupsController@create','as' => 'pages.groups.create']);
    Route::get('/groups/getUsers',['uses' => 'GroupsController@getUsers','as' => 'pages.groups.getUsers']);

    Route::get('/groups/create_file_header/{id}',['uses' => 'GroupsController@create_file_header','as' => 'pages.groups.create_file_header']);
    Route::get('/groups/validate_file/{id}',['uses' => 'GroupsController@validate_file','as' => 'pages.groups.validate']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/groups/store',['uses' => 'GroupsController@store']);
    Route::post('/groups/update',['uses' => 'GroupsController@update']);
    Route::post('/groups/delete',['uses' => 'GroupsController@delete']);
    Route::post('/groups/getGroups',['uses' => 'GroupsController@getGroups'])->name('groups.getGroups');

    Route::post('/groups/store_file_header',['uses' => 'GroupsController@store_file_header']);
    Route::post('/groups/upload_file',['uses' => 'GroupsController@upload_file']);
    Route::post('/groups/delete_file_header',['uses' => 'GroupsController@delete_file_header']);
    
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Manage Email
    //This route is for the Manage Email. all the POST and GET from Manage Email is here.
    ##This line is for the GET.
    Route::get('/emails',['uses' => 'EmailController@index','as' => 'pages.emails.index']);
    Route::get('/emails_settings',['uses' => 'EmailController@emails_settings','as' => 'pages.settings.emails.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/emails_settings/update_smtp',['uses' => 'EmailController@update_smtp'])->name('emails_settings.update_smtp');
    Route::post('/emails_settings/send_email',['uses' => 'EmailController@send_email'])->name('emails_settings.send_email');
    Route::post('/emails/store',['uses' => 'EmailController@store'])->name('emails.store');
    Route::post('/emails/select_email_templates',['uses' => 'EmailController@select_email_templates'])->name('emails.select_email_templates');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Manage Asterisk
    //This route is for the Manage Asterisk. all the POST and GET from Manage Asterisk is here.
    ##This line is for the GET.
    Route::get('/asterisk',['uses' => 'AsteriskController@index','as' => 'pages.settings.asterisk.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/asterisk/update',['uses' => 'AsteriskController@update'])->name('asterisk.update');
    Route::post('/asterisk/test_call',['uses' => 'AsteriskController@test_call']);
    Route::post('/asterisk/test_pbx_connection',['uses' => 'AsteriskController@test_pbx_connection']);
    Route::post('/asterisk/get_asterisk_status_debug',['uses' => 'AsteriskController@get_asterisk_status_debug']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

    //Manage Root Settings
    //This route is for the Manage Root Settings. all the POST and GET from Manage Root Settings is here.
    ##This line is for the GET.
    Route::get('/root',['uses' => 'RootController@index','as' => 'pages.settings.root.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/root/update',['uses' => 'RootController@update'])->name('root.update');
    Route::post('/root/update_features',['uses' => 'RootController@update_features'])->name('root.update_features');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Manage SMS Settings
    //This route is for the Manage SMS Settings. all the POST and GET from Manage SMS Settings is here.
    ##This line is for the GET.
    Route::get('/sms',['uses' => 'SMSController@index','as' => 'pages.settings.sms.index']);
    Route::get('/sms_template',['uses' => 'SMSController@sms_template','as' => 'pages.sms.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/sms/update',['uses' => 'SMSController@update'])->name('sms.update');
    Route::post('/sms_template/select_sms_templates',['uses' => 'SMSController@select_sms_templates'])->name('sms_template.select_sms_templates');
    Route::post('/sms_template/store',['uses' => 'SMSController@store'])->name('sms_template.store');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //System Logs Reports
    //This route is for the System Logs Reports all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/system_logs',['uses' => 'ReportsController@view_system_logs','as' => 'pages.reports.system_logs.index']);
    Route::get('/system_logs/export_system_logs/{date?}/{collector?}',['uses' => 'ReportsController@export_system_logs','as' => 'pages.reports.system_logs.export_system_logs']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/system_logs/getSystemLogsList',['uses' => 'ReportsController@getSystemLogsList'])->name('system_logs.getSystemLogsList');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //SMS Logs Reports
    //This route is for the SMS Logs Reports all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/sms_logs',['uses' => 'ReportsController@view_sms_logs','as' => 'pages.reports.sms.index']);
    Route::get('/sms_logs/export_sms_logs/{date?}',['uses' => 'ReportsController@export_sms_logs','as' => 'pages.reports.sms.export_sms_logs']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/sms_logs/getSMSLogsList',['uses' => 'ReportsController@getSMSLogsList'])->name('sms_logs.getSMSLogsList');
    Route::post('/sms_logs/getDemographSMS',['uses' => 'ReportsController@getDemographSMS'])->name('sms_logs.getDemographSMS');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################


    //Email Logs Reports
    //This route is for the Email Logs Reports all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/email_logs',['uses' => 'ReportsController@view_email_logs','as' => 'pages.reports.email.index']);
    Route::get('/email_logs/export_email_logs/{date?}',['uses' => 'ReportsController@export_email_logs','as' => 'pages.reports.email.export_email_logs']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/email_logs/getEmailLogsList',['uses' => 'ReportsController@getEmailLogsList'])->name('email_logs.getEmailLogsList');
    Route::post('/email_logs/getDemographEmail',['uses' => 'ReportsController@getDemographEmail'])->name('email_logs.getDemographEmail');
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

    //Dialer Settings
    //This route is for the Dialer Settings all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/dialer',['uses' => 'DialerController@index','as' => 'pages.settings.dialer.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/dialer/update',['uses' => 'DialerController@update']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

    //Pullout Settings
    //This route is for the Pullout Settings all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/pullout',['uses' => 'PullOutAccountsController@index','as' => 'pages.pullout.index']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/pullout/getLeadsList',['uses' => 'PullOutAccountsController@getLeadsList'])->name('pullout.getLeadsList');
    Route::post('/pullout/getPulloutLogs',['uses' => 'PullOutAccountsController@getPulloutLogs'])->name('pullout.getPulloutLogs');
    Route::post('/pullout/update_pullouts',['uses' => 'PullOutAccountsController@update_pullouts']);
    Route::post('/pullout/import_pullout',['uses' => 'PullOutAccountsController@import_pullout']);
    Route::post('/pullout/update_pullout_all',['uses' => 'PullOutAccountsController@update_pullout_all']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################

    //Status Settings
    //This route is for the Pullout Settings all the POST and GET from is here.
    ##This line is for the GET.
    Route::get('/statuses',['uses' => 'StatusesController@index','as' => 'pages.statuses.index']);
    Route::get('/statuses/edit/{id}',['uses' => 'StatusesController@edit','as' => 'pages.statuses.edit']);
    ##End of the line for the GET.
    #######################################################################################
    #######################################################################################
    ##This line is for the POST.
    Route::post('/statuses/getStatuses',['uses' => 'StatusesController@getStatuses'])->name('statuses.getStatuses');
    Route::post('/statuses/store',['uses' => 'StatusesController@store']);
    Route::post('/statuses/update',['uses' => 'StatusesController@update']);
    Route::post('/statuses/delete',['uses' => 'StatusesController@delete']);
    Route::post('/statuses/import_statuses',['uses' => 'StatusesController@import_statuses']);
    ##End of the line for the POST.
    #######################################################################################
    #######################################################################################



});

