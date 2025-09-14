<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


    public function orders()
    {
        return $this->hasMany(Order::class, 'dishes_id');
    }

    // Végső ár kiszámítása méret és extrák alapján

    public function getFinalPrice(string $sizeLabel = 'Normál', array $extras = []): float
    {
        $basePrice = $this->gross_price;

        // Méret szorzó lekérése
        $multiplier = $this->size_options[$sizeLabel]['multiplier'] ?? 1.0;

        // Extra hozzávalók árának összegzése
        $extraCost = 0;
        if (! empty($extras)) {
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

    // Méretarányos ár kiszámítása extrák nélkül, kedvezménnyel

    public function getDiscountedSizePrice(string $sizeLabel = 'Normál'): float
    {
        // Alap bruttó ár az adatbázisból
        $basePrice = $this->gross_price;

        // Alapértelmezett szorzó
        $multiplier = 1.0;

        // Ha van size_options tömb és benne a keresett méret, akkor használjuk annak szorzóját
        if (is_array($this->size_options) && isset($this->size_options[$sizeLabel]['multiplier'])) {
            $multiplier = $this->size_options[$sizeLabel]['multiplier'];
        }

        // Kedvezmény faktor kiszámítása
        $discountFactor = $this->on_sale ? (1 - ($this->discount_percent / 100)) : 1;

        // Végső ár = (alapár * szorzó) * kedvezmény
        $discountedSizePrice = ($basePrice * $multiplier) * $discountFactor;

        return round($discountedSizePrice, 0); // Kerekítés egész Ft-ra
    }

    // Eredeti ár kiszámítása méret alapján (extrák nélkül, kedvezmény nélkül)

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
