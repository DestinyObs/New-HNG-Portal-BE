<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Software Development'],
            ['name' => 'Design'],
            ['name' => 'Marketing'],
            ['name' => 'Sales'],
            ['name' => 'Customer Support'],
            ['name' => 'Human Resources'],
            ['name' => 'Finance'],
            ['name' => 'Operations'],
            ['name' => 'Product'],
            ['name' => 'Engineering'],
            ['name' => 'Data & Analytics'],
            ['name' => 'Security'],
        ];

        foreach ($categories as $category) {
            Category::factory()->create(
                [
                    'name' => $category['name'],
                ]
            );
        }
    }
}
