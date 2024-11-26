<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'session_time_id',
        'level_session_id',
        'date',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function sessionTime()
    {
        return $this->belongsTo(SessionTime::class);
    }

    public function levelSession()
    {
        return $this->belongsTo(LevelSession::class);
    }
}
