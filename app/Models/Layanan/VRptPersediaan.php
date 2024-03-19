<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class VRptPersediaan extends Model
{
    protected $guarded = [];
    protected $table = 'vRptPersediaan';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan\Layanan', 'LayananId', 'Id');
    }
}
