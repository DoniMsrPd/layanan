<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class ServiceCatalogTematik extends Model
{
    protected $guarded = [];
    protected $table = 'ServiceCatalogTematik';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
}
