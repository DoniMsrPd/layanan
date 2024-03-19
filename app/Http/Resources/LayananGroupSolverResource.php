<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananGroupSolverResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Id' => $this->Id,
            'KdUnitOrg' => $this->MstGroupSolverId ?? '',
            'Kode' => $this->mstGroupSolver->Kode ?? '',
        ];
    }
}