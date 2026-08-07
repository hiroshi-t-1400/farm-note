<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CropSeasonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cropSeasonsId' => $this->id,
            'variety' => $this->variety,
            'supplier' => $this->supplier,
            'plantedArea' => $this->planted_area,
            'plantCount' => $this->plant_count,
            'totalYield' => $this->total_yield,
            'year' => $this->year,

            // リレーション先についてEagerロードされているときのみ
            'crop' => $this->whenLoaded('crop', function() {
                return [
                    'id'    => $this->crop->id,
                    'cropName'  => $this->crop->name,
                ];
            }),
            'field' => $this->whenLoaded('field', function() {
                return [
                    'id'         => $this->field->id,
                    'fieldName'  => $this->field->name,
                    'fieldNotes' => $this->field->notes,
                ];
            }),
        ];
    }
}
