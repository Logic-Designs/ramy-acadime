<?php

namespace App\Models;

use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, SlugTrait;

    protected $fillable = ['name_en', 'name_ar', 'slug', 'country_id'];

    protected $slugSource = 'name_en';

    /**
     * Define a relationship to the Country model.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
