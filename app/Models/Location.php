<?php

namespace App\Models;

use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory, SlugTrait;

    protected $fillable = ['name', 'slug','country_id', 'city', 'address', 'map'];

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
