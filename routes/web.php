<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Models\Article;

// 🔹 Halaman utama menampilkan artikel (tanpa login)
Route::get('/', function () {
    $articles = Article::with(['category', 'user', 'comments'])->latest()->get();
    return view('articles.index', compact('articles'));
})->name('home');

// 🔹 Route publik: semua orang bisa lihat daftar artikel
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// 🔹 Dashboard (khusus user login)
Route::get('/dashboard', function () {
    $articles = Article::with(['category', 'user', 'comments'])->latest()->get();
    return view('dashboard', compact('articles'));
})->middleware(['auth', 'verified'])->name('dashboard');


// ✅ Hanya Admin Boleh Tambah/Edit/Hapus Artikel
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

// 📝 User login boleh memberi komentar
Route::middleware(['auth'])->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    // Profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 🔹 Route publik untuk membaca artikel (harus DITARUH PALING BAWAH)
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');


// ✅ Route untuk kategori (buat dropdown “Index” di navbar)
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');


require __DIR__.'/auth.php';
