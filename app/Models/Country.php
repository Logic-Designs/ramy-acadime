<?php

namespace App\Models;

use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, SlugTrait;

    protected $fillable = ['name_en', 'name_ar','code', 'slug'];

    protected $slugSource = 'name_en';


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
