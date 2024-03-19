<?php

namespace App\Models\Setting;

use App\Models\Layanan\MstUnitOrgLayananOwner;
use Illuminate\Database\Eloquent\Model;

class ServiceCatalog extends Model
{
    protected $guarded = [];
    protected $table = 'ServiceCatalog';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';

    public function tematik()
    {
        return $this->hasMany('App\Models\Setting\ServiceCatalogTematik', 'ServiceCatalogId', 'Id')
            ->whereNull('deletedAt');
    }
    public function scopeFiltered($query)
    {
        //for custom datatable
        $query->when(request()->q<>null, function ($query) {
            $param = '%' . request()->q . '%';
            $query->where('Nama', 'like', $param)
            ->orWhere('Kode', 'like', $param);
        });
    }
    public function owner()
    {
        return $this->belongsTo(MstUnitOrgLayananOwner::class, 'KdUnitOrgOwnerLayanan', 'KdUnitOrgOwnerLayanan');
    }
}
