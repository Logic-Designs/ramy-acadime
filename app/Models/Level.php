<?php

namespace App\Models;

use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory, SlugTrait;

    protected $slugSource = 'name_en';


    protected $fillable = [
        'name_en', 'name_ar', 'slug', 'description_en', 'description_ar', 'min_age', 'gender'
    ];

    public function sessions()
    {
        return $this->hasMany(LevelSession::class);
    }

    public function prices()
    {
        return $this->hasMany(LevelPrice::class);
    }

    public function sessionTimes()
    {
        return $this->hasMany(SessionTime::class);
    }

}
