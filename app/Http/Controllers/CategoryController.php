<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $articles = $category->articles()->with(['user', 'comments'])->latest()->get();

        return view('categories.show', compact('category', 'articles'));
    }
}
