<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TopicsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ThreadController::class, 'index'])->name('threads.index');
Route::get('/threads/{id}', [ThreadController::class, 'show'])->name('threads.show');
Route::get('/topics/{id}', [TopicsController::class, 'show'])->name('topics.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/topics/{id}/replies', [RepliesController::class, 'store'])->name('replies.store');
});

require __DIR__.'/auth.php';
