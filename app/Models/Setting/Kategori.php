<?php

namespace App\Models\Setting;

use App\Models\Layanan\MstUnitOrgLayananOwner;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $guarded = [];
    protected $table = 'MstKategori';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
    public function owner()
    {
        return $this->belongsTo(MstUnitOrgLayananOwner::class, 'KdUnitOrgOwnerLayanan', 'KdUnitOrgOwnerLayanan');
    }
}
