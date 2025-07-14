<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\User;
use App\Models\Category;
use Faker\Factory as Faker;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $leafCategories = Category::doesntHave('children')->get();

        $faker = Faker::create();

        foreach ($leafCategories as $category) {
            $numberOfListings = rand(25, 50);

            foreach (range(1, $numberOfListings) as $i) {
                Listing::create([
                    'user_id' => $users->random()->id,
                    'category_id' => $category->id,
                    'title' => $faker->sentence(4),
                    'description' => $faker->paragraph(4),
                    'price' => $faker->randomFloat(2, 10, 10000),
                    'condition' => $faker->randomElement(['new', 'used']),
                    'image_path' => null,
                    'phone' => $faker->phoneNumber,
                    'location' => $faker->city,
                ]);
            }
        }

        $this->command->info('Listings seeded: ' . Listing::count());
    }
}
