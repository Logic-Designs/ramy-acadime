<?php

namespace App\Models;

use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelSession extends Model
{
    use HasFactory, SlugTrait;

    protected $slugSource = 'name_en';

    protected $fillable = [
        'name_en', 'name_ar', 'slug', 'description_en', 'description_ar', 'level_id'
    ];


    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function bookingTimes()
    {
        return $this->hasMany(BookingTime::class);
    }
}
