<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/users/login', 'UserLoginController@login');
Route::post('/users/register', 'UserRegistrationController@register');
Route::post('/users/update', 'UserRegistrationController@update');
Route::get('/users/admin', 'UserRegistrationController@getAllUsers');

//Events
Route::post('/users/events', 'CustomerEventsController@createEvent');
Route::get('/users/events/admin', 'CustomerEventsController@getAllEventsList');
Route::get('/users/events', 'CustomerEventsController@getEventsList');
Route::get('/users/event', 'CustomerEventsController@showEvent');
Route::post('/users/register/fcm-device-token', 'FCMTokenController@updateDeviceToken');
Route::get('/users/register/send-message/{message}', 'FCMTokenController@sendMessage');
Route::get('/users/events/broadcast', 'CustomerEventsController@eventsBroadcast');
Route::get('/users/events/delete', 'CustomerEventsController@delete');
Route::post('/users/events/update', 'CustomerEventsController@updateEvent');
Route::post('/users/events/comment', 'CustomerEventsController@saveComment');

//Illnesses and Allergies
Route::get('/users/my-health/illnesses', 'CustomerIllnessesControllerOrig@getIllnesses');
Route::post('/users/my-health/illnesses/create', 'CustomerIllnessesControllerOrig@createIllness');
Route::post('/users/my-health/illnesses/update', 'CustomerIllnessesControllerOrig@update');
Route::get('/users/my-health/illnesses/show', 'CustomerIllnessesControllerOrig@show');
Route::get('/users/my-health/illnesses/delete', 'CustomerIllnessesControllerOrig@delete');

//Medication
Route::post('/users/my-health/illnesses/medication', 'CustomerMedicationsController@save');
Route::post('/users/my-health/illnesses/medication/edit', 'CustomerMedicationsController@update');
//Route::post('/users/my-health/illnesses/medication/reminder', 'CustomerMedicationsController@reminder');
Route::get('/users/my-health/illnesses/medication/delete', 'CustomerMedicationsController@delete');

//Alarms
Route::post('/users/my-health/illnesses/medication/alarm-entries',
    'CustomerAlarmEntriesController@createAlarmEntry');
Route::get('/users/my-health/illnesses/medication/alarm-entries',
    'CustomerAlarmEntriesController@getCustomerAlarmEntries');
Route::get('/users/my-health/illnesses/medication/customer-medication-alarm-entries',
    'CustomerAlarmEntriesController@getCustomerMedicationAlarmEntry');
Route::post('/users/my-health/illnesses/medication/customer-medication-alarm-entries/update',
    'CustomerAlarmEntriesController@updateAlarmEntry');

//Service Request
Route::post('/users/my-health/service-request', 'CustomerServiceRequestController@create');
Route::get('/users/my-health/service-request', 'CustomerServiceRequestController@getServices');
Route::get('/users/my-health/service-request/admin', 'CustomerServiceRequestController@getServicesAdmin');
Route::get('/users/my-health/service-customer', 'CustomerServiceRequestController@getServiceCustomer');

//Medications
Route::get('/medicines', 'MedicationsController@getMedicationList');

//Email controllers
Route::get('/send-mail', 'SendServiceEmailController@sendEmail');


//Page data
Route::get('/users/my-health/page-data', 'PageDataController@getHospitals');

/*------------------------------------------------------Admin routes---------------------------------------------------*/
//Admin Health facilities
Route::get('/users/my-health/health-facilities', 'HealthFacilitiesController@getHealthFacilities');

//Admin Illness data
Route::get('/users/my-health/illnesses', 'IllnessDataController@getIllnessData');

//Admin allergy data
Route::get('/users/my-health/allergies', 'AllergyDataController@getAllergiesData');

//Admin
Route::post('/admin/login', 'AdminLoginController@login');

//Customer profile
Route::get('/users/{userId}customer-profile', 'CustomerProfileController@getCustomerProfile');

//Customer allergy
Route::get('/users/{userId}/customer-allergies', 'CustomerAllergiesController@getCustomerAllergies');

//Show customer allergy
Route::get('/users/{userId}/customer-allergies/{id}', 'CustomerAllergiesController@showCustomerAllergy');

//Create customer allergy
Route::post('/users/{userId}/customer-allergies/', 'CustomerAllergiesController@saveCustomerAllergy');

//Update customer allergy
Route::post('/users/{userId}customer-allergies/{id}/update', 'CustomerAllergiesController@updateCustomerAllergy');

//Delete customer allergy
Route::get('/users/{userId}customer-allergies/{id}/delete', 'CustomerAllergiesController@deleteCustomerAllergy');


Route::get('date', function () {

    ///$startDate = time();
    $startDate = strtotime('2018-07-17 20:20:00');
    return date('Y-m-d H:i:s', strtotime('+1 day', $startDate));

});

Route::get('/test-logging', function(){
	Log::debug('Logging test message.');
});
