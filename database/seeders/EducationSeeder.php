<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $educations = [
            ['name' => 'High School', 'category' => 'School'],
            ['name' => 'Intermediate / 12th', 'category' => 'School'],
            ['name' => 'Diploma', 'category' => 'Diploma'],
            ['name' => 'Bachelor\'s Degree', 'category' => 'UG'],
            ['name' => 'B.Tech / B.E.', 'category' => 'UG'],
            ['name' => 'BCA', 'category' => 'UG'],
            ['name' => 'BBA', 'category' => 'UG'],
            ['name' => 'B.Com', 'category' => 'UG'],
            ['name' => 'B.Sc', 'category' => 'UG'],
            ['name' => 'BA', 'category' => 'UG'],
            ['name' => 'MBBS', 'category' => 'UG'],
            ['name' => 'BDS', 'category' => 'UG'],
            ['name' => 'B.Pharm', 'category' => 'UG'],
            ['name' => 'LLB', 'category' => 'UG'],
            ['name' => 'Master\'s Degree', 'category' => 'PG'],
            ['name' => 'M.Tech / M.E.', 'category' => 'PG'],
            ['name' => 'MCA', 'category' => 'PG'],
            ['name' => 'MBA', 'category' => 'PG'],
            ['name' => 'M.Com', 'category' => 'PG'],
            ['name' => 'M.Sc', 'category' => 'PG'],
            ['name' => 'MA', 'category' => 'PG'],
            ['name' => 'MD', 'category' => 'PG'],
            ['name' => 'MS (Medicine)', 'category' => 'PG'],
            ['name' => 'LLM', 'category' => 'PG'],
            ['name' => 'Ph.D', 'category' => 'Doctorate'],
            ['name' => 'CA', 'category' => 'Professional'],
            ['name' => 'CS', 'category' => 'Professional'],
            ['name' => 'ICWA', 'category' => 'Professional'],
            ['name' => 'Other', 'category' => null],
        ];

        foreach ($educations as $education) {
            Education::updateOrCreate(
                ['name' => $education['name']],
                ['name' => $education['name'], 'category' => $education['category'], 'is_active' => true]
            );
        }
    }
}
