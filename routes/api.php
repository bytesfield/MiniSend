<?php

use App\Http\Controllers\Api\SendMailController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get/mails', [SendMailController::class, 'index']);
Route::get('get/mail/{id}', [SendMailController::class, 'show']);
Route::get('get/recipient/mail/{recipient}', [SendMailController::class, 'showRecipientMails']);
Route::post('get/search/mail', [SendMailController::class, 'getEmailSearch']);
Route::post('delete/mail/{id}', [SendMailController::class, 'destroy']);
Route::post('send/mail', [SendMailController::class, 'store']);
