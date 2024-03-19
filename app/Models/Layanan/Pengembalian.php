<?php

namespace App\Models\Layanan;

use Uuids;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $guarded = [];
    protected $table = 'Pengembalian';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $keyType = 'string';
    protected $with = ['peminjamanDetail', 'layanan', 'pihak2'];
    public function peminjamanDetail()
    {
        return $this->hasMany('App\Models\Layanan\PeminjamanDetail', 'PengembalianId', 'Id')->whereNull('deletedAt');
    }
    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan\Layanan', 'LayananId', 'Id');
    }
    public function pihak2()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPihak2', 'Nip');
    }
    public function pihak1()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPihak1', 'Nip');
    }
}
