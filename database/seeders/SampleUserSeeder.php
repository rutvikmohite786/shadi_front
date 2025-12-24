<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\State;
use App\Models\City;
use App\Models\Religion;
use App\Models\Caste;
use App\Models\MotherTongue;
use App\Models\Education;
use App\Models\Occupation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '9876543210',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'dob' => '1990-01-15',
                'role' => 'admin',
                'is_active' => true,
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Sample Male Users
        $maleUsers = [
            [
                'name' => 'Rahul Sharma',
                'email' => 'rahul.sharma@example.com',
                'phone' => '9876543211',
                'dob' => '1995-03-20',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'religion' => 'Hindu',
                'caste' => 'Brahmin',
                'mother_tongue' => 'Hindi',
                'education' => 'B.Tech / B.E.',
                'occupation' => 'Software Engineer',
                'about' => 'A passionate software engineer looking for a life partner who shares similar values.',
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram.singh@example.com',
                'phone' => '9876543212',
                'dob' => '1993-07-10',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'religion' => 'Hindu',
                'caste' => 'Rajput',
                'mother_tongue' => 'Hindi',
                'education' => 'MBA',
                'occupation' => 'Manager',
                'about' => 'Working professional with a love for travel and music.',
            ],
            [
                'name' => 'Arjun Patel',
                'email' => 'arjun.patel@example.com',
                'phone' => '9876543213',
                'dob' => '1994-11-25',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'religion' => 'Hindu',
                'caste' => 'Patel',
                'mother_tongue' => 'Gujarati',
                'education' => 'B.Com',
                'occupation' => 'Business Owner',
                'about' => 'Family business owner with traditional values and modern outlook.',
            ],
            [
                'name' => 'Mohammed Imran',
                'email' => 'imran.khan@example.com',
                'phone' => '9876543214',
                'dob' => '1992-05-15',
                'city' => 'Hyderabad',
                'state' => 'Telangana',
                'religion' => 'Muslim',
                'caste' => 'Sunni',
                'mother_tongue' => 'Urdu',
                'education' => 'MBBS',
                'occupation' => 'Doctor',
                'about' => 'Doctor by profession, looking for an educated and caring partner.',
            ],
            [
                'name' => 'Karthik Reddy',
                'email' => 'karthik.reddy@example.com',
                'phone' => '9876543215',
                'dob' => '1996-09-08',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'religion' => 'Hindu',
                'caste' => 'Reddy',
                'mother_tongue' => 'Telugu',
                'education' => 'M.Tech / M.E.',
                'occupation' => 'IT Professional',
                'about' => 'Tech enthusiast working in a leading MNC.',
            ],
        ];

        // Sample Female Users
        $femaleUsers = [
            [
                'name' => 'Priya Gupta',
                'email' => 'priya.gupta@example.com',
                'phone' => '9876543216',
                'dob' => '1996-04-12',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'religion' => 'Hindu',
                'caste' => 'Gupta',
                'mother_tongue' => 'Hindi',
                'education' => 'MBA',
                'occupation' => 'HR Professional',
                'about' => 'Working in HR at a top company. Love reading and cooking.',
            ],
            [
                'name' => 'Anjali Nair',
                'email' => 'anjali.nair@example.com',
                'phone' => '9876543217',
                'dob' => '1997-08-22',
                'city' => 'Kochi',
                'state' => 'Kerala',
                'religion' => 'Hindu',
                'caste' => 'Nair',
                'mother_tongue' => 'Malayalam',
                'education' => 'B.Tech / B.E.',
                'occupation' => 'Software Engineer',
                'about' => 'Software developer with a passion for dance and music.',
            ],
            [
                'name' => 'Sneha Iyer',
                'email' => 'sneha.iyer@example.com',
                'phone' => '9876543218',
                'dob' => '1995-12-05',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'religion' => 'Hindu',
                'caste' => 'Iyer',
                'mother_tongue' => 'Tamil',
                'education' => 'CA',
                'occupation' => 'CA / Accountant',
                'about' => 'Chartered Accountant, believes in work-life balance.',
            ],
            [
                'name' => 'Fatima Sheikh',
                'email' => 'fatima.sheikh@example.com',
                'phone' => '9876543219',
                'dob' => '1998-02-28',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'religion' => 'Muslim',
                'caste' => 'Sheikh',
                'mother_tongue' => 'Urdu',
                'education' => 'BDS',
                'occupation' => 'Doctor',
                'about' => 'Dentist by profession with a caring personality.',
            ],
            [
                'name' => 'Pooja Deshmukh',
                'email' => 'pooja.deshmukh@example.com',
                'phone' => '9876543220',
                'dob' => '1996-06-18',
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'religion' => 'Hindu',
                'caste' => 'Maratha',
                'mother_tongue' => 'Marathi',
                'education' => 'Master\'s Degree',
                'occupation' => 'Teacher / Professor',
                'about' => 'Teaching is my passion. Looking for a kind-hearted partner.',
            ],
            [
                'name' => 'Deepika Joshi',
                'email' => 'deepika.joshi@example.com',
                'phone' => '9876543221',
                'dob' => '1997-10-30',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'religion' => 'Hindu',
                'caste' => 'Brahmin',
                'mother_tongue' => 'Hindi',
                'education' => 'B.Sc',
                'occupation' => 'Healthcare Professional',
                'about' => 'Nurse by profession, love to help people.',
            ],
        ];

        foreach ($maleUsers as $userData) {
            $this->createUserWithProfile($userData, 'male');
        }

        foreach ($femaleUsers as $userData) {
            $this->createUserWithProfile($userData, 'female');
        }
    }

    protected function createUserWithProfile(array $data, string $gender): void
    {
        $user = User::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make('password'),
                'gender' => $gender,
                'dob' => $data['dob'],
                'role' => 'user',
                'is_active' => true,
                'is_verified' => true,
                'profile_completed' => true,
                'email_verified_at' => now(),
            ]
        );

        // Get related IDs
        $state = State::where('name', $data['state'])->first();
        $city = City::where('name', $data['city'])->first();
        $religion = Religion::where('name', $data['religion'])->first();
        $caste = Caste::where('name', $data['caste'])->first();
        $motherTongue = MotherTongue::where('name', $data['mother_tongue'])->first();
        $education = Education::where('name', $data['education'])->first();
        $occupation = Occupation::where('name', $data['occupation'])->first();

        // Generate random values for profile
        $heights = [150, 155, 160, 165, 170, 175, 180, 185];
        $incomes = ['1-3 Lakh', '3-5 Lakh', '5-7 Lakh', '7-10 Lakh', '10-15 Lakh', '15-20 Lakh', '20+ Lakh'];
        $familyTypes = ['Joint', 'Nuclear'];
        $familyStatuses = ['Middle Class', 'Upper Middle Class', 'Rich', 'Affluent'];
        $familyValues = ['Traditional', 'Moderate', 'Liberal'];
        $bodyTypes = ['Slim', 'Average', 'Athletic', 'Heavy'];
        $complexions = ['Fair', 'Wheatish', 'Dusky', 'Dark'];

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'about_me' => $data['about'],
                'height' => $heights[array_rand($heights)],
                'weight' => rand(50, 90),
                'body_type' => $bodyTypes[array_rand($bodyTypes)],
                'complexion' => $complexions[array_rand($complexions)],
                'physical_status' => 'Normal',
                'marital_status' => 'never_married',
                'num_children' => 0,
                'country_id' => 1, // India
                'state_id' => $state?->id,
                'city_id' => $city?->id,
                'religion_id' => $religion?->id,
                'caste_id' => $caste?->id,
                'mother_tongue_id' => $motherTongue?->id,
                'education_id' => $education?->id,
                'occupation_id' => $occupation?->id,
                'annual_income' => $incomes[array_rand($incomes)],
                'family_type' => $familyTypes[array_rand($familyTypes)],
                'family_status' => $familyStatuses[array_rand($familyStatuses)],
                'family_values' => $familyValues[array_rand($familyValues)],
                'father_occupation' => 'Business',
                'mother_occupation' => 'Homemaker',
                'num_brothers' => rand(0, 2),
                'num_sisters' => rand(0, 2),
                'brothers_married' => 0,
                'sisters_married' => 0,
                'diet' => ['vegetarian', 'non_vegetarian', 'eggetarian'][array_rand(['vegetarian', 'non_vegetarian', 'eggetarian'])],
                'smoke' => 'no',
                'drink' => ['no', 'occasionally'][array_rand(['no', 'occasionally'])],
            ]
        );
    }
}
