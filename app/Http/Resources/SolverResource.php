<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SolverResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Nip' => $this->Nip,
            'NmPeg' => $this->pegawai->NmPeg ?? $this->NmPeg ?? '',
            'NmUnitOrg' => $this->pegawai->NmUnitOrg ?? $this->NmUnitOrg ?? '',
        ];

        // return [
        //     'data' => $this->collection,
        //     'links' => [
        //         'self' => 'link-value',
        //     ],
        // ];
    }
}