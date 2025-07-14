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
                            [
                                'name' => 'Laptops',
                                'children' => [
                                    ['name' => 'Gaming Laptops'],
                                    ['name' => 'Business Laptops'],
                                ],
                            ],
                            ['name' => 'Desktops'],
                            ['name' => 'Components'],
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
                    ['name' => 'Land'],
                ],
            ],
            [
                'name' => 'Home & Furniture',
                'children' => [
                    ['name' => 'Living Room'],
                    ['name' => 'Bedroom'],
                    ['name' => 'Kitchen'],
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
                    ['name' => 'Fitness'],
                    ['name' => 'Camping'],
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
