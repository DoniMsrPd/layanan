<?php

namespace App\Models\Layanan;

use Uuids;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $guarded = [];
    protected $table = 'Peminjaman';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $keyType = 'string';
    protected $with = ['peminjamanDetail', 'layanan','pihak2'];
    public function peminjamanDetail()
    {
        return $this->hasMany('App\Models\Layanan\PeminjamanDetail', 'PeminjamanId', 'Id')->whereNull('deletedAt')->orderBy('PengembalianId','DESC');
    }
    public function peminjamanDetailBelumDikembalikan()
    {
        return $this->hasMany('App\Models\Layanan\PeminjamanDetail', 'PeminjamanId', 'Id')->whereNull('deletedAt')->whereNull('PengembalianId');
    }
    public function pengembalian()
    {
        return $this->hasMany('App\Models\Layanan\Pengembalian', 'PeminjamanId', 'Id')->whereNull('deletedAt');
    }
    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan\Layanan', 'LayananId', 'Id');
    }
    public function layananTl()
    {
        return $this->belongsTo('App\Models\Layanan\LayananTL', 'LayananTLId', 'Id');
    }
    public function pihak2()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPihak2', 'Nip');
    }
    public function pihak1()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPihak1', 'Nip');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\Layanan\RefStatusPeminjaman', 'RefStatusPeminjamanId', 'Id');
    }

    public function scopeFiltered($query)
    {
        if (request()->tglStart) {
            $query->whereHas('layanan', function ($q) {
                $q->whereBetween('Layanan.CreatedAt', [request()->tglStart . ' 00:00:00', request()->tglEnd . ' 23:59:59']);
            });
        }
    }
}
