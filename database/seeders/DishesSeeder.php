<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DishesSeeder extends Seeder
{
    public function run()
    {
        $dishes = [
            [
                'name' => 'Espresso Macchiato',
                'description' => 'Erős eszpresszó egy kis tejhabbal a tetején – klasszikus olasz kávé.',
                'image' => 'espresso_macchiato.jpg',
                'category' => 'Ital',
                'type' => 'Kávé',
                'calories' => 15,
                'vegetarian' => 1,
                'gross_price' => 1000,
                'tax_percent' => 27.00,
                'size_options' => json_encode([
                    ['label' => 'Kicsi', 'unit' => 'dl', 'multiplier' => 0.8],
                    ['label' => 'Normál', 'unit' => 'dl', 'multiplier' => 1.0],
                    ['label' => 'Nagy', 'unit' => 'dl', 'multiplier' => 1.2],
                ]),
                'ingredient_modifiers' => json_encode([
                    ['name' => 'Espresso', 'modifier' => -50],
                    ['name' => 'Tejhabb', 'modifier' => -50],
                    ['name' => 'Extra tejhab', 'modifier' => 50],
                ]),
                'base_ingredients' => 'Espresso, Tejhabb',
                'extra_ingredients' => 'Extra tejhab, Fahéj',
                'stock' => 50,
                'allergens' => 'Tej',
                'on_sale' => rand(0, 1),
                'discount_percent' => 10.00,
                'active' => 1,
            ],
            [
                'name' => 'Mojito Mocktail',
                'description' => 'Frissítő lime-menta ital szódával – alkoholmentes változat.',
                'image' => 'mojito_mocktail.jpg',
                'category' => 'Ital',
                'type' => 'Koktél',
                'calories' => 90,
                'vegetarian' => 1,
                'gross_price' => 1500,
                'tax_percent' => 27.00,
                'size_options' => json_encode([
                    ['label' => 'Kicsi', 'unit' => 'dl', 'multiplier' => 0.8],
                    ['label' => 'Normál', 'unit' => 'dl', 'multiplier' => 1.0],
                    ['label' => 'Nagy', 'unit' => 'dl', 'multiplier' => 1.2],
                ]),
                'ingredient_modifiers' => json_encode([
                    ['name' => 'Lime', 'modifier' => -50],
                    ['name' => 'Menta', 'modifier' => -50],
                    ['name' => 'Extra menta', 'modifier' => 50],
                ]),
                'base_ingredients' => 'Lime, Menta, Szóda',
                'extra_ingredients' => 'Extra menta, Cukorszirup',
                'stock' => 50,
                'allergens' => null,
                'on_sale' => rand(0, 1),
                'discount_percent' => 10.00,
                'active' => 1,
            ],
            [
                'name' => 'Gyerek Almás Ice Tea',
                'description' => 'Alkoholmentes, enyhén édesített almás jeges tea gyerekeknek.',
                'image' => 'gyerek_ice_tea.jpg',
                'category' => 'Ital',
                'type' => 'Gyerekital',
                'calories' => 60,
                'vegetarian' => 1,
                'gross_price' => 1000,
                'tax_percent' => 27.00,
                'size_options' => json_encode([
                    ['label' => 'Kicsi', 'unit' => 'dl', 'multiplier' => 0.8],
                    ['label' => 'Normál', 'unit' => 'dl', 'multiplier' => 1.0],
                    ['label' => 'Nagy', 'unit' => 'dl', 'multiplier' => 1.2],
                ]),
                'ingredient_modifiers' => json_encode([
                    ['name' => 'Almalé', 'modifier' => -50],
                    ['name' => 'Tea', 'modifier' => -50],
                    ['name' => 'Extra alma', 'modifier' => 50],
                ]),
                'base_ingredients' => 'Almalé, Tea',
                'extra_ingredients' => 'Extra alma, Méz',
                'stock' => 50,
                'allergens' => null,
                'on_sale' => rand(0, 1),
                'discount_percent' => 10.00,
                'active' => 1,
            ],
            [
                'name' => 'Szénsavas Ásványvíz',
                'description' => 'Hűsítő, enyhén szénsavas ásványvíz palackból.',
                'image' => 'szensavas_asvanyviz.jpg',
                'category' => 'Ital',
                'type' => 'Ásványvíz',
                'calories' => 0,
                'vegetarian' => 1,
                'gross_price' => 750,
                'tax_percent' => 27.00,
                'size_options' => json_encode([
                    ['label' => 'Kicsi', 'unit' => 'dl', 'multiplier' => 0.8],
                    ['label' => 'Normál', 'unit' => 'dl', 'multiplier' => 1.0],
                    ['label' => 'Nagy', 'unit' => 'dl', 'multiplier' => 1.2],
                ]),
                'ingredient_modifiers' => null,
                'base_ingredients' => null,
                'extra_ingredients' => null,
                'stock' => 50,
                'allergens' => null,
                'on_sale' => rand(0, 1),
                'discount_percent' => 10.00,
                'active' => 1,
            ],
            [
                'name' => 'Jeges Earl Grey Tea',
                'description' => 'Klasszikus Earl Grey tea jegesen, citrommal és mézzel.',
                'image' => 'jeges_earl_grey.jpg',
                'category' => 'Ital',
                'type' => 'Tea',
                'calories' => 40,
                'vegetarian' => 1,
                'gross_price' => 1250,
                'tax_percent' => 27.00,
                'size_options' => json_encode([
                    ['label' => 'Kicsi', 'unit' => 'dl', 'multiplier' => 0.8],
                    ['label' => 'Normál', 'unit' => 'dl', 'multiplier' => 1.0],
                    ['label' => 'Nagy', 'unit' => 'dl', 'multiplier' => 1.2],
                ]),
                'ingredient_modifiers' => json_encode([
                    ['name' => 'Earl Grey tea', 'modifier' => -50],
                    ['name' => 'Citrom', 'modifier' => -50],
                    ['name' => 'Méz', 'modifier' => 50],
                ]),
                'base_ingredients' => 'Earl Grey tea, Citrom',
                'extra_ingredients' => 'Méz, Jég',
                'stock' => 50,
                'allergens' => null,
                'on_sale' => rand(0, 1),
                'discount_percent' => 10.00,
                'active' => 1,
            ],
            [
                'name' => 'Narancsos Smoothie',
                'description' => 'Friss narancs, banán és joghurt keveréke – igazi vitaminbomba.',
                'image' => 'narancsos_smoothie.jpg',
                'category' => 'Fitness',
                'type' => 'Gyümölcs',
                'calories' => 180,
                'vegetarian' => 1,
                'gross_price' => 2000,
                'tax_percent' => 27.00,
                'size_options' => json_encode([
                    ['label' => 'Kicsi', 'unit' => 'dl', 'multiplier' => 0.8],
                    ['label' => 'Normál', 'unit' => 'dl', 'multiplier' => 1.0],
                    ['label' => 'Nagy', 'unit' => 'dl', 'multiplier' => 1.2],
                ]),
                'ingredient_modifiers' => json_encode([
                    ['name' => 'Narancs', 'modifier' => -100],
                    ['name' => 'Banán', 'modifier' => -50],
                    ['name' => 'Extra joghurt', 'modifier' => 100],
                ]),
                'base_ingredients' => 'Narancs, Banán, Joghurt',
                'extra_ingredients' => 'Extra joghurt, Méz',
                'stock' => 50,
                'allergens' => 'Tej',
                'on_sale' => rand(0, 1),
                'discount_percent' => 10.00,
                'active' => 1,
            ],
        ];

        foreach ($dishes as $dish) {
            DB::table('dishes')->insert($dish);
        }
    }
}
