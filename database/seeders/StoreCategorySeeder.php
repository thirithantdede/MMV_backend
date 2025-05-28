<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StoreCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list = ['Retail', 'Food', 'Drink', 'Service', 'Other', 'Mobile Phone', 'Computer', 'Furniture', 'Electronics', 'Clothing', 'Beauty', 'Health', 'Sports', 'Travel', 'Home', 'Garden', 'Pet', 'Automotive', 'Music', 'Book', 'Movie', 'Game', 'Toy'];

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'name' => $item,
            ];
        }

        \App\Models\StoreCategory::insert($data);
    }
}
