<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public $timestamps = true;
    
    protected $table = 'books';
    protected $primaryKey = 'book_id';
    
    protected $fillable = [
        'isbn',
        'gonvill_code',
        'title',
        'subtitle',
        'publisher',
        'publication_year',
        'edition',
        'language',
        'price',
        'pages',
        'stock_quantity',
        'description',
        'cover_image',
        'category_id',
        'status',
        'type'
    ];

  
    protected $appends = [
        'cover_url',
       'authors_list'
    ];

    

    // Relación con categoría
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }


    // ACCESSOR: Lista de autores como string
    public function authors()
     {
        return $this->belongsToMany(
            Author::class,
            'book_authors',
            'book_id',
            'author_id'
        )->withPivot('author_order')->orderBy('author_order');
     }

    // Relación con reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id', 'book_id');
    }

    // ACCESSOR: URL completa de la imagen de portada
    public function getCoverUrlAttribute()
    {
        // Si no hay imagen, retornar placeholder
        if (!$this->cover_image) {
            return 'https://via.placeholder.com/300x400/6366f1/ffffff?text=Sin+Portada';
        }
        
        // Si ya es una URL completa (http/https), devolverla tal cual
        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }
        
        // Construir URL completa: http://tu-dominio.com/img/cover.jpg
        return url('img/' . $this->cover_image);
    }

    //  ACCESSOR: Lista de autores como string
    public function getAuthorsListAttribute()
    {
        if (!$this->relationLoaded('authors')) {
            return '';
        }
        return $this->authors->pluck('name')->join(', ');
    }

    //  ACCESSOR: Calificación promedio
    public function getAverageRatingAttribute()
    {
        if (!$this->relationLoaded('reviews')) {
            return 0;
        }
        return $this->reviews->avg('rating') ?? 0;
    }

    //  ACCESSOR: Total de reseñas
    public function getReviewsCountAttribute()
    {
        if (!$this->relationLoaded('reviews')) {
            return 0;
        }
        return $this->reviews->count();
    }
}