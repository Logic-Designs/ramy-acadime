<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionTime extends Model
{
    use HasFactory;

    public const DAYS_OF_WEEK = [
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];

    protected $fillable = [
        'location_id', 'start_time', 'end_time', 'day_of_week',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function bookingTimes()
    {
        return $this->hasMany(BookingTime::class, 'session_time_id');
    }


}
