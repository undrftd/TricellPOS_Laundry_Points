<?php

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

Route::get('/', 'LoginController@index'); 
Route::post('verify', 'LoginController@verify'); 
Route::get('shutdown', 'LoginController@shutdown');
Route::get('logout', 'LoginController@logout');

Route::group(['middleware' => ['admin']], function () {
	//Dashboard
	Route::get('dashboard', 'Admin\DashboardController@index');

	//POS
	Route::get('sales', 'Admin\PointofSaleController@index');
	Route::get('sales/buttons', 'Admin\PointofSaleController@buttonload');
	Route::post('sales/member_loadpayment', 'Admin\PointofSaleController@member_loadpayment');
	Route::post('sales/member_pointspayment', 'Admin\PointofSaleController@member_pointspayment');
	Route::get('sales/member_autocomplete', 'Admin\PointofSaleController@member_autocomplete');
	Route::post('sales/guest_cashpayment', 'Admin\PointofSaleController@guest_cashpayment');
	Route::post('sales/member_reload', 'Admin\PointofSaleController@reload');

	//Queueing
	Route::get('queue', 'Admin\QueueController@index');
	Route::post('queue/view_status', 'Admin\QueueController@viewstatus');
	Route::post('queue/showdetails', 'Admin\QueueController@showdetails');
	Route::post('queue/switch', 'Admin\QueueController@switch');

	//Sales Logs
	Route::get('logs/sales', 'Admin\SalesLogsController@index');
	Route::get('logs/sales/showdetails/{id}', 'Admin\SalesLogsController@showdetails');
	Route::post('logs/sales/delete_sales', 'Admin\SalesLogsController@destroy');
	Route::get('logs/sales/filter', 'Admin\SalesLogsController@filter');
	Route::get('logs/sales/export', 'Admin\SalesLogsController@export');

	//Reload Logs
	Route::get('logs/reload', 'Admin\ReloadLogsController@index');
	Route::get('logs/reload/showdetails/{id}', 'Admin\ReloadLogsController@showdetails');
	Route::get('logs/reload/filter', 'Admin\ReloadLogsController@filter');
	Route::post('logs/reload/delete_reload', 'Admin\ReloadLogsController@destroy');
	Route::get('/logs/reload/export', 'Admin\ReloadLogsController@export');

	//Services
	Route::get('services/washers', 'Admin\ServicesController@index');
	Route::get('services/dryers', 'Admin\ServicesController@dryer');
	Route::get('services/products', 'Admin\ServicesController@product');
	Route::post('services/add_service', 'Admin\ServicesController@create');
	Route::post('services/update_service', 'Admin\ServicesController@edit');
	Route::post('services/delete_service', 'Admin\ServicesController@destroy');
	Route::get('services/search_washer', 'Admin\ServicesController@search_washer');
	Route::get('services/search_dryer', 'Admin\ServicesController@search_dryer');
	Route::get('services/search_product', 'Admin\ServicesController@search_product');
	Route::get('inventory/healthy_stocks', 'Admin\ServicesController@healthystocks');
	
	//Accounts - Member
	Route::get('accounts/members', 'Admin\MemberAccountsController@index');
	Route::post('accounts/update_member', 'Admin\MemberAccountsController@edit');
	Route::post('accounts/delete_member', 'Admin\MemberAccountsController@destroy');
	Route::post('accounts/add_member', 'Admin\MemberAccountsController@create');
	Route::post('accounts/reload_member', 'Admin\MemberAccountsController@reload');
	Route::get('accounts/search_member', 'Admin\MemberAccountsController@search');

	//Accounts - Admin
	Route::get('accounts/admin', 'Admin\AdminAccountsController@index');
	Route::get('accounts/view_admindetails', 'Admin\AdminAccountsController@show');
	Route::post('accounts/add_admin', 'Admin\AdminAccountsController@create');
	Route::post('accounts/update_admin', 'Admin\AdminAccountsController@edit');
	Route::get('accounts/search_admin', 'Admin\AdminAccountsController@search');

	//Accounts - Staff
	Route::get('accounts/staff', 'Admin\StaffAccountsController@index');
	Route::post('accounts/add_staff', 'Admin\StaffAccountsController@create');
	Route::get('accounts/edit_staff', 'Admin\StaffAccountsController@show');
	Route::post('accounts/update_staff', 'Admin\StaffAccountsController@edit');
	Route::post('accounts/delete_staff', 'Admin\StaffAccountsController@destroy');
	Route::get('accounts/search_staff', 'Admin\StaffAccountsController@search');

	//Preferences
	Route::get('preferences/backup', 'Admin\BackupController@index');
	Route::get('backup/create', 'Admin\BackupController@create');
   	Route::get('backup/download/{file_name}', 'Admin\BackupController@download');
	Route::get('backup/delete/{file_name}', 'Admin\BackupController@delete');
	Route::get('preferences/backup/search', 'Admin\BackupController@search');
	Route::get('preferences/profile', 'Admin\ProfileController@index');
	Route::post('preferences/update_profile', 'Admin\ProfileController@edit');
	Route::get('preferences/discounts', 'Admin\DiscountsController@index');
	Route::post('preferences/add_discount', 'Admin\DiscountsController@create');
	Route::post('preferences/update_discount', 'Admin\DiscountsController@edit');
	Route::post('preferences/delete_discount', 'Admin\DiscountsController@destroy');
	Route::get('preferences/discounts/search', 'Admin\DiscountsController@search');

	//Account
	Route::get('/account', 'Admin\AccountController@index');
	Route::post('/update_account', 'Admin\AccountController@edit');

	//Timesheet
	Route::get('timesheet', 'Admin\TimesheetController@index');
	Route::post('timesheet/time_in', 'Admin\TimesheetController@time_in');
	Route::get('timesheet/time_out', 'Admin\TimesheetController@time_out');
	Route::get('timesheet/filter', 'Admin\TimesheetController@filter');
	Route::get('timesheet/export', 'Admin\TimesheetController@export');
   	
});

