<?php

namespace App\Models\System;


class Pegawai extends BaseModel
{
    protected $table = "SpgDataCurrent";

    public function scopeActive($query)
    {
        $query->where('StsPensiun', '!=', 1);
    }

    public function scopeFiltered($query)
    {
        $query->when(request('q'), function ($query) {
            $param = sprintf("%%%s%%", request('q'));
            $query->where('Nip', 'like', $param)
                ->orWhere('NmPeg', 'like', $param)
                ->orWhere('NmUnitOrg', 'like', $param)
                ;
        });
    }

    public function inputer()
    {
        return $this->hasMany(MstInputer::class, 'NipStruktural', 'Nip');
    }
}
