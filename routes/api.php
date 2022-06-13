<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([ 'prefix' => 'auth'], function (){
    Route::get('service/{id}','API\ServiceController@getServiceDetails');
    Route::group(['middleware' => ['api','localization']], function () {
        Route::post('store_guest_device', 'API\AuthController@storeGuestDevice');
        Route::post('register', 'API\UsersController@signup')->name('api.register');
        Route::post('login', 'API\AuthController@login')->name('api.login');

        Route::post('password/send_otp', 'API\PasswordController@sendPasswordResetCode');
        Route::post('password/verify_password_otp', 'API\PasswordController@verifyPasswordCode');

        Route::get('settings','API\HomeController@settings');
        Route::post('contactus','API\HomeController@storeContactUs');
        Route::get('faqs','API\HomeController@faqs');
        Route::get('page/{slug}','API\HomeController@getPage');
        Route::get('banners','API\HomeController@getBanners');
        Route::get('areas', 'API\HomeController@getAreas');

        Route::get('car_makes', 'API\CarsController@getCarMakes');
        Route::post('car_models', 'API\CarsController@getCarModels');

        Route::get('services', 'API\ServiceController@getServices');

        Route::post('track_request', 'API\RequestController@getTrackRequest');
        Route::get('callback_success', 'API\RequestController@success');
        Route::get('callback_fail', 'API\RequestController@fail');
        Route::post('place_request', 'API\RequestController@placeRequest');
        Route::post('add_message', 'API\RequestController@addMessage');
        Route::get('messages/{id}','API\RequestController@getMessages');
        Route::get('messages_admin/{id}','API\RequestController@messages_admin');
        
        Route::post('availabel_times', 'API\ServiceController@getAvailabelTimes');
        Route::post('resend_code', 'API\AuthController@resendCode')->name('api.resendCode');

    });
        Route::group(['middleware' => 'auth:api'], function() {
        Route::post('activate_account', 'API\AuthController@activateAccount')->name('api.activResendCode');
        Route::post('change_online_status', 'API\AuthController@change_online_status')->name('api.change_online_status');

        Route::post('password/changepassword', 'API\PasswordController@changePassword');

        Route::get('profile', 'API\UsersController@getProfil');
        Route::put('update_profile_account', 'API\UsersController@update_profile_account');
        Route::put('update_profile_phone', 'API\UsersController@update_profile_phone');
        
        Route::get('notifications', 'API\UsersController@notifications');
        Route::get('read_notification/{id}', 'API\UsersController@readNotification');
        Route::get('notification_setting', 'API\UsersController@notificationSettings');
        Route::post('updatenotification_setting', 'API\UsersController@updateNotificationSettings');

        Route::get('cars', 'API\UsersController@getCars');
        Route::get('car_details/{id}', 'API\UsersController@carDetails');
        Route::post('save_car', 'API\UsersController@saveCar');
        Route::post('delete_car/{id}', 'API\UsersController@deleteCar');

        Route::get('addresse', 'API\UsersController@getAddresses');
        Route::get('addresse_details/{id}', 'API\UsersController@addressDetails');
        Route::post('save_addresse', 'API\UsersController@saveAddress');
        Route::post('delete_addresse/{id}', 'API\UsersController@deleteAddress');

        Route::post('wallet', 'API\RequestController@wallet');
        Route::get('request/{id}','API\RequestController@getRequestDetails');
        Route::get('latest_request','API\RequestController@latestRequestDetails');
        Route::get('requests/{type}','API\RequestController@getRequestsType');
        Route::post('re_shedule_request/{id}','API\RequestController@reSheduleRequest');
        Route::post('cancel_request/{id}','API\RequestController@cancelRequest');
        Route::post('terminate_consultation/{id}','API\RequestController@terminateConsultation');
        Route::post('dispute_request/{id}','API\RequestController@disputeRequest');
        Route::get('drop_request/{id}','API\RequestController@dropRequest');

    });
});


