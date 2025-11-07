<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public $timestamps = true;

    protected $primaryKey = 'author_id';

    protected $fillable = [
        'first_name',
        'last_name',
        // otros campos
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
}
