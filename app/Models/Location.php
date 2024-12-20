<?php

namespace App\Models;

use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory, SlugTrait;

    protected $fillable = ['name_en', 'name_ar','slug','city_id',
                        'address_en', 'address_ar','map'];

    protected $slugSource = 'name_en';

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, City::class, 'id', 'id', 'city_id', 'country_id');
    }

    public function sessionTimes()
    {
        return $this->hasMany(SessionTime::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function siteManagers()
    {
        return $this->belongsToMany(User::class, 'location_user');
    }


    public function coaches()
    {
        return $this->belongsToMany(User::class, 'location_coach');
    }



}
