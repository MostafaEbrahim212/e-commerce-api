<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $users = User::all();
        $productIds = Product::pluck('id')->toArray();

        foreach ($users as $user) {
            for ($j = 0; $j < 5; $j++) {
                $total = 0;
                $order = $user->orders()->create([
                    'total' => $total,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                for ($k = 0; $k < 3; $k++) {
                    $productPrice = $faker->numberBetween(10, 100);
                    $quantity = $faker->numberBetween(1, 10);
                    $order->orderDetails()->create([
                        'product_id' => $faker->randomElement($productIds),
                        'total' => $total,
                        'quantity' => $quantity,
                        'price' => $productPrice,
                    ]);

                    $total += $productPrice * $quantity;
                }

                $order->update(['total' => $total]);

                $order->payment()->create([
                    'amount' => $total,
                    'method' => fake()->randomElement(['credit_card', 'paypal']),
                    'status' => 'pending',
                    'transaction_id' => $faker->uuid,
                    'payment_details' => json_encode([
                        'card_number' => $faker->creditCardNumber,
                        'card_holder' => $faker->name,
                        'expires_at' => $faker->creditCardExpirationDate,
                        'cvv' => $faker->randomNumber(3),
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
