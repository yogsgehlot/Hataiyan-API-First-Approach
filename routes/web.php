<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
// Route::domain(config('app.crm_domain'))->group(function () {
Route::middleware('guest')->group(function () {
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['session.auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');
    Route::get('/feed', [HomeController::class, 'index'])->name('feed');

    Route::prefix('profile')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('profile');
        Route::get('/edit', [UserController::class, 'edit'])->name('profile.edit');
        Route::post('/update', [UserController::class, 'update'])->name('profile.update');
        // Route::post('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');

        // // View other users
        Route::get('/{username}', [UserController::class, 'showUser'])->name('profile.view');
    });

    Route::prefix('post')->group(function () {
        Route::get('/create', [PostController::class, 'create'])->name('post.create');
        Route::post('/store', [PostController::class, 'store'])->name('post.store');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name(name: 'post.edit');
        Route::post('/{id}/update', [PostController::class, 'update'])->name('post.update');
        Route::get('/{id}', [PostController::class, 'show'])->name('post.show');
        Route::delete('/{id}/delete', [PostController::class, 'destroy'])->name('post.delete');
    });

    Route::get('/explore', [UserController::class, 'explore'])->name('explore.feed');
    Route::get('/explore/load-more', [UserController::class, 'loadMore'])
        ->name('explore.loadMore');

    Route::get('/search-users', [UserController::class, 'search'])->name('search');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/report/post/{postId}', [\App\Http\Controllers\ReportController::class, 'createPostReport'])
        ->name('report.post.form');

    Route::post('/report/post/{postId}', [\App\Http\Controllers\ReportController::class, 'storePostReport'])
        ->name('report.post.store');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('user.notifications');
    Route::get('/notifications/redirect/{notification}', [NotificationController::class, 'redirect'])
        ->name('notifications.redirect');


});


Route::prefix('admin')->group(function () {

    // ----------------- GUEST ROUTES -----------------
    Route::middleware('guest')->group(function () {
        Route::get('login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])
            ->name('admin.login');

        Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])
            ->name('admin.login.post');
    });

    // ----------------- AUTH ROUTES -----------------
    Route::middleware('session.admin')->group(function () {

        // Logout
        Route::post('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])
            ->name('admin.logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])
            ->name('admin.dashboard');


        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        // must come BEFORE users/{id}
        Route::get('users/trashed', [\App\Http\Controllers\Admin\UserController::class, 'trashed'])
            ->name('admin.users.trashed');

        Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])
            ->name('admin.users.index');

        Route::post('users/{id}/restore', [\App\Http\Controllers\Admin\UserController::class, 'restore'])
            ->name('admin.users.restore');

        Route::post('users/{id}/delete', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])
            ->name('admin.users.delete');

        Route::get('users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show'])
            ->name('admin.users.show');


        /*
        |--------------------------------------------------------------------------
        | POSTS
        |--------------------------------------------------------------------------
        */

        // must come BEFORE posts/{id}
        Route::get('posts/trashed', [\App\Http\Controllers\Admin\PostController::class, 'trashed'])
            ->name('admin.posts.trashed');

        Route::get('posts', [\App\Http\Controllers\Admin\PostController::class, 'index'])
            ->name('admin.posts.index');

        Route::post('posts/{id}/restore', [\App\Http\Controllers\Admin\PostController::class, 'restore'])
            ->name('admin.posts.restore');

        Route::post('posts/{id}/delete', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])
            ->name('admin.posts.delete');

        Route::get('posts/{id}', [\App\Http\Controllers\Admin\PostController::class, 'show'])
            ->name('admin.posts.show');


        /*
        |--------------------------------------------------------------------------
        | REPORTS
        |--------------------------------------------------------------------------
        */

        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('admin.reports.index');

        Route::post('reports/{id}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve'])
            ->name('admin.reports.resolve');


        /*
        |--------------------------------------------------------------------------
        | ADMINS
        |--------------------------------------------------------------------------
        */

        // no conflict because `/admins/create` comes BEFORE any dynamic `/admins/{id}`
        // Admins
        Route::get('admins', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.admins.index');
        Route::get('admins/create', [\App\Http\Controllers\Admin\AuthController::class, 'showCreateForm'])->name('admin.admins.create');
        Route::post('admins/create', [\App\Http\Controllers\Admin\AuthController::class, 'createAdminWeb'])->name('admin.admins.store');

        Route::get('admins/{id}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'edit'])->name('admin.admins.edit');
        Route::post('admins/{id}/update', [\App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admin.admins.update');
        Route::delete('admins/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('admin.admins.delete');


    });
});


// });