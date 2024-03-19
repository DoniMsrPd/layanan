<?php

namespace App\Models\Setting;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $guarded = [];
    protected $table = 'AsetLayanan';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
    public function pengguna()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPengguna', 'Nip');
    }
    public function jenisAset()
    {
        return $this->belongsTo('Modules\Setting\Entities\JnsAset', 'RefJnsAsetId', 'Id');
    }

    public function scopeFiltered($query)
    {
        //for custom datatable
        $query->when(request('search')['value'], function ($query) {
            $param = '%' . request('search')['value'] . '%';

            $query->where(function ($query) use ($param){
                $query->orwhere('NipPengguna', 'like', $param)
                ->orWhere('Nama', 'like', $param)
                ->orWhere('NoIkn1', 'like', $param)
                ->orWhere('NoIkn2', 'like', $param);
            });
        });
    }
}