Route::group([ 'prefix' => 'auth/lawyer'], function (){
    Route::get('service/{id}','API\ServiceController@getServiceDetails');
    Route::group(['middleware' => ['api','localization']], function () {
        Route::post('store_guest_device', 'API\AuthController@storeGuestDevice');
        Route::post('register', 'API\UsersController@signup_lawyer')->name('api.register');
        Route::post('login', 'API\AuthController@login')->name('api.login');
        Route::post('password/send_otp', 'API\PasswordController@sendPasswordResetCode_lawyer');
        Route::post('password/verify_password_otp', 'API\PasswordController@verifyPasswordCode_lawyer');
        Route::get('settings','API\HomeController@settings');
        Route::post('contactus','API\HomeController@storeContactUs');
        Route::get('faqs','API\HomeController@faqs');
        Route::get('page/{slug}','API\HomeController@getPage');
        Route::get('banners','API\HomeController@getBanners');
        Route::get('specialties', 'API\HomeController@getSpecialty');
        Route::post('getSpecialty_lawyers', 'API\HomeController@getSpecialty_lawyers');
        
        Route::get('specialties_update', 'API\HomeController@getSpecialty_update');
        
        Route::get('mini_specialties', 'API\HomeController@getSpecialty_mini');
        Route::post('search', 'API\HomeController@search');
        Route::get('lawyers', 'API\HomeController@getLawyers');
        Route::get('subjects', 'API\HomeController@getSubjects');
        Route::get('get_status', 'API\HomeController@get_status');
        Route::post('add_message', 'API\RequestController@addMessage');

        Route::get('car_makes', 'API\CarsController@getCarMakes');
        Route::post('lawyer', 'API\HomeController@getLawyer');
        Route::post('get_total', 'API\HomeController@get_total');
        Route::get('services', 'API\ServiceController@getServices');
        Route::post('track_request', 'API\RequestController@getTrackRequest');
        Route::post('place_request', 'API\RequestController@placeRequest');
        Route::post('availabel_times', 'API\ServiceController@getAvailabelTimes');
        Route::post('resend_code', 'API\AuthController@resendCodelawyer')->name('api.resendCodelawyer');
        
    });

        Route::group(['middleware' => 'auth:lawyer-api'], function() {

            Route::post('activate_account', 'API\AuthController@activateAccount_lawyer')->name('api.activResendCode');
            Route::post('change_online_status', 'API\AuthController@change_online_status')->name('api.change_online_status');
            Route::post('password/changepassword', 'API\PasswordController@changePassword_lawyer');
            
            Route::post('update_deactive', 'API\UsersController@update_deactive');
            Route::post('update_profile', 'API\UsersController@update_profile_lawyer');
            Route::put('update_profile_account', 'API\UsersController@update_profile_account_lawyer');
            Route::put('update_profile_phone', 'API\UsersController@update_profile_phone_lawyer');
            Route::get('profile', 'API\UsersController@getProfil_lawyer');

            Route::get('notifications', 'API\UsersController@notifications');
            Route::get('read_notification/{id}', 'API\UsersController@readNotification');
            Route::get('notification_setting', 'API\UsersController@notificationSettings');
            Route::post('updatenotification_setting', 'API\UsersController@updateNotificationSettings');

            Route::get('cars', 'API\UsersController@getCars');
            Route::get('car_details/{id}', 'API\UsersController@carDetails');
            Route::post('save_car', 'API\UsersController@saveCar');
            Route::post('delete_car/{id}', 'API\UsersController@deleteCar');

            Route::get('addresse', 'API\UsersController@getAddresses');
            Route::get('addresse_details/{id}', 'API\UsersController@addressDetails');
            Route::post('save_addresse', 'API\UsersController@saveAddress');
            Route::post('delete_addresse/{id}', 'API\UsersController@deleteAddress');
            Route::post('report', 'API\RequestController@report');
            Route::post('week_sales', 'API\RequestController@week_sales');
            Route::get('request/{id}','API\RequestController@getRequestDetails');
            Route::get('requests/{type}','API\RequestController@getRequestsType_lawyer');
            Route::post('re_shedule_request/{id}','API\RequestController@reSheduleRequest');
            Route::post('cancel_request/{id}','API\RequestController@cancelRequest');
            Route::post('dispute_request/{id}','API\RequestController@disputeRequest');
            Route::get('drop_request/{id}','API\RequestController@dropRequest');

    });
});