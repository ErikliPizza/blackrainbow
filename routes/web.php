<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArtController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\HomeController;
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

/*
Route::get('generate', function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});*/

// Responses
Route::get('/access-denied', function () {
    return Inertia::render('Response/403');
})->name('access-denied');

Route::get('/test', function () {
    return Inertia::render('test');
});

Route::get('/', [HomeController::class, 'index'])->name('home');


// ArtController
Route::middleware(['auth', 'verified', 'role:artist'])->group(function () {
    Route::get('/myArts', [ArtController::class, 'index'])->name('arts');
    Route::get('/myArts/create', [ArtController::class, 'create'])->name('arts.create');
    Route::post('/myArts', [ArtController::class, 'store'])->name('arts.store');
    Route::delete('/arts/{art}', [ArtController::class, 'destroy']);

});
Route::get('/arts/{id}', [ArtController::class, 'show'])->name('arts.show');
Route::get('/likedArts', [ArtController::class, 'likedIndex'])->name('arts.likedIndex')->middleware(['auth', 'verified']);

// ArtController

// CommentController
Route::post('/comments', [CommentController::class, 'store'])->middleware(['auth', 'verified']);
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware(['auth', 'verified']);
// CommentController

// LikeController
Route::post('/arts/{art}/like', [LikeController::class, 'like'])->name('arts.like')->middleware(['auth', 'verified']);
Route::get('/arts/{art}/likes', [LikeController::class, 'likes'])->name('arts.likes');
// LikeController

// Profile
Route::get('/user/{user}', [ProfileController::class, 'index'])->name('profile.show');
Route::post('/user/{user}/follow', [FollowController::class, 'toggleFollow'])
    ->name('follow')->middleware(['auth', 'verified']);
Route::get('/user/{user}/followings', [FollowController::class, 'followings']);
Route::get('/user/{user}/followers', [FollowController::class, 'followers']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/login/google', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'handleGoogleCallback']);


require __DIR__.'/auth.php';
