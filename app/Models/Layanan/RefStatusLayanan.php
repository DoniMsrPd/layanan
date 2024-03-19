<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class RefStatusLayanan extends Model
{
    protected $guarded = [];
    protected $table = 'RefStatusLayanan';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;

    public function scopeFilterOrg($query, $id)
    {
        $query->where('KdUnitOrgOwnerLayanan', kdUnitOrgOwner());
        if (isset($id)) {
            $query->where('Id', $id);
        }
    }
}
