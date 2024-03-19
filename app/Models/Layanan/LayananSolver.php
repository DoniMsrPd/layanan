<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class LayananSolver extends Model
{
    protected $guarded = [];
    protected $table = 'LayananSolver';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
    public function pegawai()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'Nip', 'Nip');
    }
    public function mstSolver()
    {
        return $this->belongsTo('App\Models\Setting\Solver', 'Nip', 'Nip');
    }
    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan\Layanan', 'LayananId', 'Id');
    }
}
