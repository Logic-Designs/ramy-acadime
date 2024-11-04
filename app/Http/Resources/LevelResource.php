<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\LocaleHelper;

class LevelResource extends JsonResource
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
        $localizedData = LocaleHelper::getLocalizedFields($this, ['name', 'description'], $this->isTwoLang);

        return $localizedData + [
            'id' => $this->id,
            'slug' => $this->slug,
            'min_age' => $this->min_age,
            'gender' => $this->gender,
            'prices' => LevelPriceResource::collection($this->whenLoaded('prices')),
            'sessions' => LevelSessionResource::collection($this->whenLoaded('sessions')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
