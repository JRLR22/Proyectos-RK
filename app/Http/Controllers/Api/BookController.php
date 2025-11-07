<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = DB::table('books')
            ->join('categories', 'books.category_id', '=', 'categories.category_id')
            ->leftJoin('book_authors', 'books.book_id', '=', 'book_authors.book_id')
            ->leftJoin('authors', 'book_authors.author_id', '=', 'authors.author_id')
            ->select(
                'books.*',
                'categories.name as category_name',
                DB::raw("STRING_AGG(CONCAT(authors.first_name, ' ', authors.last_name), ', ') as authors")
            )
            ->groupBy('books.book_id', 'categories.name')
            ->get();

        return response()->json($books);
    }
}

