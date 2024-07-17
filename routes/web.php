<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/dashboard', [QuizController::class, 'index'])->middleware('auth')->name('home');
Route::get('/quiz', [QuizController::class, 'index'])->middleware('auth');
Route::get('/dashboard', [QuizController::class, 'categories'])->middleware('auth')->name('categories');
Route::get('/quiz/{category}', [QuizController::class, 'quiz'])->name('quiz');
Route::post('/submit', [QuizController::class, 'submit'])->name('submit');
