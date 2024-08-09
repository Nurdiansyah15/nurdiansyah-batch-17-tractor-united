<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'username' => 'user1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123'),
        ]);

        \App\Models\User::factory()->create([
            'username' => 'user2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password123'),
        ]);

        \App\Models\User::factory()->create([
            'username' => 'user3',
            'email' => 'user3@example.com',
            'password' => bcrypt('password123'),
        ]);

        \App\Models\Category::factory()->create([
            'name' => 'Electronics',
        ]);

        \App\Models\Category::factory()->create([
            'name' => 'Books',
        ]);

        \App\Models\Category::factory()->create([
            'name' => 'Clothing',
        ]);

        \App\Models\Product::factory()->create([
            'category_id' => 1, // Assuming a category with ID 1 exists
            'name' => 'Smartphone',
            'price' => 699,
            'image' => 'smartphone.jpg',
        ]);

        \App\Models\Product::factory()->create([
            'category_id' => 2, // Assuming a category with ID 2 exists
            'name' => 'Novel',
            'price' => 19,
            'image' => 'novel.jpg',
        ]);

        \App\Models\Product::factory()->create([
            'category_id' => 3, // Assuming a category with ID 3 exists
            'name' => 'T-Shirt',
            'price' => 25,
            'image' => 'tshirt.jpg',
        ]);

        \App\Models\Transaction::factory()->create([
            'product_id' => 1, // Assuming a product with ID 1 exists
            'user_id' => 1, // Assuming a user with ID 1 exists
            'quantity' => 2,
            'amount' => 1398, // Assuming price is 699 and quantity is 2
        ]);

        \App\Models\Transaction::factory()->create([
            'product_id' => 2, // Assuming a product with ID 2 exists
            'user_id' => 2, // Assuming a user with ID 2 exists
            'quantity' => 1,
            'amount' => 19, // Assuming price is 19 and quantity is 1
        ]);

        \App\Models\Transaction::factory()->create([
            'product_id' => 3, // Assuming a product with ID 3 exists
            'user_id' => 3, // Assuming a user with ID 3 exists
            'quantity' => 3,
            'amount' => 75, // Assuming price is 25 and quantity is 3
        ]);






    }
}
