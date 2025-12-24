<?php

namespace Database\Seeders;

use App\Models\MotherTongue;
use Illuminate\Database\Seeder;

class MotherTongueSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            'Hindi',
            'English',
            'Bengali',
            'Telugu',
            'Marathi',
            'Tamil',
            'Urdu',
            'Gujarati',
            'Kannada',
            'Malayalam',
            'Odia',
            'Punjabi',
            'Assamese',
            'Maithili',
            'Sanskrit',
            'Konkani',
            'Nepali',
            'Sindhi',
            'Dogri',
            'Kashmiri',
            'Manipuri',
            'Bodo',
            'Santali',
            'Rajasthani',
            'Bhojpuri',
            'Haryanvi',
            'Chhattisgarhi',
            'Magahi',
            'Tulu',
            'Kutchi',
            'Other',
        ];

        foreach ($languages as $language) {
            MotherTongue::updateOrCreate(
                ['name' => $language],
                ['name' => $language, 'is_active' => true]
            );
        }
    }
}
