<?php

use App\Http\Controllers\admin\TestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminController;
use App\Mail\SendMessage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/send', [TestController::class, 'send'])->name('send');

// Route::post('/send-email', [TestController::class, 'sendMail'])->name('send.email');
Route::get('/send-email', [TestController::class, 'sendMail'])->name('send.email');



Route::get('/test', [TestController::class , 'index'])->name('test');
Route::get('/dashboard', [AdminController::class , 'index'])->name('dashboard');
Route::post('/send-selected-users', [AdminController::class , 'sendSelectedUsers'])->name('sendSelectedUsers');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
