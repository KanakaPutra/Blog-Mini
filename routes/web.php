<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\CommentReportController;
use App\Http\Controllers\NotificationController;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGE (WELCOME + HOME)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $articles = Article::with(['category', 'user'])
        ->published()
        ->where('suspended', false)
        ->latest()
        ->get();

    $tickers = [];
    try {
        $response = Http::get('https://api.alternative.me/v2/ticker/?limit=10');
        $data = $response->json();

        if (isset($data['data'])) {
            foreach ($data['data'] as $item) {
                $tickers[] = [
                    'symbol' => $item['symbol'] . '/USD',
                    'price' => $item['quotes']['USD']['price'],
                    'change' => $item['quotes']['USD']['percent_change_24h'],
                ];
            }
        }
    } catch (\Exception $e) {
        // Fallback or empty list
    }

    return view('welcome', compact('articles', 'tickers'));
})->name('home');

Route::get('/welcome', fn() => redirect()->route('home'))->name('welcome');


/*
|--------------------------------------------------------------------------
| CATEGORY PUBLIC (UNTUK NAVBAR)
|--------------------------------------------------------------------------
*/
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');


/*
|--------------------------------------------------------------------------
| CATEGORY MANAGEMENT (SUPER ADMIN ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::resource('categories', CategoryController::class)->except('show');
});


/*
|--------------------------------------------------------------------------
| ARTICLES PUBLIC PAGE
|--------------------------------------------------------------------------
*/
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');


/*
|--------------------------------------------------------------------------
| ARTICLES CRUD (ADMIN & SUPERADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

// Single article
Route::get('/articles/{article}', [ArticleController::class, 'show'])
    ->whereNumber('article')
    ->name('articles.show');


/*
|--------------------------------------------------------------------------
| DASHBOARD (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {

    if (!Auth::check()) {
        return redirect()->route('welcome');
    }

    $articles = Article::with(['category', 'user', 'comments'])
        ->published()
        ->where('suspended', false)
        ->latest()
        ->get();

    $tickers = [];
    try {
        $response = Http::get('https://api.alternative.me/v2/ticker/?limit=10');
        $data = $response->json();

        if (isset($data['data'])) {
            foreach ($data['data'] as $item) {
                $tickers[] = [
                    'symbol' => $item['symbol'] . '/USD',
                    'price' => $item['quotes']['USD']['price'],
                    'change' => $item['quotes']['USD']['percent_change_24h'],
                ];
            }
        }
    } catch (\Exception $e) {
        // Fallback or empty list
    }

    return view('dashboard', compact('articles', 'tickers'));
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| COMMENTS + PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // support komentar + reply (parent_id)
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Comment Like & Report
    Route::post('/comments/{comment}/like', [CommentLikeController::class, 'toggle'])->name('comments.like');
    Route::post('/comments/{comment}/report', [CommentReportController::class, 'store'])->name('comments.report');
    Route::post('/comments/{comment}/pin', [CommentController::class, 'togglePin'])->name('comments.pin');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| ARTICLE LIKE / DISLIKE / REPORT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/articles/{id}/like', [ArticleController::class, 'like'])->name('articles.like');
    Route::post('/articles/{id}/dislike', [ArticleController::class, 'dislike'])->name('articles.dislike');
    Route::post('/articles/{id}/report', [ArticleController::class, 'report'])->name('articles.report');

    // History Like
    Route::get('/history-like', [ArticleController::class, 'history'])->name('history.like');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('/notifications/{id}/redirect', [NotificationController::class, 'readAndRedirect'])->name('notifications.redirect');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/articles/{article}/bookmark', [BookmarkController::class, 'toggle'])->name('articles.bookmark');
});


/*
|--------------------------------------------------------------------------
| SUPER ADMIN PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
        Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');

        Route::patch('/users/{user}/ban', [SuperAdminController::class, 'ban'])->name('users.ban');
        Route::patch('/users/{user}/unban', [SuperAdminController::class, 'unban'])->name('users.unban');

        Route::patch('/articles/{article}/suspend', [SuperAdminController::class, 'suspend'])->name('articles.suspend');
        Route::patch('/articles/{article}/unsuspend', [SuperAdminController::class, 'unsuspend'])->name('articles.unsuspend');
    });


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
