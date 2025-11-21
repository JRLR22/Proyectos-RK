<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = true;
    
    protected $primaryKey = 'category_id';
    
    protected $fillable = [
        'name',
        'description',
        'parent_category_id'
    ];

    // Relación con libros
    public function books()
    {
        return $this->hasMany(Book::class, 'category_id', 'category_id');
    }

    // Categoría padre
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'category_id');
    }

    // Subcategorías
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id', 'category_id');
    }
}
