<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest Apple smartphone with A17 Pro chip, titanium design, and advanced camera system',
                'price' => 999.99,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android smartphone with AI features and S Pen',
                'price' => 1199.99,
            ],
            [
                'name' => 'MacBook Pro 14"',
                'description' => 'Powerful laptop with M3 Pro chip for professionals',
                'price' => 1999.99,
            ],
            [
                'name' => 'Nike Air Max',
                'description' => 'Comfortable running shoes with air cushioning',
                'price' => 129.99,
            ],
            [
                'name' => 'Levi\'s Jeans',
                'description' => 'Classic denim jeans for everyday wear',
                'price' => 79.99,
            ],
            [
                'name' => 'The Great Gatsby',
                'description' => 'Classic novel by F. Scott Fitzgerald',
                'price' => 14.99,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Premium noise-cancelling headphones',
                'price' => 399.99,
            ],
            [
                'name' => 'Dyson V15 Vacuum',
                'description' => 'Powerful cordless vacuum cleaner',
                'price' => 699.99,
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'category_id' => $categories->random()->id,
            ]);
        }

        // Создаем дополнительные продукты через фабрику
        Product::factory(20)->create();
    }
}
