<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    // GET /api/categories - Listar todas las categorías
    public function index()
    {
        $categories = Category::with('children')
            ->withCount('books') // 👈 AGREGADO
            ->whereNull('parent_category_id')
            ->get();

        return response()->json($categories);
    }

    // GET /api/categories/{id} - Detalle de categoría con sus libros
    public function show($id)
    {
        $category = Category::with(['books.authors', 'children'])
            ->withCount('books') // 👈 AGREGADO
            ->findOrFail($id);

        return response()->json($category);
    }
}