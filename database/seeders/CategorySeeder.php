<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Restaurant',
                'image' => 'https://images.pexels.com/photos/326055/pexels-photo-326055.jpeg'
            ],
            [
                'name' => 'Salon & Spa',
                'image' => 'https://images.pexels.com/photos/1133957/pexels-photo-1133957.jpeg'
            ],
            [
                'name' => 'Fitness',
                'image' => 'https://images.pexels.com/photos/206359/pexels-photo-206359.jpeg'
            ],
            [
                'name' => 'Entertainment',
                'image' => 'https://images.pexels.com/photos/45853/grey-crowned-crane-bird-crane-animal-45853.jpeg'
            ],
            [
                'name' => 'Automotive',
                'image' => 'https://images.pexels.com/photos/906150/pexels-photo-906150.jpeg'
            ],
            [
                'name' => 'Retail',
                'image' => 'https://images.pexels.com/photos/593655/pexels-photo-593655.jpeg'
            ],
        ];
        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'image' => $category['image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