Route::group(['middleware' => ['staff']], function () {
	//POS
	Route::get('staff/sales', 'Staff\PointofSaleController@index');
	Route::get('staff/sales/buttons', 'Staff\PointofSaleController@buttonload');
	Route::post('staff/sales/member_loadpayment', 'Staff\PointofSaleController@member_loadpayment');
	Route::post('staff/sales/member_pointspayment', 'Staff\PointofSaleController@member_pointspayment');
	Route::get('staff/sales/member_autocomplete', 'Staff\PointofSaleController@member_autocomplete');
	Route::post('staff/sales/guest_cashpayment', 'Staff\PointofSaleController@guest_cashpayment');
	Route::post('staff/sales/member_reload', 'Staff\PointofSaleController@reload');

   	//Queueing
   	Route::get('staff/queue', 'Staff\QueueController@index');
   	Route::post('staff/queue/view_status', 'Staff\QueueController@viewstatus');
   	Route::post('staff/queue/showdetails', 'Staff\QueueController@showdetails');
   	Route::post('staff/queue/switch', 'Staff\QueueController@switch');

	//Sales Logs
	Route::get('staff/logs/sales', 'Staff\SalesLogsController@index');
	Route::get('staff/logs/sales/showdetails/{id}', 'Staff\SalesLogsController@showdetails');
	Route::post('staff/logs/sales/delete_sales', 'Staff\SalesLogsController@destroy');
	Route::get('staff/logs/sales/filter', 'Staff\SalesLogsController@filter');
	Route::get('staff/logs/sales/export', 'Staff\SalesLogsController@export');

	//Reload Logs
	Route::get('staff/logs/reload', 'Staff\ReloadLogsController@index');
	Route::get('staff/logs/reload/showdetails/{id}', 'Staff\ReloadLogsController@showdetails');
	Route::get('staff/logs/reload/filter', 'Staff\ReloadLogsController@filter');
	Route::post('staff/logs/reload/delete_reload', 'Staff\ReloadLogsController@destroy');
	Route::get('staff/logs/reload/export', 'Staff\ReloadLogsController@export');

	//Accounts - Member
	Route::get('/staff/accounts/members', 'Staff\MemberAccountsController@index');
	Route::post('/staff/accounts/update_member', 'Staff\MemberAccountsController@edit');
	Route::post('/staff/accounts/delete_member', 'Staff\MemberAccountsController@destroy');
	Route::post('/staff/accounts/add_member', 'Staff\MemberAccountsController@create');
	Route::post('/staff/accounts/reload_member', 'Staff\MemberAccountsController@reload');
	Route::get('/staff/accounts/search_member', 'Staff\MemberAccountsController@search');

	//Preferences
	Route::get('staff/preferences/backup', 'Staff\BackupController@index');
	Route::get('staff/backup/create', 'Staff\BackupController@create');
   	Route::get('staff/backup/download/{file_name}', 'Staff\BackupController@download');
	Route::get('staff/backup/delete/{file_name}', 'Staff\BackupController@delete');
	Route::get('staff/preferences/backup/search', 'Staff\BackupController@search');
	Route::get('staff/preferences/profile', 'Staff\ProfileController@index');
	Route::post('staff/preferences/update_profile', 'Staff\ProfileController@edit');
	Route::get('staff/preferences/discounts', 'Staff\DiscountsController@index');
	Route::post('staff/preferences/add_discount', 'Staff\DiscountsController@create');
	Route::post('staff/preferences/update_discount', 'Staff\DiscountsController@edit');
	Route::post('staff/preferences/delete_discount', 'Staff\DiscountsController@destroy');
	Route::get('staff/preferences/discounts/search', 'Staff\DiscountsController@search');

	//Account
	Route::get('staff/account', 'Staff\AccountController@index');
	Route::post('staff/update_account', 'Staff\AccountController@edit');

	//Timesheet
	Route::get('staff/timesheet', 'Staff\TimesheetController@index');
	Route::post('staff/timesheet/time_in', 'Staff\TimesheetController@time_in');
	Route::get('staff/timesheet/time_out', 'Staff\TimesheetController@time_out');
	Route::get('staff/timesheet/filter', 'Staff\TimesheetController@filter');
	Route::get('staff/timesheet/export', 'Staff\TimesheetController@export');

});