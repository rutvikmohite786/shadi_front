<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    public function run(): void
    {
        $occupations = [
            ['name' => 'Software Engineer', 'category' => 'IT'],
            ['name' => 'IT Professional', 'category' => 'IT'],
            ['name' => 'Doctor', 'category' => 'Medical'],
            ['name' => 'Engineer', 'category' => 'Engineering'],
            ['name' => 'Teacher / Professor', 'category' => 'Education'],
            ['name' => 'Government Employee', 'category' => 'Government'],
            ['name' => 'Bank Employee', 'category' => 'Banking'],
            ['name' => 'Business Owner', 'category' => 'Business'],
            ['name' => 'CA / Accountant', 'category' => 'Finance'],
            ['name' => 'Lawyer / Advocate', 'category' => 'Legal'],
            ['name' => 'Civil Services (IAS/IPS/IFS)', 'category' => 'Government'],
            ['name' => 'Defence Personnel', 'category' => 'Defence'],
            ['name' => 'Police Officer', 'category' => 'Government'],
            ['name' => 'Architect', 'category' => 'Engineering'],
            ['name' => 'Designer', 'category' => 'Creative'],
            ['name' => 'Marketing Professional', 'category' => 'Marketing'],
            ['name' => 'HR Professional', 'category' => 'HR'],
            ['name' => 'Sales Professional', 'category' => 'Sales'],
            ['name' => 'Finance Professional', 'category' => 'Finance'],
            ['name' => 'Manager', 'category' => 'Management'],
            ['name' => 'Director / CEO', 'category' => 'Management'],
            ['name' => 'Consultant', 'category' => 'Consulting'],
            ['name' => 'Scientist / Researcher', 'category' => 'Research'],
            ['name' => 'Pilot', 'category' => 'Aviation'],
            ['name' => 'Merchant Navy', 'category' => 'Maritime'],
            ['name' => 'Pharma Professional', 'category' => 'Pharma'],
            ['name' => 'Healthcare Professional', 'category' => 'Medical'],
            ['name' => 'Media Professional', 'category' => 'Media'],
            ['name' => 'Artist / Musician', 'category' => 'Creative'],
            ['name' => 'Chef / Hotelier', 'category' => 'Hospitality'],
            ['name' => 'Farmer / Agriculturist', 'category' => 'Agriculture'],
            ['name' => 'Real Estate Professional', 'category' => 'Real Estate'],
            ['name' => 'Self Employed', 'category' => 'Business'],
            ['name' => 'Freelancer', 'category' => 'Freelance'],
            ['name' => 'Student', 'category' => null],
            ['name' => 'Not Working', 'category' => null],
            ['name' => 'Other', 'category' => null],
        ];

        foreach ($occupations as $occupation) {
            Occupation::updateOrCreate(
                ['name' => $occupation['name']],
                ['name' => $occupation['name'], 'category' => $occupation['category'], 'is_active' => true]
            );
        }
    }
}
