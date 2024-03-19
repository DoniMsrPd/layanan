<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class ServiceCatalogDetail extends Model
{
    protected $guarded = [];
    protected $table = 'ServiceCatalogDetail';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    public function catalog()
    {
        return $this->belongsTo('App\Models\Setting\ServiceCatalog', 'ServiceCatalogId', 'Id');
    }
    public function scopeFiltered($query)
    {
        //for custom datatable
        $query->when(request()->q<>null, function ($query) {
            $param = '%' . request()->q . '%';
            $query->where('Nama', 'like', $param);
        });
    }
}
