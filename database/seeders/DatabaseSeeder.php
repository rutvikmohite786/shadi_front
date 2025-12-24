<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Master data seeders (order matters due to dependencies)
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            ReligionSeeder::class,
            CasteSeeder::class,
            MotherTongueSeeder::class,
            EducationSeeder::class,
            OccupationSeeder::class,
            PlanSeeder::class,
            
            // Sample data
            SampleUserSeeder::class,
        ]);
    }
}
