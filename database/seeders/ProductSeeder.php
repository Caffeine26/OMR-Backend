<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all category IDs to assign randomly
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            DB::table('products')->insert([
                'category_id'  => $faker->randomElement($categoryIds),
                'name'         => $faker->words(2, true),
                'description'  => $faker->sentence(),
                'image_url'    => $faker->imageUrl(300, 300, 'food', true, 'product-' . $index),
                'price'        => $faker->randomFloat(2, 1, 100), // 1.00 - 100.00
                'rate'         => $faker->randomFloat(1, 0, 5),   // rating 0.0 - 5.0
                'type'         => $faker->randomElement(['menu', 'add_on', 'modify']),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
