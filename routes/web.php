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

use App\Events\ChatEvent;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {

  //broadcast(new ChatEvent('some date'));
    return view('welcome');
});

Auth::routes();




Route::get('/login/doctor', 'Auth\LoginController@showDoctorLoginForm');
Route::get('/register/doctor', 'Auth\RegisterController@showDoctorRegisterForm');

Route::post('/login/doctor', 'Auth\LoginController@doctorLogin');
Route::post('/register/doctor', 'Auth\RegisterController@createDoctor');

Route::view('/home', 'home')->middleware('auth');
Route::view('/doctor', 'doctor')->middleware('doctorauth');

Route::middleware(['auth'])->group(function(){
  
  Route::get('users/profile','UserController@edit')->name('users.edit-profile');
    
  Route::put('Update/Users','UserController@update')->name('users.update-profile');
  Route::get('request/admin','PatientRequestController@create')->name('patients.create');
  Route::post('request/store','PatientRequestController@store')->name('patients.store');
  Route::get('view/request','AdminRequestController@viewRequest')->name('patient-request.view');
  Route::get('patient/{patientRequest}/change-status','PatientRequestController@changeStatus')->name('patient.change-status');
  Route::get('create/doctorrequest','DoctorsRequestController@createDoctorRequest')->name('create-doctor.request');
  Route::post('send/doctorrequest','DoctorsRequestController@storeDoctorRequest')->name('doctorsrequest.store');
});


Route::middleware(['adminordoctor'])->group(function(){
  Route::get('users','UserController@index')->name('users.index');
  Route::resource('types','TypeController');

});

Route::middleware(['allroleauth'])->group(function(){
  Route::resource('doctors','DoctorController');
  Route::get('view/adminrequest','DoctorController@viewAdminRequest')->name('view-admin.request');
});

//chat
Route::get('chat','ChatController@chat');
Route::post('send','ChatController@send');
Route::post('saveToSession','ChatController@saveToSession');
Route::post('deleteSession','ChatController@deleteSession');
Route::post('getOldMessage','ChatController@getOldMessage');
Route::get('check',function(){
	return session('chat');
});
