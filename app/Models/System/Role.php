<?php

namespace App\Models\System;


class Role extends \Spatie\Permission\Models\Role
{

    protected $perPage = 13;

    public function scopeFiltered($query)
    {
        $query->when(request('q'), function ($query) {
            $param = sprintf("%%%s%%", request('q'));
            return $query->where('name', 'like', $param);
        });
    }
}
