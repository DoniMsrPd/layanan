<?php

namespace App\Models\Setting;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class AsetSMA extends Model
{
    protected $guarded = [];
    protected $table = 'AsetSMA';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
    public function pengguna()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'nip_pengguna', 'Nip');
    }

    public function scopeFiltered($query)
    {
        //for custom datatable
        $query->when(request('search')['value'], function ($query) {
            $param = '%' . request('search')['value'] . '%';

            $query->where(function ($query) use ($param){
                $query->orwhere('nip_pengguna', 'like', $param)
                ->orWhere('nm_brg', 'like', $param)
                ->orWhere('nm_lgkp_brg', 'like', $param)
                ->orWhere('no_ikn', 'like', $param);
            });
        });
    }
}
