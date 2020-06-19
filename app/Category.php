<?php

namespace App;

use App\Traits\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, Logs;

    protected $fillable = ['fk_created_by', 'fk_updated_by', 'name'];

    protected $hidden = ['deleted_at'];
}
