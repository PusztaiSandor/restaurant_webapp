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
}
