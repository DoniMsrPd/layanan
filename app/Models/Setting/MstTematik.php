<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class MstTematik extends Model
{
    protected $guarded = [];
    protected $table = 'MstTematik';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
}
