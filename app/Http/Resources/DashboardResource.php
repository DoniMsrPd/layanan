<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'KdUnitOrgOwnerLayanan' => $this->KdUnitOrgOwnerLayanan,
            'NmUnitOrgOwnerLayanan' => $this->NmUnitOrgOwnerLayanan,
            'PathIcon' =>$this->PathIcon ?  $this->PathIcon : null,
        ];
    }
}