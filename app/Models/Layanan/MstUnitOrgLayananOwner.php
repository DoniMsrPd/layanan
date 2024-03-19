<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class MstUnitOrgLayananOwner extends Model
{
    protected $guarded = [];
    protected $table = 'MstUnitOrgLayananOwner';
    protected $primaryKey = 'KdUnitOrgOwnerLayanan';
	public $timestamps = false;
    protected $keyType = 'string';

}
