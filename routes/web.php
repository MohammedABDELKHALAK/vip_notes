<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
                      
});

Route::resource('notes', NoteController::class )->except(['show']); 
Route::get('/notes/{id}/{token}', [NoteController::class, 'show'])->name('notes.show');

Route::post('/notes/{id}/regenerate-token', [NoteController::class, 'regenerateToken'] )->name('notes.regenerateToken'); 
Route::post('/notes/{id}/toggle-open-status', [NoteController::class, 'toggleOpenStatus'] )->name('notes.toggleOpenStatus'); 

Route::get('/expired-notes', [App\Http\Controllers\NoteController::class, 'ExpiredNotes'])->name('expired.notes');
Route::get('/recieved-notes', [App\Http\Controllers\NoteController::class, 'RecievedNotes'])->name('recieved.notes');
Route::post('/send-note/{id}', [App\Http\Controllers\NoteController::class, 'sendNote'])->name('send.notes');
Route::get('/notifications/read/{id}', [NoteController::class, 'markAsRead'])->name('notifications.markAsRead');



