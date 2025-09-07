<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Elsődleges kulcs megadása
    protected $primaryKey = 'order_items_id';

    // Tömegesen kitölthető mezők
    protected $fillable = [
        'orders_id',
        'dishes_id',
        'size',
        'size_multiplier',
        'quantity',
        'extra_ingredients',
        'excluded_ingredients',
        'ingredient_price',
        'exclusion_discount',
        'final_unit_price',
        'tax_amount',
        'subtotal',
    ];

    // Típuskonverziók: automatikusan átalakítja a mezőket
    protected $casts = [
        'size_multiplier' => 'float',
        'quantity' => 'integer',
        'extra_ingredients' => 'array',
        'excluded_ingredients' => 'array',
        'ingredient_price' => 'float',
        'exclusion_discount' => 'float',
        'final_unit_price' => 'float',
        'tax_amount' => 'float',
        'subtotal' => 'float',
    ];

    // Kapcsolat a rendeléshez
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    // Kapcsolat az ételhez
    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dishes_id');
    }
}
