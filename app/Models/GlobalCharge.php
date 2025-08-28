<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalCharge extends Model
{
    use HasFactory;

// Elsődleges kulcs megadása
    protected $primaryKey = 'global_charges_id';
// Tömegesen kitölthető mezők
    protected $fillable = [
        'charge_type',
        'delivery_method',
        'is_active',
        'is_optional',
        'is_percentage',
        'value',
        'description',
    ];
// Típuskonverziók: automatikusan átalakítja a mezőket
    protected $casts = [
        'is_active' => 'boolean',
         'is_optional' => 'boolean',
        'is_percentage' => 'boolean',
        'value' => 'float',
    ];
}
