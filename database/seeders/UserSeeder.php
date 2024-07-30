<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@gmail.com',
                'password' => bcrypt('123456789'),
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
    }
}
