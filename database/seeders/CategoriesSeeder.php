<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('categories')->insert([
                'name_khmer'   => 'ក្រុម ' . $index, // simple Khmer label
                'name_english' => $faker->word(),
                'image'    => $faker->imageUrl(200, 200, 'category', true, 'cat-' . $index),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
