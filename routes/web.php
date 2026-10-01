<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use App\Models\Tag;
use App\Models\Product;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MetalPriceController;



// This maps the URL "://yourdomain.com" to your controller function
Route::get('/metals', [MetalPriceController::class, 'index']);
Route::get('/api/metal-prices', [MetalPriceController::class, 'getJsonPrices']);


Route::get('/', [MainController::class,"index"])->name('Main');
Route::get('/main', [MainController::class,"index"])->name('main.index');

Route::post('/message', [MainController::class, "message"])->name('main.message');
Route::post('/order', [MainController::class, "order"])->name('main.order');




Route::middleware('auth')->group(function () {

Route::resource('manage', ManageController::class);

});





// عرض صفحة تسجيل الدخول
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// تنفيذ تسجيل الدخول
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// تسجيل الخروج
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
























