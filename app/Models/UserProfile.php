<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address', 'bio', 'birthday', 'gender', 'city_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ciry()
    {
        return $this->belongsTo(City::class);
    }
}
