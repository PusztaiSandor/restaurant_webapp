<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'users_id',
        'type',
        'rating',
        'subject',
        'content',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Kapcsolat a felhasználóval
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
