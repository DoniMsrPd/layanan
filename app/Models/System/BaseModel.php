<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public function scopeSoftDelete($query)
    {
        $query->whereNull('DeletedAt');
    }
}
