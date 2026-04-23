<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices, gadgets, and accessories'],
            ['name' => 'Clothing', 'description' => 'Fashion, apparel, and accessories'],
            ['name' => 'Books', 'description' => 'Books, magazines, and publications'],
            ['name' => 'Home & Garden', 'description' => 'Home decor, furniture, and garden supplies'],
            ['name' => 'Sports', 'description' => 'Sports equipment, clothing, and accessories'],
            ['name' => 'Toys', 'description' => 'Toys, games, and entertainment for kids'],
            ['name' => 'Food & Beverages', 'description' => 'Groceries, snacks, and beverages'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
