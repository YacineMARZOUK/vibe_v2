<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/hhh', function () {
    return view('friends.index');
});
Route::post('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::get('/test-db', [PostController::class, 'testDb']);
require __DIR__.'/auth.php';
Route::get('/posts', [PostController::class, 'index'])->name('posts.index')->middleware('auth');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// Friend routes
Route::middleware('auth')->group(function () {
    Route::get('/users', [FriendController::class, 'showUsers'])->name('users.index');
    Route::post('/friend-request/{user}', [FriendController::class, 'sendFriendRequest'])->name('friend.request');
    Route::get('/friends', [FriendController::class, 'listFriends'])->name('friends.index');
    Route::post('/friend-request/{requestId}/{action}', [FriendController::class, 'respondToFriendRequest'])->name('friend.respond');
});