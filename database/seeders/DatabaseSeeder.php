<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core seeders
        $this->call([
            UserSeeder::class,
            KriteriaSeeder::class,
            SubKriteriaSeeder::class,
            PenyakitSeeder::class,
            PenyakitSubKriteriaSeeder::class,
        ]);
    }
}
