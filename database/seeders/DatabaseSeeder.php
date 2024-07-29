<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@gmail.com',
                'password' => bcrypt('123456789'),
                'status' => random_int(0, 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user->profile()->create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'phone_number' => fake()->phoneNumber(),
                'profile_picture' => fake()->imageUrl(),
                'cover_picture' => fake()->imageUrl(),
                'bio' => fake()->sentence(),
            ]);

            $user->addresses()->create([
                'address' => fake()->address(),
                'city' => fake()->city(),
                'country' => fake()->country(),
                'postal_code' => fake()->postcode(),
            ]);
        }

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

        $categoryIds = Category::pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            Product::create([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->numberBetween(10, 500),
                'stock' => $faker->numberBetween(1, 100),
                'category_id' => $faker->randomElement($categoryIds),
                'image' => $faker->imageUrl(640, 480, 'products', true),
            ]);
        }
    }
}
