<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id', 'country_id', 'price'
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
