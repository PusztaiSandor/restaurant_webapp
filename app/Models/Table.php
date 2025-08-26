<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $primaryKey = 'tables_id';

    protected $fillable = [
        'table_code',
        'location',
        'position',
        'capacity',
        'is_reservable',
        'notes',
    ];

    protected $casts = [
        'is_reservable' => 'boolean',
        'capacity' => 'integer',
    ];

}
