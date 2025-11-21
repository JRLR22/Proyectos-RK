<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; // Descomenta cuando tengas el modelo

class BookController extends Controller
{
    /**
     * Muestra el listado de libros
     */
    public function index()
    {
        // $books = Book::paginate(20);
        // return view('books.index', compact('books'))
        // 
    $books = Book::with('author')->get(); // Trae los libros con su autor
    return response()->json($books);
    }

    /**
     * Muestra un libro específico
     */
    public function show($id)
    {
        // $book = Book::findOrFail($id);
        // return view('books.show', compact('book'));
        
        return view('books.show');
    }

    /**
     * Muestra la página de búsqueda avanzada
     */
    public function busquedaAvanzada()
    {
        return view('books.busqueda-avanzada');
    }

    /**
     * Procesa la búsqueda de libros
     */
    public function buscar(Request $request)
    {
        $query = $request->input('query');
        $autor = $request->input('autor');
        $isbn = $request->input('isbn');
        
        // Aquí implementarías la lógica de búsqueda
        // $books = Book::where('titulo', 'LIKE', "%{$query}%")
        //     ->when($autor, function($q) use ($autor) {
        //         return $q->where('autor', 'LIKE', "%{$autor}%");
        //     })
        //     ->when($isbn, function($q) use ($isbn) {
        //         return $q->where('isbn', $isbn);
        //     })
        //     ->paginate(20);
        
        // return view('books.index', compact('books', 'query'));
        
        return redirect()->route('libros.index');
    }
}