<?php

namespace App\Models\Setting;

use App\Models\Layanan\MstUnitOrgLayananOwner;
use Illuminate\Database\Eloquent\Model;

class GroupSolver extends Model
{
    protected $guarded = [];
    protected $table = 'MstGroupSolver';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    public function owner()
    {
        return $this->belongsTo(MstUnitOrgLayananOwner::class, 'KdUnitOrgOwnerLayanan', 'KdUnitOrgOwnerLayanan');
    }
}
