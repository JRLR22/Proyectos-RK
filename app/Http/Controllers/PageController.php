<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class PageController extends Controller
{
    /**
     * Muestra la página de inicio
     */
    public function inicio()
    {
        // Aquí puedes cargar libros destacados, promociones, etc.
        return view('inicio');
    }

    /**
     * Muestra la página de impresión bajo demanda
     */
     public function impresionBajoDemanda(Request $request)
    {
        // Query base - solo libros de impresión bajo demanda
        $query = Book::with(['category', 'authors'])
            ->where('type', 'Impresión bajo demanda');

        // FILTRO: Novedades por días
        if ($request->has('dias')) {
            $dias = $request->input('dias');
            $query->where('created_at', '>=', now()->subDays($dias));
        }

        // FILTRO: Precio
        if ($request->has('precio')) {
            switch ($request->input('precio')) {
                case 'menos_100':
                    $query->where('price', '<', 100);
                    break;
                case '100_200':
                    $query->whereBetween('price', [100, 200]);
                    break;
                case '200_300':
                    $query->whereBetween('price', [200, 300]);
                    break;
                case '300_800':
                    $query->whereBetween('price', [300, 800]);
                    break;
                case 'mas_800':
                    $query->where('price', '>', 800);
                    break;
            }
        }

        // FILTRO: Disponibilidad
        if ($request->has('disponibilidad') && $request->input('disponibilidad') == 'si') {
            $query->where('status', 'En stock');
        }

        // ORDENAMIENTO
        $orden = $request->input('orden', 'recientes');
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'titulo':
                $query->orderBy('title', 'asc');
                break;
            case 'autor':
                $query->leftJoin('book_authors', 'books.book_id', '=', 'book_authors.book_id')
                      ->leftJoin('authors', 'book_authors.author_id', '=', 'authors.author_id')
                      ->orderBy('authors.last_name', 'asc')
                      ->select('books.*');
                break;
            case 'fecha_edicion':
                $query->orderBy('publication_year', 'desc');
                break;
            case 'disponibilidad':
                $query->orderByRaw("CASE WHEN status = 'En stock' THEN 0 ELSE 1 END");
                break;
            case 'recientes':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginación
        $perPage = $request->input('per_page', 20);
        $books = $query->paginate($perPage)->withQueryString();

        // Contadores para filtros
        $stats = [
            'total' => Book::where('type', 'Impresión bajo demanda')->count(),
            'ultimos_30_dias' => Book::where('type', 'Impresión bajo demanda')
                ->where('created_at', '>=', now()->subDays(30))->count(),
            'ultimos_60_dias' => Book::where('type', 'Impresión bajo demanda')
                ->where('created_at', '>=', now()->subDays(60))->count(),
            'menos_100' => Book::where('type', 'Impresión bajo demanda')
                ->where('price', '<', 100)->count(),
            'entre_100_200' => Book::where('type', 'Impresión bajo demanda')
                ->whereBetween('price', [100, 200])->count(),
            'entre_200_300' => Book::where('type', 'Impresión bajo demanda')
                ->whereBetween('price', [200, 300])->count(),
            'entre_300_800' => Book::where('type', 'Impresión bajo demanda')
                ->whereBetween('price', [300, 800])->count(),
            'mas_800' => Book::where('type', 'Impresión bajo demanda')
                ->where('price', '>', 800)->count(),
            'disponibles' => Book::where('type', 'Impresión bajo demanda')
                ->where('status', 'En stock')->count(),
        ];

        return view('impresion-bajo-demanda', compact('books', 'stats'));
    }

    /**
     * Muestra la página sobre nosotros
     */
    public function sobreNosotros()
    {
        return view('sobre-nosotros');
    }

    /**
     * Muestra la página de nuestras librerías
     */
    public function nuestrasLibrerias()
    {
        // Aquí puedes cargar las ubicaciones de las librerías desde la BD
        $librerias = [
            [
                'nombre' => 'Gonvill Centro',
                'direccion' => 'Dirección ejemplo',
                'telefono' => '123-456-7890',
                'horario' => 'Lun-Vie: 9:00-20:00'
            ],
            // Más librerías...
        ];
        
        return view('nuestras-librerias', compact('librerias'));
    }

    /**
     * Muestra la página de bolsa de trabajo
     */
    public function bolsaTrabajo()
    {
        return view('bolsa-trabajo');
    }

    /**
     * Muestra la página de ayuda
     */
    public function ayuda()
    {
        return view('ayuda');
    }

    /**
     * Muestra la página de SchoolShop
     */
    public function schoolShop()
    {
        return view('schoolshop');
    }

    /**
     * Muestra la página de contacto
     */
    public function contacto()
    {
        return view('contacto');
    }

        /**
     * Muestra la página de micuenta
     */
    public function micuenta()
    {
        return view('micuenta');
    }



}