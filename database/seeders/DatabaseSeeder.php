<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Listing::truncate();
        Category::truncate();
        User::truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ListingSeeder::class);
    }
}
