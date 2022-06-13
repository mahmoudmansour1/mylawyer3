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

use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\App;

//Route::get('/', function () {
//    return view('welcome');
//})->name('home');
Route::get('/','HomeController@index')->name('home');
// switch language
Route::get('/switch_lang/{locale}','HomeController@changeLang')->name('switch_lang');

Route::get('/page/{slug}', 'PagesController@index')->name('pageDetails');
Route::get('/contact-us', 'PagesController@contactus')->name('contactUs');
Route::post('/contact-request', 'PagesController@sendContactRequest')->name('sendContactUs');

Route::get('/payonline/{token}', 'PaymentController@getInfo')->name('paypage');
Route::get('/pay/{token}', 'PaymentController@payInvoice')->name('pay');
Route::get('/pay/{token}/{staus}/{payment}', 'PaymentController@payReturn')->name('paySuccess');

