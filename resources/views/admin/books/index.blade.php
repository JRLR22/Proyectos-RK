@extends('layouts.admin')

@section('content')
<style>
    .admin-title {
        font-weight: 700;
        color: #1a73e8;
    }

    .table thead th {
        background: #f1f3f5;
        font-weight: 600;
        font-size: 14px;
    }

    .table-hover tbody tr:hover {
        background-color: #eef5ff;
    }

    .book-img {
        width: 55px;
        height: 78px;
        border-radius: 4px;
        object-fit: cover;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    .btn-action {
        border-radius: 8px;
        padding: 6px 10px;
    }

    .btn-action i {
        font-size: 14px;
    }

    .badge-stock {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 8px;
    }

    .panel-card {
        border-radius: 18px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: none;
    }
</style>

<div class="container mt-4">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="admin-title">📚 Gestión de Libros</h1>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary px-4 py-2">
            <i class="fas fa-plus me-1"></i> Nuevo Libro
        </a>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>✔ Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>⚠ Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Panel principal -->
    <div class="card panel-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td>{{ $book->book_id }}</td>

                                <!-- Imagen -->
                                <td>
                                    @if($book->cover_image)
                                        <img src="{{ asset('img/' . $book->cover_image) }}" 
                                             class="book-img" 
                                             alt="{{ $book->title }}">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                             style="width:55px; height:78px; border-radius:4px; font-size:11px;">
                                            Sin imagen
                                        </div>
                                    @endif
                                </td>

                                <!-- Información del libro -->
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->authors->pluck('name')->join(', ') }}</td>
                                <td>{{ $book->category->name ?? 'Sin categoría' }}</td>
                                <td>${{ number_format($book->price, 2) }}</td>

                                <!-- Stock -->
                                <td>
                                    <span class="badge badge-stock {{ $book->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $book->stock_quantity }}
                                    </span>
                                </td>

                                <!-- Acciones -->
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.books.edit', $book->book_id) }}" 
                                           class="btn btn-warning btn-sm btn-action">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.books.destroy', $book->book_id) }}"
                                              method="POST" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este libro?');"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm btn-action">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-book-open"></i> No hay libros registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-3">
                {{ $books->links() }}
            </div>

        </div>
    </div>
</div>

@endsection
