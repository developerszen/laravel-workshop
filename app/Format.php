<?php

namespace App;

use App\Traits\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Format extends Model
{
    use SoftDeletes, Logs;

    protected $fillable = [
        'fk_created_by', 'fk_updated_by', 'fk_book', 'fk_editorial', 'edition', 'image', 'price'
    ];

    function book() {
        return $this->belongsTo(Book::class, 'fk_book');
    }

    function editorial() {
        return $this->belongsTo(Editorial::class, 'fk_editorial');
    }
}
