<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'rating' => 'integer', // A 'rating' mező mindig egész szám legyen
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
