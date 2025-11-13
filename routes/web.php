<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Http;
use App\Models\Article;

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

// 🔹 Halaman welcome (alias)
Route::get('/welcome', function () {
    return redirect()->route('home');
})->name('welcome');

// 🔹 Daftar artikel (publik)
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// 🔹 Route khusus admin (CRUD artikelnya sendiri)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

// 🔹 Route publik baca artikel tunggal
Route::get('/articles/{article}', [ArticleController::class, 'show'])
    ->whereNumber('article') // Pastikan hanya ID numerik
    ->name('articles.show');

// 🔹 Dashboard (khusus user login)
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

// 📝 Route untuk komentar & profil (user login)
Route::middleware(['auth'])->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔹 Route untuk kategori (dropdown navbar)
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');

require __DIR__ . '/auth.php';
