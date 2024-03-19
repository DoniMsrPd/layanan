<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class LayananLog extends Model
{
    protected $guarded = [];
    protected $table = 'LayananLog';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
    public function statusAwal()
    {
        return $this->belongsTo('App\Models\Layanan\RefStatusLayanan', 'RefStatusLayananIdAwal', 'Id');
    }
    public function statusAkhir()
    {
        return $this->belongsTo('App\Models\Layanan\RefStatusLayanan', 'RefStatusLayananIdAkhir', 'Id');
    }
}
