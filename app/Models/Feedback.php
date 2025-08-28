<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    // Elsődleges kulcs megadása
    protected $primaryKey = 'feedback_id';

    // Tömegesen kitölthető mezők
    protected $fillable = [
        'users_id',
        'type',
        'rating',
        'subject',
        'content',
    ];
// Típuskonverziók: automatikusan átalakítja a mezőket
    protected $casts = [
        'rating' => 'integer', // A 'rating' mező mindig egész szám legyen
    ];

    // Kapcsolat a felhasználóval
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
