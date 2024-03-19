<?php

namespace App\Models\System;


class Permission extends \Spatie\Permission\Models\Permission
{

    public function scopeFiltered($query)
    {
        $query->when(request('q'), function ($query) {
            $param = sprintf("%%%s%%", request('q'));
            return $query->where('name', 'like', $param);
        });
    }
}
