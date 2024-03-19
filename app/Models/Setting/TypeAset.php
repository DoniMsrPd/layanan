<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class TypeAset extends Model
{
    protected $guarded = [];
    protected $table = 'RefTypeAset';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
    public function jnsAset()
    {
        return $this->belongsTo('Modules\Setting\Entities\JnsAset', 'RefJnsAsetId','Id');
    }
}
