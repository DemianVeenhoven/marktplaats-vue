<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ad;
use App\Models\User;
use App\Models\Category;
use Database\Seeders\PostalCodeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(3)
            ->has(Ad::factory()
                ->count(2)
                ->has(Category::factory()->count(2))
            )    
            ->create();

            $this->call([
                PostalCodeSeeder::class,
            ]);
    }
}
