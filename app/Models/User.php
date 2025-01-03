<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use App\Traits\ManagesRolesAndPermissionsTrait;
use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles,
        ManagesRolesAndPermissionsTrait, SlugTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'slug',
        'phone',
        'first_name',
        'last_name'
    ];

    protected $slugSource = 'name';


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if (! $user->roles->count() ) {
                $role = Role::firstOrCreate(
                    ['name' =>'user', 'guard_name' => 'sanctum']
                );
                $user->syncRoles($role);
            }
        });
    }


    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_child', 'child_id', 'parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(User::class, 'parent_child', 'parent_id', 'child_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = config('app.frontend_url') . '/reset-password?token=' . $token . '&email=' . $this->email;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_user');
    }

    public function hasAnyRole(array $roles): bool
    {
        foreach($roles as $role){
            if ($this->hasRole($role))
                return true;
        }
        return false;
        // return in_array($this->role, $roles);
    }



}
