<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();

        return view('admin.categories.index', compact('categories'));
    }

    public function show($slug) {
        $category = Category::where('slug', '=', $slug)->first();
        $posts = $category->posts;

        if(!$category) {
            abort('404');
        }

        return view('admin.categories.show', compact('category', 'posts'));
    }
}
