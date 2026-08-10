<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cropSeasonId' => $this->crop_season_id,
            'createdBy' => $this->created_by,
            'updatedBy' => $this->updated_by,
            'workDate' => $this->work_date,
            'status' => $this->status == 'plan' ? true : false,
            'title' => $this->title,
            'content' => $this->content,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

            // eagerロードしているとき
            'cropSeason' => $this->whenLoaded('cropSeason', function () {
                return [
                    'cropSeasonsNameYear' => $this->cropSeason->crop->name.$this->cropSeason->year,
                    'cropName' => $this->cropSeason->crop->name,
                    'year' => $this->cropSeason->year,
                    'variety' => $this->cropSeason->variety,
                    'fieldName' => $this->cropSeason->field->name,
                    'fieldNotes' => $this->cropSeason->field->notes,
                ];
            }),

            'createdByName' => $this->whenLoaded('createdBy', function() {
                return $this->createdBy->name;
            }),

            'updatedByName' => $this->whenLoaded('updatedBy', function() {
                return $this->updatedBy->name;
            }),

            'performedBy' => $this->whenLoaded('performedBy', function() {
                return $this->performedBy->map(function ($u) {
                    return [
                        'id' => $u->id,
                        'name' => $u->name,
                    ];
                });
            }),

            'material' => $this->whenLoaded('material', function () {
                return  $this->material->map(function ($mat) {
                    return [
                        'id' => $mat->id,
                        'name' => $mat->name,
                        'defaultDilutionRate' => $mat->default_dilution_rate,
                        'standardSprayVolume' => $mat->standard_spray_volume,
                        'unit' => $mat->unit,
                        'manufacturer' => $mat->manufacturer,
                        'typeId' => $mat->materialCategory->id,
                        'typeLabel' => $mat->materialCategory->label,

                        'quantity' => $mat->pivot->quantity,
                        'dilutionRate' => $mat->pivot->dilution_rate,
                        'materialAmount' => $mat->pivot->material_amount,
                    ];
                });
            }),

        ];
    }
}
