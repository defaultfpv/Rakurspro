<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\File\FileController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Page\PageController;
use App\Http\Controllers\Api\V1\Email\EmailController;
use App\Http\Controllers\TestController;



// документация
Route::get('/dist', function () {
    return view('vendor.l5-swagger.index', [
        'documentation' => 'default',
        'documentationTitle' => 'RakursPro API',
        'documentationConfig' => config('l5-swagger.documentations.default'),
        'urlsToDocs' => ['RakursPro API' => '/api/v1/docs'], // эта
        'useAbsolutePath' => config('l5-swagger.documentations.default.paths.use_absolute_path', true),
        'operationsSorter' => null,
        'configUrl' => null,
        'validatorUrl' => null,
    ]);
});
Route::get('/docs', function () {
    $path = storage_path('api-docs/api-docs.json');
    if (!file_exists($path)) abort(404, 'API documentation not generated. Run php artisan l5-swagger:generate');
    return response()->file($path, ['Content-Type' => 'application/json', 'Content-Disposition' => 'inline']);
});


// Авторизация
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth_token')->post('logout', [AuthController::class, 'logout']);
});

// Страницы
Route::prefix('pages')->group(function () {
    Route::get('', [PageController::class, 'getList']);
    Route::get('{page_id}', [PageController::class, 'get']);
    Route::middleware('auth_token')->patch('{page_id}', [PageController::class, 'edit']);
});


// Файлы
Route::prefix('files')->group(function () {
    Route::middleware('auth_token')->post('', [FileController::class, 'add']);
});


// Пользователи
Route::prefix('users')->group(function () {
    Route::middleware('auth_token')->get('me', [UserController::class, 'me']);
});


// Почта
Route::prefix('email')->group(function () {
    Route::post('send', [EmailController::class, 'send']);
});

// Тест
Route::any('', [TestController::class, 'handle']);