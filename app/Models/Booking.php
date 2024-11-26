<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS = [
        'reserved', 'confirmed'
    ];

    protected $fillable = [
        'user_id', 'level_id', 'location_id', 'status', 'payment_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function times()
    {
        return $this->hasMany(BookingTime::class);
    }
}
