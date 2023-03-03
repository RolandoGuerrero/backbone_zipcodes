<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZipCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'zip_code' => $this->zip_code,
            'locality' => $this->replace_accents($this->locality),
            'federal_entity' => new FederalEntityResource($this->whenLoaded('federalEntity')),
            'settlements' => SettlementResource::collection($this->whenLoaded('settlements')),
            'municipality' => new MunicipalityResource($this->whenLoaded('municipality'))
        ];
    }

    function replace_accents($str) {
        $str = htmlentities($str, ENT_COMPAT, "UTF-8");
        $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|ring);/','$1',$str);
        return html_entity_decode($str);
    }
}
