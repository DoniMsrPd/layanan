<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananSolverResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Id' => $this->Id,
            'NmPeg' => $this->NmPeg ?? '',
            'Nip' => $this->Nip ?? '',
            'Catatan' => $this->Catatan ?? '',
        ];
    }
}