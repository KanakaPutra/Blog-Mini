<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
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
