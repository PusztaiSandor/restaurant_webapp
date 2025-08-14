<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dish extends Model
{
    use HasFactory;

    protected $primaryKey = 'dishes_id';

    protected $fillable = [
        'name',
        'description',
        'image',
        'category',
        'type',
        'calories',
        'vegetarian',
        'gross_price',
        'tax_percent',
        'size_options',
        'ingredient_modifiers',
        'stock',
        'allergens',
        'base_ingredients',
        'extra_ingredients',
        'on_sale',
        'discount_percent',
        'active',
    ];

    protected $casts = [
        'vegetarian' => 'boolean',
        'on_sale' => 'boolean',
        'active' => 'boolean',
        'size_options' => 'array',
        'ingredient_modifiers' => 'array',
        'base_ingredients' => 'array',
        'extra_ingredients' => 'array',
        'allergens' => 'array',
        'gross_price' => 'float',
        'tax_percent' => 'float',
        'discount_percent' => 'float',
        'calories' => 'integer',
        'stock' => 'integer',
    ];

    // Ha később rendeléshez kapcsolódik:
    public function orders()
    {
        return $this->hasMany(Order::class, 'dishes_id');
    }

     /**
     * 💰 Végső ár kiszámítása méret és extrák alapján
     */
    public function getFinalPrice(string $sizeLabel = 'Normál', array $extras = []): float
{
    $basePrice = $this->gross_price;

    // Méret szorzó
    $multiplier = $this->size_options[$sizeLabel]['multiplier'] ?? 1.0;

    // Extra hozzávalók ármódosítói (csak ha nem üres a tömb)
    $extraCost = 0;
    if (!empty($extras)) {
        foreach ($extras as $extra) {
            $extraCost += $this->ingredient_modifiers[$extra] ?? 0;
        }
    }

    // Akciós ár
    $discountFactor = $this->on_sale ? (1 - ($this->discount_percent / 100)) : 1;

    // Végső ár kiszámítása
    $finalPrice = ($basePrice * $multiplier + $extraCost) * $discountFactor;

    return round($finalPrice, 0);
}


/**
 * 💰 Eredeti ár kiszámítása méret alapján (extrák nélkül, kedvezmény nélkül)
 */
public function getOriginalPrice(string $sizeLabel = 'Normál'): float
{
    $basePrice = $this->gross_price;

    // Méret szorzó
    $multiplier = $this->size_options[$sizeLabel]['multiplier'] ?? 1.0;

    // Eredeti ár (kedvezmény nélkül, extrák nélkül)
    $originalPrice = $basePrice * $multiplier;

    return round($originalPrice, 0);
}

}

