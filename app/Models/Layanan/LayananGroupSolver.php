<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class LayananGroupSolver extends Model
{
    protected $guarded = [];
    protected $table = 'LayananGroupSolver';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
    public function mstGroupSolver()
    {
        return $this->belongsTo('App\Models\Setting\GroupSolver', 'MstGroupSolverId', 'Id');
    }
    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan\Layanan', 'LayananId', 'Id');
    }
}
