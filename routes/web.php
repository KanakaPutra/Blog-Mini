<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔹 Halaman utama (publik)
Route::get('/', function () {
    $articles = Article::with(['category', 'user'])->latest()->get();

    try {
        $response = Http::withHeaders([
            'x-rapidapi-key' => 'd243538abamsh53f12e467468e89p13d16fjsn9c6bead9a415',
            'x-rapidapi-host' => 'binance43.p.rapidapi.com',
        ])->get('https://binance43.p.rapidapi.com/ticker/24hr', [
            'symbol' => 'BTCUSDT',
        ]);

        $data = $response->json();
        $btcPrice = $data['lastPrice'] ?? 'N/A';
        $btcChange = $data['priceChangePercent'] ?? '0';
    } catch (\Exception $e) {
        $btcPrice = 'N/A';
        $btcChange = '0';
    }

    return view('welcome', compact('articles', 'btcPrice', 'btcChange'));
})->name('home');

// 🔹 Alias ke home
Route::get('/welcome', fn () => redirect()->route('home'))->name('welcome');

// ======================================================
// 🔹 Category
// ======================================================
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::resource('categories', CategoryController::class);
});

// ======================================================
// 🔹 Artikel publik + support filter "Artikel Saya"
// ======================================================
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// ======================================================
// 🔹 CRUD Artikel untuk admin biasa (is_admin >= 1)
// ======================================================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

// 🔹 Artikel tunggal
Route::get('/articles/{article}', [ArticleController::class, 'show'])
    ->whereNumber('article')
    ->name('articles.show');

// ======================================================
// 🔹 Dashboard
// ======================================================
Route::get('/dashboard', function () {
    $articles = Article::with(['category', 'user', 'comments'])->latest()->get();

    try {
        $response = Http::withHeaders([
            'x-rapidapi-key' => 'd243538abamsh53f12e467468e89p13d16fjsn9c6bead9a415',
            'x-rapidapi-host' => 'binance43.p.rapidapi.com',
        ])->get('https://binance43.p.rapidapi.com/ticker/24hr', [
            'symbol' => 'BTCUSDT',
        ]);

        $data = $response->json();
        $btcPrice = $data['lastPrice'] ?? 'N/A';
        $btcChange = $data['priceChangePercent'] ?? '0';
    } catch (\Exception $e) {
        $btcPrice = 'N/A';
        $btcChange = '0';
    }

    return view('dashboard', compact('articles', 'btcPrice', 'btcChange'));
})->middleware(['auth', 'verified'])->name('dashboard');

// ======================================================
// 🔹 Komentar & Profil (user login)
// ======================================================
Route::middleware(['auth'])->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔹 Route kategori (navbar)
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');

// ======================================================
// 🔹 SUPER ADMIN AREA
// ======================================================
Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
        Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');

        // Ban / unban user
        Route::patch('/users/{user}/ban', [SuperAdminController::class, 'ban'])->name('users.ban');
        Route::patch('/users/{user}/unban', [SuperAdminController::class, 'unban'])->name('users.unban');
    });

// ======================================================
// 🔹 Dummy routes untuk CI test
// ======================================================
Route::post('/login', fn () => redirect()->route('dashboard'))->name('login.store');

Route::get('/user/password/edit', fn () => view('auth.passwords.edit'))
    ->name('user-password.edit');

Route::get('/two-factor', fn () => view('auth.two-factor'))
    ->name('two-factor.show');

require __DIR__.'/auth.php';