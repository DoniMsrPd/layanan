<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class Persediaan extends Model
{
    protected $guarded = [];
    protected $table = 'MstPersediaan';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
}
