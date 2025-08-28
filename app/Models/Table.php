<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;
// Elsődleges kulcs megadása
    protected $primaryKey = 'tables_id';
// Tömegesen kitölthető mezők
    protected $fillable = [
        'table_code',
        'location',
        'position',
        'capacity',
        'is_reservable',
        'notes',
    ];
// Típuskonverziók: automatikusan átalakítja a mezőket
    protected $casts = [
        'is_reservable' => 'boolean',
        'capacity' => 'integer',
    ];

}
