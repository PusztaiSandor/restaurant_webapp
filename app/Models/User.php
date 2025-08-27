<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'users_id'; // Egyedi kulcs

    protected $fillable = [
        'name',
        'email',
        'phone',
        'postal_code',
        'city',
        'street_name',
        'street_number',
        'password',
        'password_hint',
        'role',
        'active',
        'must_change_password',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
        'must_change_password' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Kapcsolatok (ha már vannak más modellek)
    public function orders()
    {
        return $this->hasMany(Order::class, 'users_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'users_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'users_id');
    }

    public function deliveries()
    {
        return $this->hasMany(Order::class, 'courier_id');
    }

    public function getRoleLabel(): string
{
    return match ($this->role) {
        'user' => 'Felhasználó',
        'courier' => 'Futár',
        'admin' => 'Admin',
        default => ucfirst($this->role),
    };
}
}
