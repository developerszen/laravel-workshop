<?php

namespace App;

use App\Traits\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes, Logs;

    protected $fillable = ['fk_created_by', 'title', 'synopsis'];

    function formats() {
        return $this->hasMany(Format::class, 'fk_book');
    }

    function authors() {
        return $this->belongsToMany(Author::class, 'author_book', 'fk_book', 'fk_author');
    }

    function categories() {
        return $this->belongsToMany(Category::class, 'book_category', 'fk_book', 'fk_category');
    }
}
