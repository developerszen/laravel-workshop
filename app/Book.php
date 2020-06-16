<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    function authors() {
        return $this->belongsToMany(Author::class, 'author_book', 'fk_book', 'fk_author');
    }

    function categories() {
        return $this->belongsToMany(Category::class, 'book_category', 'fk_book', 'fk_category');
    }
}
