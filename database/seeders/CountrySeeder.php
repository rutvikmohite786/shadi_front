<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'India', 'code' => 'IN', 'is_active' => true],
            ['name' => 'United States', 'code' => 'US', 'is_active' => true],
            ['name' => 'United Kingdom', 'code' => 'GB', 'is_active' => true],
            ['name' => 'Canada', 'code' => 'CA', 'is_active' => true],
            ['name' => 'Australia', 'code' => 'AU', 'is_active' => true],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'is_active' => true],
            ['name' => 'Singapore', 'code' => 'SG', 'is_active' => true],
            ['name' => 'Germany', 'code' => 'DE', 'is_active' => true],
            ['name' => 'New Zealand', 'code' => 'NZ', 'is_active' => true],
            ['name' => 'South Africa', 'code' => 'ZA', 'is_active' => true],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code' => $country['code']], $country);
        }
    }
}

















