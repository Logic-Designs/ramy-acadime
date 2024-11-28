<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_time_id',
        'evaluator_id',
        'evaluator_type',
        'responses',
    ];

    protected $casts = [
        'responses' => 'array', // Automatically cast JSON to array
    ];

    public function bookingTime()
    {
        return $this->belongsTo(BookingTime::class);
    }

    public function evaluator()
    {
        return $this->morphTo();
    }
}
