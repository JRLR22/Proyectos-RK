<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    // GET /api/categories - Listar todas las categorías
    public function index()
    {
        $categories = Cache::remember('all-categories', 7200, function () {
            // 7200 segundos = 2 horas
            return Category::with('children')
                ->withCount('books')
                ->whereNull('parent_category_id')
                ->get();
        });

        return response()->json($categories);
    }

    public function show($id)
    {
        // Caché individual por categoría
        $category = Cache::remember("category-{$id}", 3600, function () use ($id) {
            return Category::with(['books.authors', 'children'])
                ->withCount('books')
                ->findOrFail($id);
        });

        return response()->json($category);
    }
}