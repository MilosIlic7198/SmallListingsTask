<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
{
    $categories = [
        [
            'name' => 'Electronics',
            'children' => [
                [
                    'name' => 'Computers',
                    'children' => [
                        ['name' => 'Laptops'],
                        ['name' => 'Desktops'],
                        ['name' => 'Components'],
                        ['name' => 'Gaming Laptops'],
                        ['name' => 'Business Laptops'],
                    ],
                ],
                [
                    'name' => 'Phones',
                    'children' => [
                        ['name' => 'Smartphones'],
                        ['name' => 'Accessories'],
                    ],
                ],
                [
                    'name' => 'TV & Audio',
                    'children' => [
                        ['name' => 'Televisions'],
                        ['name' => 'Soundbars'],
                        ['name' => 'Headphones'],
                    ],
                ],
            ],
        ],
        [
            'name' => 'Vehicles',
            'children' => [
                [
                    'name' => 'Cars',
                    'children' => [
                        ['name' => 'Used Cars'],
                        ['name' => 'New Cars'],
                        ['name' => 'Car Parts'],
                    ],
                ],
                [
                    'name' => 'Motorcycles',
                    'children' => [
                        ['name' => 'Scooters'],
                        ['name' => 'Sport Bikes'],
                    ],
                ],
            ],
        ],
        [
            'name' => 'Real Estate',
            'children' => [
                [
                    'name' => 'Residential',
                    'children' => [
                        ['name' => 'Apartments'],
                        ['name' => 'Houses'],
                        ['name' => 'Vacation Homes'],
                    ],
                ],
                [
                    'name' => 'Commercial',
                    'children' => [
                        ['name' => 'Office Spaces'],
                        ['name' => 'Warehouses'],
                    ],
                ],
                [
                    'name' => 'Land',
                    'children' => [],
                ],
            ],
        ],
        [
            'name' => 'Home & Furniture',
            'children' => [
                [
                    'name' => 'Living Room',
                    'children' => [],
                ],
                [
                    'name' => 'Bedroom',
                    'children' => [],
                ],
                [
                    'name' => 'Kitchen',
                    'children' => [],
                ],
            ],
        ],
        [
            'name' => 'Sports & Outdoors',
            'children' => [
                [
                    'name' => 'Cycling',
                    'children' => [
                        ['name' => 'Mountain Bikes'],
                        ['name' => 'Road Bikes'],
                    ],
                ],
                [
                    'name' => 'Fitness',
                    'children' => [],
                ],
                [
                    'name' => 'Camping',
                    'children' => [],
                ],
            ],
        ],
    ];

    $this->createCategories($categories);
}


    private function createCategories(array $categories, ?Category $parent = null): void
    {
        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $category = new Category($categoryData);
            $category->parent()->associate($parent);
            $category->save();

            if (!empty($children)) {
                $this->createCategories($children, $category);
            }
        }
    }
}
