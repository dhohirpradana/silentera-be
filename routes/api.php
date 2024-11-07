<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ElasticsearchController;
use App\Http\Controllers\SavelocController;
use App\Http\Controllers\GetlistindexController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CsvdownloadController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PlanFeatureController;
use App\Http\Controllers\Api\SubscriptionController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('plans', PlanController::class);
Route::apiResource('locations', LocationController::class);
Route::apiResource('plan-features', PlanFeatureController::class);
Route::apiResource('subscriptions', SubscriptionController::class);


Route::controller(SurveyController::class)->group(function () {
    Route::post('survey', 'store');
    Route::get('survey/csv', 'downloadcsv');
    Route::get('survey', 'indexall');
});

Route::controller(CsvdownloadController::class)->group(function () {
    Route::get('historis/bulanan/csv', 'historisbulananCsv');
    Route::get('historis/tahunan/csv', 'historistahunanCsv');
    Route::get('usercsv', 'indexadmin');
    Route::get('paymentcsv', 'indexpayment');
});

Route::controller(PaymentController::class)->group(function () {
    Route::get('payment/all', 'index');
    Route::get('payment/user', 'indexuser');
    Route::post('payment', 'store');
    Route::put('payment/{id}', 'update');
    Route::delete('payment/user/{id}', 'destroybyuser');
    Route::delete('payment/admin/{id}', 'destroybyadmin');
});

Route::controller(ElasticsearchController::class)->group(function () {
    Route::post('search', 'search');
    Route::post('search/histori/bulanan', 'index');
    Route::post('search/prakiraan', 'prakiraan');
    Route::post('month/{month}', 'windrose');
    Route::post('search/new_prakiraan', 'new_prakiraan');
});

Route::controller(GetlistindexController::class)->group(function () {
    Route::get('prakiraan', 'index_prakiraan');
    Route::get('historis-bulanan', 'index_historis_bulanan');
    Route::get('historis-tahunan', 'index_historis_tahunan');
    Route::delete('deleteindex', 'destroy');
});

Route::controller(SavelocController::class)->group(function () {
    Route::get('saveloc', 'index');
    Route::post('savelocation', 'create');
    Route::get('savelocation/{id}', 'show');
    Route::put('savelocation/{id}', 'update');
    Route::delete('savelocation/{id}', 'destroy');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('edituser', 'editUser');
    Route::post('user/upload', 'upload');
    Route::get('getimages/{filename}', 'getimages');
    Route::post('register', 'register');
    Route::post('taketour', 'taketour');
    Route::post('register_admin', 'register_admin');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('verifytoken', 'me');
    Route::get('/barangs/{id}', 'show');
    Route::get('users', 'indexadmin');
    Route::delete('delete/users/{id}', 'destroybyadmin');
});

Route::controller(TodoController::class)->group(function () {
    Route::get('todos', 'index');
    Route::post('todo', 'store');
    Route::get('todo/{id}', 'show');
    Route::put('todo/{id}', 'update');
    Route::delete('todo/{id}', 'destroy');
}); 
