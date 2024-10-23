<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\LocaleHelper;

class LocationResource extends JsonResource
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
        // Fetch the localized fields for regular users
        $localizedData = LocaleHelper::getLocalizedFields($this, ['name', 'city', 'address'], $this->isTwoLang);

        return
            $localizedData + [
            'id' => $this->id,
            'slug' => $this->slug,
            'map' => $this->map,
            'country_slug' => $this->country->slug,
            'country_name' => LocaleHelper::getLocalizedField($this->country, 'name'),
            'country_id' => $this->country->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
