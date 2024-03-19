<?php

namespace App\Models;

use App\Models\Referensi\MstKonselor;
use App\Models\System\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasFactory, HasRoles;


    protected $fillable = [
        'username', 'name', 'email', 'password', 'NIP'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'NIP', 'Nip');
    }
    public function konselor()
    {
        return $this->belongsTo(MstKonselor::class, 'NIP', 'NIK')->whereNull('DeletedAt');
    }
    public function konselorInternal()
    {
        return $this->belongsTo(MstKonselor::class, 'NIP', 'NIP')->whereNull('DeletedAt');
    }

    public function scopeFiltered($query)
    {
        $query->when(request('q'), function ($query) {
            $param = sprintf("%%%s%%", request('q'));
            return $query->where('nip', 'like', $param)->orWhere('name', 'like', $param);
        });
    }

    public function scopeFilterRole($query)
    {
        $query->when(request('role'), function ($query) {
            return $query->whereHas('roles', function ($query) {
                // $param = sprintf("%%%s%%", request('role'));
                $query->Where('id',  request('role'));
            });
        });
    }

    public function scopeFilterOrg($query)
    {
        $kdUnitOrg = rtrim(kdUnitOrgOwner(), '0') . '%';
        $query->leftJoin('SpgDataCurrent as spg', 'spg.Nip', '=', 'users.NIP')
            ->select('users.*', 'spg.KdUnitOrg')
            ->where('spg.KdUnitOrg', 'like', $kdUnitOrg);
    }

    public function scopeFilterByRole($query, $kdUnitOwner, $role)
    {
        $kdUnitOrg = rtrim($kdUnitOwner, '0') . '%';
        $query->leftJoin('SpgDataCurrent as spg', 'spg.Nip', '=', 'users.NIP')
            ->whereHas('roles', function ($q) use ($role) {
                $q->whereIn('name', $role);
            })
            ->select('users.*', 'spg.KdUnitOrg')
            ->where('spg.KdUnitOrg', 'like', $kdUnitOrg);
    }

    /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
