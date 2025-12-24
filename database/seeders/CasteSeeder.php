<?php

namespace Database\Seeders;

use App\Models\Religion;
use App\Models\Caste;
use Illuminate\Database\Seeder;

class CasteSeeder extends Seeder
{
    public function run(): void
    {
        $castesByReligion = [
            'Hindu' => [
                'Brahmin', 'Kshatriya', 'Vaishya', 'Kayastha', 'Rajput', 'Maratha', 'Jat', 
                'Yadav', 'Gupta', 'Agarwal', 'Baniya', 'Patel', 'Reddy', 'Naidu', 'Nair', 
                'Iyer', 'Iyengar', 'Sharma', 'Khatri', 'Arora', 'Bania', 'Goud', 'Lingayat',
                'Vokkaliga', 'Kurmi', 'Koiri', 'Teli', 'Mali', 'Kumhar', 'Lohar', 'Sonar',
                'Saini', 'Jatav', 'Meena', 'Gurjar', 'Ahir', 'Lodhi', 'Kushwaha', 'Maurya',
                'Pasi', 'Dhobi', 'Nai', 'Chamar', 'Valmiki', 'Kori', 'Other'
            ],
            'Muslim' => [
                'Sunni', 'Shia', 'Ansari', 'Syed', 'Sheikh', 'Pathan', 'Mughal', 'Khan',
                'Qureshi', 'Malik', 'Mirza', 'Bohra', 'Khoja', 'Memon', 'Hanafi', 'Other'
            ],
            'Christian' => [
                'Roman Catholic', 'Protestant', 'Syrian Christian', 'Orthodox', 
                'Pentecostal', 'Methodist', 'Baptist', 'Anglican', 'Other'
            ],
            'Sikh' => [
                'Jat Sikh', 'Khatri Sikh', 'Arora Sikh', 'Ramgarhia', 'Saini Sikh',
                'Lubana', 'Ahluwalia', 'Ghumar', 'Ramdasia', 'Ravidasia', 'Other'
            ],
            'Jain' => [
                'Digambar', 'Shwetambar', 'Agarwal Jain', 'Oswal', 'Porwal', 'Other'
            ],
            'Buddhist' => [
                'Mahayana', 'Theravada', 'Vajrayana', 'Neo Buddhist', 'Other'
            ],
        ];

        foreach ($castesByReligion as $religionName => $castes) {
            $religion = Religion::where('name', $religionName)->first();
            if (!$religion) continue;

            foreach ($castes as $casteName) {
                Caste::updateOrCreate(
                    ['name' => $casteName, 'religion_id' => $religion->id],
                    ['name' => $casteName, 'religion_id' => $religion->id, 'is_active' => true]
                );
            }
        }
    }
}
