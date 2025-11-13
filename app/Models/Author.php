<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public $timestamps = true;

    protected $table = 'authors';
    protected $primaryKey = 'author_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'biography',
        'birth_date',
        'nationality',
        'photo',
        'website'
    ];

    // Relación muchos a muchos con libros
    public function books()
    {
        return $this->belongsToMany(
            Book::class,
            'book_authors',
            'author_id',
            'book_id'
        )->withPivot('author_order');
    }
    // Accessor para obtener el nombre completo
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
