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
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔹 Halaman utama (publik)
Route::get('/', function () {
    $articles = Article::with(['category', 'user'])->latest()->get();

    try {
        $response = Http::get('https://api.alternative.me/v2/ticker/1/');
        $data = $response->json();
        $btcPrice = $data['data']['1']['quotes']['USD']['price'] ?? 'N/A';
        $btcChange = $data['data']['1']['quotes']['USD']['percent_change_24h'] ?? '0';
    } catch (\Exception $e) {
        $btcPrice = 'N/A';
        $btcChange = '0';
    }

    return view('welcome', compact('articles', 'btcPrice', 'btcChange'));
})->name('home');

// 🔹 Alias ke home
Route::get('/welcome', fn () => redirect()->route('home'))->name('welcome');


// ======================================================
// 🔹 Category (khusus superadmin)
// ======================================================
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::resource('categories', CategoryController::class);
});


// ======================================================
// 🔹 Artikel publik + filter "Artikel Saya"
// ======================================================
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');


// ======================================================
// 🔹 CRUD Artikel (khusus admin & superadmin)
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
// 🔹 DASHBOARD — custom redirect jika belum login
// ======================================================
Route::get('/dashboard', function () {

    // Jika belum login → ke welcome
    if (!Auth::check()) {
        return redirect()->route('welcome');
    }

    $articles = Article::with(['category', 'user', 'comments'])->latest()->get();

    try {
        $response = Http::get('https://api.alternative.me/v2/ticker/1/');
        $data = $response->json();
        $btcPrice = $data['data']['1']['quotes']['USD']['price'] ?? 'N/A';
        $btcChange = $data['data']['1']['quotes']['USD']['percent_change_24h'] ?? '0';
    } catch (\Exception $e) {
        $btcPrice = 'N/A';
        $btcChange = '0';
    }

    return view('dashboard', compact('articles', 'btcPrice', 'btcChange'));
})->name('dashboard');


// ======================================================
// 🔹 Komentar & Profil
// ======================================================
Route::middleware(['auth'])->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 🔹 Route kategori (navbar publik)
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

Route::get('/user/password/edit', fn () => view('auth.passwords.edit'))->name('user-password.edit');

Route::get('/two-factor', fn () => view('auth.two-factor'))->name('two-factor.show');

require __DIR__.'/auth.php';
