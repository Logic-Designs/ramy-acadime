<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\LocaleHelper;

class CityResource extends JsonResource
{
    protected $isTwoLang;

    // Allow passing a flag to support two languages
    public function __construct($resource, $isTwoLang = false)
    {
        parent::__construct($resource);
        $this->isTwoLang = $isTwoLang;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (request()->query('simple')) {
            return [
                'id' => $this->id,
                'name' => LocaleHelper::getLocalizedField($this, 'name', $this->isTwoLang),
            ];
        }

        // Fetch the localized fields for the city in full mode
        $localizedData = LocaleHelper::getLocalizedFields($this, ['name'], $this->isTwoLang);

        return $localizedData + [
            'id' => $this->id,
            'slug' => $this->slug,
            'country_id' => $this->country->id,
            'country_name' => LocaleHelper::getLocalizedField($this->country, 'name'),
            'locations' => LocationResource::collection($this->whenLoaded('locations')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
