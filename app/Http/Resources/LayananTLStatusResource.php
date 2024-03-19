<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananTLStatusResource extends JsonResource
{
    public function toArray($request)
    {

        $str= '';
        if($this->statusAwal){
            $str = 'from';
        }
        return [
            'Status' => "Status changed $str ".optional($this->statusAwal)->Nama." to ".optional($this->statusAkhir)->Nama ." <br>",
        ];
    }
}