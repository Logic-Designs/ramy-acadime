<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\LocaleHelper;

class CountryResource extends JsonResource
{

    protected $isTwoLang;

    // Allow passing an admin flag into the resource
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
        $localizedData = LocaleHelper::getLocalizedFields($this, ['name'], $this->isTwoLang);

        return
            $localizedData + [
            'id' => $this->id,
            'slug' => $this->slug,
            'locations' => LocationResource::collection($this->whenLoaded('locations')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
