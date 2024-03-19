<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class Solver extends Model
{
    protected $guarded = [];
    protected $table = 'MstSolver';
    public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = false;
    public function pegawai()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'Nip', 'Nip');
    }
    public function groupSolver()
    {
        return $this->belongsTo('App\Models\Setting\GroupSolver', 'MstGroupSolverId', 'Id');
    }
    public function scopeSolver($query)
    {
        $query->select('MstSolver.Nip as Nip', 'SpgDataCurrent.NmPeg as NmPeg')
            ->join('SpgDataCurrent', 'SpgDataCurrent.Nip', '=', 'MstSolver.Nip')
            ->join('MstGroupSolver', 'MstGroupSolver.Id', '=', 'MstSolver.MstGroupSolverId')
            ->where('MstGroupSolver.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())
            ->distinct();
    }
}
