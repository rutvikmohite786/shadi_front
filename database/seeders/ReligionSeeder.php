<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $religions = [
            'Hindu',
            'Muslim',
            'Christian',
            'Sikh',
            'Buddhist',
            'Jain',
            'Parsi',
            'Jewish',
            'Other',
        ];

        foreach ($religions as $religion) {
            Religion::updateOrCreate(
                ['name' => $religion],
                ['name' => $religion, 'is_active' => true]
            );
        }
    }
}
