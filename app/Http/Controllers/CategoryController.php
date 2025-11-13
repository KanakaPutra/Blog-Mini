<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $articles = $category->articles()->with(['user', 'comments'])->latest()->get();

        return view('categories.show', compact('category', 'articles'));
    }
}
