<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;

class Activity extends \Spatie\Activitylog\Models\Activity
// class Activity extends Model implements ActivityContract
{
    protected $table = "MelatiLog";
    public function getDescriptionAttribute($val)
    {
        if (strpos($val, 'ID') !== false)
            return substr($val, 0, -39);
        return $val;
    }
}
