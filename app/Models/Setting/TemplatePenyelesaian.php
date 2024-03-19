<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class TemplatePenyelesaian extends Model
{
    protected $guarded = [];
    protected $table = 'MstTemplatePenyelesaian';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
}
