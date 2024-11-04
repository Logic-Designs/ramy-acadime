<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\LocaleHelper;

class LevelPriceResource extends JsonResource
{
    protected $isTwoLang;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param  bool  $isTwoLang
     */
    public function __construct($resource, $isTwoLang = false)
    {
        parent::__construct($resource);
        $this->isTwoLang = $isTwoLang;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $localizedData = LocaleHelper::getLocalizedFields($this, ['currency_code'], $this->isTwoLang);

        return $localizedData + [
            'id' => $this->id,
            'price' => $this->price,
            'country' => new CountryResource($this->whenLoaded('country'), $this->isTwoLang),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
