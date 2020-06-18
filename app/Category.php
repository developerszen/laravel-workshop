<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['fk_created_by', 'fk_updated_by', 'name'];

    protected $hidden = ['deleted_at'];

    function createdBy() {
        return $this->belongsTo(User::class, 'fk_created_by');
    }

    function updatedBy() {
        return $this->belongsTo(User::class, 'fk_updated_by');
    }
}
