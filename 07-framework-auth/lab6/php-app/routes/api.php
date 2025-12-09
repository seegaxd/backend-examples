<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
// Подключаем класс напрямую, чтобы Laravel не искал его по кличкам
use KeycloakGuard\Middleware\KeycloakCan;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Используем полный путь к классу ::class
Route::get('/subscribers', [SubscriberController::class, 'index'])
    ->middleware(['auth:api', KeycloakCan::class . ':SubscriberApiViewer']);

Route::post('/subscribers', [SubscriberController::class, 'store'])
    ->middleware(['auth:api', KeycloakCan::class . ':SubscriberApiWriter']);