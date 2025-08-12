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
                'name' => 'Chicken Caesar Bowl',
                'description' => 'Romaine saláta grillezett csirkemellel, parmezánnal és Caesar öntettel.',
                'image' => 'public/images/termekek/chicken_caesar_bowl.jpg',
                'category' => 'Étel',
                'type' => 'Saláta',
                'gross_price' => 3500,
                'tax_percent' => 27.00,
                'on_sale' => 0,
                'discount_percent' => 0.00,
                'size_options' => json_encode([
                    'Kicsi' => ['unit' => 'g', 'amount' => 250, 'multiplier' => 0.8],
                    'Normál' => ['unit' => 'g', 'amount' => 350, 'multiplier' => 1.0],
                    'Nagy' => ['unit' => 'g', 'amount' => 450, 'multiplier' => 1.2],
                ]),
                'base_ingredients' => 'csirke,saláta,parmezán',
                'extra_ingredients' => 'pirított kenyérkocka,extra öntet',
                'ingredient_modifiers' => json_encode([
                    'pirított kenyérkocka' => 150,
                    'extra öntet' => 100,
                    'csirke' => -100,
                ]),
                'calories' => 352,
                'vegetarian' => 0,
                'stock' => 50,
                'allergens' => 'tej,tojás,glutén',
                'active' => 1,
            ],
            [
                'name' => 'Avocado Toast',
                'description' => 'Pirított kenyér avokádókrémmel, lime-mal és chilivel.',
                'image' => 'public/images/termekek/avocado_toast.jpg',
                'category' => 'Vegetáriánus',
                'type' => 'Előétel',
                'gross_price' => 2500,
                'tax_percent' => 27.00,
                'on_sale' => 1,
                'discount_percent' => 10.00,
                'size_options' => json_encode([
                    'Kicsi' => ['unit' => 'szelet', 'amount' => 1, 'multiplier' => 0.8],
                    'Normál' => ['unit' => 'szelet', 'amount' => 2, 'multiplier' => 1.0],
                    'Nagy' => ['unit' => 'szelet', 'amount' => 3, 'multiplier' => 1.2],
                ]),
                'base_ingredients' => 'avokádó,lime,kenyér',
                'extra_ingredients' => 'tojás,parmezán',
                'ingredient_modifiers' => json_encode([
                    'tojás' => 200,
                    'parmezán' => 150,
                    'lime' => -50,
                ]),
                'calories' => 190,
                'vegetarian' => 1,
                'stock' => 50,
                'allergens' => 'tojás,tej,glutén',
                'active' => 1,
            ],
            [
                'name' => 'Butter Chicken',
                'description' => 'Indiai fűszeres csirke vajmártásban, basmati rizzsel.',
                'image' => 'public/images/termekek/butter_chicken.jpg',
                'category' => 'Nemzetközi',
                'type' => 'Húsétel',
                'gross_price' => 4000,
                'tax_percent' => 27.00,
                'on_sale' => 1,
                'discount_percent' => 10.00,
                'size_options' => json_encode([
                    'Kicsi' => ['unit' => 'g', 'amount' => 300, 'multiplier' => 0.8],
                    'Normál' => ['unit' => 'g', 'amount' => 400, 'multiplier' => 1.0],
                    'Nagy' => ['unit' => 'g', 'amount' => 500, 'multiplier' => 1.2],
                ]),
                'base_ingredients' => 'csirke,vaj,paradicsom',
                'extra_ingredients' => 'rizs,koriander',
                'ingredient_modifiers' => json_encode([
                    'rizs' => 200,
                    'koriander' => 50,
                    'vaj' => -100,
                ]),
                'calories' => 412,
                'vegetarian' => 0,
                'stock' => 50,
                'allergens' => 'tej',
                'active' => 1,
            ],
            [
                'name' => 'Chicken Fried Rice',
                'description' => 'Pirított rizs csirkével, zöldségekkel és szójaszósszal.',
                'image' => 'public/images/termekek/chicken_fried_rice.jpg',
                'category' => 'Étel',
                'type' => 'Egytálétel',
                'gross_price' => 3000,
                'tax_percent' => 27.00,
                'on_sale' => 0,
                'discount_percent' => 0.00,
                'size_options' => json_encode([
                    'Kicsi' => ['unit' => 'g', 'amount' => 250, 'multiplier' => 0.8],
                    'Normál' => ['unit' => 'g', 'amount' => 350, 'multiplier' => 1.0],
                    'Nagy' => ['unit' => 'g', 'amount' => 450, 'multiplier' => 1.2],
                ]),
                'base_ingredients' => 'rizs,csirke,zöldség',
                'extra_ingredients' => 'tojás,szójaszósz',
                'ingredient_modifiers' => json_encode([
                    'tojás' => 150,
                    'szójaszósz' => 100,
                    'rizs' => -50,
                ]),
                'calories' => 433,
                'vegetarian' => 0,
                'stock' => 50,
                'allergens' => 'tojás,szója',
                'active' => 1,
            ],
            [
                'name' => 'Hamburger Classic',
                'description' => 'Marhahúsos hamburger friss zöldségekkel és sajttal.',
                'image' => 'public/images/termekek/hamburger_classic.jpg',
                'category' => 'Street_food',
                'type' => 'Hamburger',
                'gross_price' => 3000,
                'tax_percent' => 27.00,
                'on_sale' => 1,
                'discount_percent' => 10.00,
                'size_options' => json_encode([
                    'Kicsi' => ['unit' => 'g', 'amount' => 180, 'multiplier' => 0.8],
                    'Normál' => ['unit' => 'g', 'amount' => 250, 'multiplier' => 1.0],
                    'Nagy' => ['unit' => 'g', 'amount' => 320, 'multiplier' => 1.2],
                ]),
                'base_ingredients' => 'marhahús,zsemle,saláta',
                'extra_ingredients' => 'sajt,bacon',
                'ingredient_modifiers' => json_encode([
                    'sajt' => 150,
                    'bacon' => 200,
                    'zsemle' => -50,
                ]),
                'calories' => 280,
                'vegetarian' => 0,
                'stock' => 50,
                'allergens' => 'tej,glutén',
                'active' => 1,
            ]
        ];

        DB::table('dishes')->insert($dishes);
    }
}
