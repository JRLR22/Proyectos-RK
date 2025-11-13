<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Dashboard con estadísticas generales
    public function dashboard()
    {
        try {
            $stats = [
                'total_books' => Book::count(),
                'total_users' => User::where('is_admin', false)->count(),
                'total_orders' => Order::count(),
                'recent_books' => Book::latest()->take(5)->get(),
            ];

            return view('admin.dashboard', compact('stats'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
        }
    }

    // Obtener todos los libros (Vista)
    public function getBooks()
    {
        try {
            $books = Book::with('category')->paginate(10);
            return view('admin.books.index', compact('books'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al obtener libros: ' . $e->getMessage());
        }
    }

    // Formulario para crear nuevo libro
    public function createBookForm()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    // Crear nuevo libro
    public function createBook(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'isbn' => 'required|string|max:20',
        'gonvill_code' => 'nullable|string|max:255',
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'author_name' => 'required|string|max:255',
        'publisher' => 'nullable|string|max:255',
        'publication_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        'edition' => 'nullable|string|max:50',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'pages' => 'nullable|integer|min:1',
        'stock_quantity' => 'required|integer|min:0',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'category_id' => 'required|exists:categories,category_id',
    ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except(['cover_image', 'author_name']);   
            
            //Si hay stock > 0, status = "En stock", si no = "Agotado"
            $data['status'] = $request->stock_quantity > 0 ? 'En stock' : 'No en stock';
            // Valores por defecto
            $data['type'] = 'Papel';
            $data['language'] = 'Español';

            // Manejar la imagen si existe
        if ($request->hasFile('cover_image')) {
            $imageName = time() . '_' . $request->file('cover_image')->getClientOriginalName();
            $request->file('cover_image')->move(public_path('img/covers'), $imageName);
            $data['cover_image'] = 'covers/' . $imageName;
        }
        // Crear el libro
            $book = Book::create($data);

        // Buscar o crear el autor
            $authorNames = explode(' ', $request->author_name, 2);
            $firstName = $authorNames[0];
            $lastName = $authorNames[1] ?? '';

            $author = Author::firstOrCreate(
                ['first_name' => $firstName, 'last_name' => $lastName],
                ['first_name' => $firstName, 'last_name' => $lastName]
            );

        // Asociar el autor al libro
        $book->authors()->attach($author->author_id);


            return redirect()
                ->route('admin.books.index')
                ->with('success', 'Libro creado exitosamente');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al crear libro: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Formulario para editar libro
    public function editBookForm($id)
{
    $book = Book::with('authors')->findOrFail($id);
    $categories = Category::all();
    
    return view('admin.books.edit', compact('book', 'categories'));
}

    // Actualizar libro
    public function updateBook(Request $request, $id)
    {
    $book = Book::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'isbn' => 'required|string|max:20',
        'gonvill_code' => 'nullable|string|max:255',
        'title' => 'required|string|max:255',
        'author_name' => 'required|string|max:255',
        'publisher' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'category_id' => 'required|exists:categories,category_id',
    ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
        $data = $request->except(['cover_image', 'author_name']);
        $data['status'] = $request->stock_quantity > 0 ? 'En stock' : 'No en stock';

            // Manejar la nueva imagen si existe
            if ($request->hasFile('cover_image')) {
                // Eliminar imagen anterior si existe
                if ($book->cover_image && file_exists(public_path('img/' . $book->cover_image))) {
                    unlink(public_path('img/' . $book->cover_image));
                }
                
                $imageName = time() . '_' . $request->file('cover_image')->getClientOriginalName();
                $request->file('cover_image')->move(public_path('img/covers'), $imageName);
                $data['cover_image'] = 'covers/' . $imageName;
            }

            $book->update($data);

            // Actualizar autor
            $authorNames = explode(' ', $request->author_name, 2);
            $firstName = $authorNames[0];
            $lastName = $authorNames[1] ?? '';
            
            $author = Author::firstOrCreate(
            ['first_name' => $firstName, 'last_name' => $lastName],
            ['first_name' => $firstName, 'last_name' => $lastName]
            );

             $book->authors()->sync([$author->author_id]);

            return redirect()
                ->route('admin.books.index')
                ->with('success', 'Libro actualizado exitosamente');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al actualizar libro: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Eliminar libro
    public function deleteBook($id)
    {
        try {
            $book = Book::findOrFail($id);
            
            // Eliminar imagen si existe
            if ($book->cover_image && file_exists(public_path('img/' . $book->cover_image))) {
                unlink(public_path('img/' . $book->cover_image));
            }
            
            $book->delete();

            return redirect()
                ->route('admin.books.index')
                ->with('success', 'Libro eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar libro: ' . $e->getMessage());
        }
    }
}