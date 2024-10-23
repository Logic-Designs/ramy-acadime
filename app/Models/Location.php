<?php

namespace App\Models;

use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory, SlugTrait;

    protected $fillable = ['name_en', 'name_ar','slug','country_id', 'city_en', 'city_ar',
                        'address_en', 'address_ar','map'];

    protected $slugSource = 'name_en';

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
