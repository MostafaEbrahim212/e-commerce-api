<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            $name = $faker->unique()->word;
            Category::create([
                'name' => $name,
                'slug' => \Str::slug($name),
                'description' => $faker->sentence,
                'image' => $faker->imageUrl(640, 480, 'categories', true),
            ]);
        }
    }
}
