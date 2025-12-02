<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;



// Route::domain(config('app.api_domain'))->group(function () {
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/users/me', [UserController::class, 'me']);

    Route::get('/users/{username}', [UserController::class, 'show']);

    Route::get('/users/feed/{page}', [UserController::class, 'feed']);

    Route::post('/users/update', [UserController::class, 'update']);

    Route::delete('/users/{username}', [UserController::class, 'destroy']);

    // Route::post('/users/{username}/restore-request', [RestoreRequestController::class, 'store']);


    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

    Route::post('/posts/{id}', [PostController::class, 'update'])->name('posts.update');

    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/posts/{id}/like', [PostController::class, 'toggleLike']);

    Route::post('/posts/{id}/comment', [PostController::class, 'addComment']);

    Route::post('/comments/{id}/reply', [PostController::class, 'reply']);

    Route::get('/explore', [UserController::class, 'explore'])->name('explore');

    Route::get('/search-users', [UserController::class, 'search'])->name('search.users');

    Route::post(
        '/report/post/{postId}',
        [ReportController::class, 'storePostReport']
    );

    // Toggle follow/unfollow
    Route::post('follow/toggle', [FollowController::class, 'toggle']);

    // Followers list
    Route::get('follow/{id}/followers', [FollowController::class, 'followers']);

    // Following list
    Route::get('follow/{id}/following', [FollowController::class, 'following']);


    // routes/api.php
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/getNotifications/{id}', [NotificationController::class, 'getNotification']);


});



// });