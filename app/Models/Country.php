<?php

namespace App\Models;

use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, SlugTrait;

    protected $fillable = [
        'name_en', 'name_ar', 'code', 'slug', 'currency_code_en', 'currency_code_ar'
    ];

    protected $slugSource = 'name_en';


    public function locations()
    {
        return $this->hasManyThrough(Location::class, City::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

}
