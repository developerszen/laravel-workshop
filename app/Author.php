<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    function createdBy() {
        return $this->belongsTo(User::class, 'fk_created_by');
    }

    function updatedBy() {
        return $this->belongsTo(User::class, 'fk_updated_by');
    }
}
