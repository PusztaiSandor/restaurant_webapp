<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Elsődleges kulcs megadása
    protected $primaryKey = 'users_id';

    // Tömegesen kitölthető mezők
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

    // Elrejtett mezők
    protected $hidden = [
        'password', // Jelszó sosem jelenik meg
        'remember_token', // Laravel automatikus token mező
    ];

    // Típuskonverziók: automatikusan átalakítja a mezőket
    protected $casts = [
        'active' => 'boolean',
        'must_change_password' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Kapcsolat a felhasználó rendeléseivel
    public function orders()
    {
        return $this->hasMany(Order::class, 'users_id');
    }

    // Kapcsolat a felhasználó asztalfoglalásaival
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'users_id');
    }

    // Kapcsolat a felhasználó által beküldött visszajelzésekkel
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'users_id');
    }

    // Kapcsolat a futárként teljesített kiszállításokkal
    public function deliveries()
    {
        return $this->hasMany(Order::class, 'courier_id');
    }

    // Segédfüggvény a szerepkörök magyar nyelvű megjelenítéséhez
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
