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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


/**
 * Routes for the invite system
 * See https://laravel-news.com/user-invitation-system
 */
// {token} is a required parameter that will be exposed to us in the controller method
Route::get('accept/{token}', 'InviteController@accept')->name('accept');
Route::post('create_password', 'InviteController@createPassword')->name('create_password');
Route::post('/password-strength', 'PasswordStrengthApi@calc');

Route::group(['middleware' => 'auth'], function () {


    Route::get('/home', 'HomeController@index')->name('home');


    Route::group(['prefix' => 'history'], function () {
        Route::get('department/{department}', 'HistoryController@department');
    });

    ///////////////////////////////////////////////////////////////////////////////
    // Invite Routes
    ///////////////////////////////////////////////////////////////////////////////
    //    Route::get('invite', 'InviteController@invite')->name('invite');
    //    Route::post('invite', 'InviteController@process')->name('process');
    Route::get('/invite/download', 'InviteController@download')->name('invite.download');
    Route::get('/invite/print', 'InviteController@print')->name('invite.print');
    Route::get('invite/{id}/resend', 'InviteController@resend')->name('invite.resend');
    Route::resource('/invite', 'InviteController');
    Route::get('/api-invite', 'InviteApi@index');

    ///////////////////////////////////////////////////////////////////////////////
    // Change Password Routes
    ///////////////////////////////////////////////////////////////////////////////
    Route::get('/change-password', 'ChangePasswordController@changePassword')->name('change_password');
    Route::post('/update-password', 'ChangePasswordController@updatePassword');

    Route::get('/api-user', 'UserApi@index');
    Route::get('/api-user/role-options', 'UserApi@getRoleOptions');
    Route::get('/api-user/options', 'UserApi@getOptions');
    Route::get('/user/download', 'UserController@download')->name('user.download');
    Route::get('/user/print', 'UserController@print')->name('user.print');
    Route::resource('/user', 'UserController');

Route::get('/api-role', 'RoleApi@index');
Route::get('/api-role/options', 'RoleApi@getOptions');
Route::get('/role/download', 'RoleController@download')->name('role.download');
Route::get('/role/print', 'RoleController@print')->name('role.print');
Route::resource('/role', 'RoleController');



    ///////////////////////////////////////////////////////////////////////////////
    // Application Routes
    ///////////////////////////////////////////////////////////////////////////////

Route::get('/api-organization', 'OrganizationApi@index');
Route::get('/api-organization/options', 'OrganizationApi@getOptions');
Route::get('/organization/download', 'OrganizationController@download')->name('organization.download');
Route::get('/organization/print', 'OrganizationController@print')->name('organization.print');
Route::resource('/organization', 'OrganizationController');

    Route::get('/api-self-report', 'SelfReportApi@index');
    Route::get('/api-self-report/options', 'SelfReportApi@getOptions');
    Route::get('/self-report/download', 'SelfReportController@download')->name('self-report.download');
    Route::get('/self-report/print', 'SelfReportController@print')->name('self-report.print');
    Route::resource('/self-report', 'SelfReportController');
});
