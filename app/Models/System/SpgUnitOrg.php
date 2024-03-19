<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class SpgUnitOrg extends Model
{
    protected $guard = [];
    protected $table = 'SpgUnitOrganisasi';
    protected $primaryKey  = 'KdUnitOrg';
    public $incrementing = false;
    protected $keyType = 'string';

    public function scopeFiltered($query)
    {
        //for custom datatable
        $query->when(request('search')['value'], function ($query) {
            $param = '%' . request('search')['value'] . '%';

            $query->where(function ($query) use ($param){
                $query->where('KdUnitOrg', 'like', $param)
                ->orWhere('NmUnitOrg', 'like', $param)
                ->orWhere('NmJabatanLengkap', 'like', $param)
                ->orWhere('NmUnitOrgInduk', 'like', $param);
            });
        });
    }
}
