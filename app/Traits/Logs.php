<?php

namespace App\Traits;

use App\User;

trait Logs
{
    function createdBy() {
        return $this->belongsTo(User::class, 'fk_created_by');
    }

    function updatedBy() {
        return $this->belongsTo(User::class, 'fk_updated_by');
    }
}