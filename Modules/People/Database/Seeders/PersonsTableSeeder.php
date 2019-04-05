<?php

namespace Modules\People\Database\Seeders;

use Illuminate\Database\Seeder;

class PersonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TODO use factory
        $names = [
            'Mohammed',
            'Ahmed',
            'Ali',
            'Talha',
            'Farajallah',
            'Kamaal',
            'Shaqeeq',
            'Mahmood',
            'Dhaafir',
            'Abdur Razzaaq',
            'Sabri',
            'Musaaid',
            'Taqi',
            'Shaimaaa',
            'Lateefa',
            'Amal',
            'Tamanna',
            'Samraa',
            'Himma',
            'Gaitha',
            'Labeeba',
            'Awaatif',
            'Saaliha',
            'Taamira',
            'Kabeera',
            'Sulama',
            'Najwa',
            'Khaleela',
            'Radiyya',
            'Saaliha',
            'Safwa',
            'Nabeela',
            'Hishma',
            'Awn',
            'Abdul Baari',
            'Abdur Raheem',
            'Mu Aawiya',
            'Uqbah',
            'Shaafi',
            'Noori',
            'Muaaid',
            'Abdul Kader',
            'Faatih',
            'Fatma',
            'Nergiz'
        ];
        $family_namnes = ['al-Nazar',
            'al-Farah',
            'el-Wakim',
            'al-Greiss',
            'al-Syed',
            'el-Fahmy',
            'al-Saladin',
            'al-Emami',
            'el-Turay',
            'el-Abraham',
            'el-Muhammed',
            'el-Jamail',
            'el-Rahaman',
            'al-Imam',
            'al-Assaf',
            'al-Hakim',
            'el-Rasul',
            'el-Mahdavi',
            'el-Sadek',
            'el-Farra',
            'el-Mahmud',
            'el-Saade',
            'el-Yousif',
            'el-Hasan',
            'el-Abid',
            'al-Rabbani',
            'el-Eid',
            'el-Saladin',
            'el-Fayad',
            'el-Mansur',
            'al-Ameen',
            'al-Vohra',
            'el-Tawil',
            'al-Masih',
            'al-Ghazi',
            'al-Jamail',
            'el-Dia',
            'al-Hussain',
            'al-Baddour',
            'al-Badour'
        ];
        
        $nationality = [
            null,
            'Syria',
            'Lebanon',
            'Jordan',
            'Iraq',
            'Iran',
            'Afghanistan',
            'Pakistan',
            'Egypt',
            'Lybia'
        ];
        $languages = [
            null,
            'English',
            'Arabic',
            'Farsi',
            'Urdu',
            'Pashto',
            'Kurdish',
            'Turkish',
            'French',
            'German',
            'Tigrinya',
            'Italian'
        ];

        $items = [];
        for ($i = 0; $i < 900; $i++) {
            \Modules\People\Entities\Person::create([
                'name' => $names[array_rand($names)],
                'family_name' => $family_namnes[array_rand($family_namnes)],
                'police_no' => rand(0,10) > 5 ? rand(10000,99999) : null,
                'case_no' => rand(0,10) > 5 ? rand(10000,99999) : null,
                'registration_no' => rand(0,100) < 15 ? rand(10000,99999) : null,
                'section_card_no' => rand(0,100) < 5 ? rand(10000,99999) : null,
                'nationality' => $nationality[array_rand($nationality)],
                'languages' => [$languages[array_rand($languages)]],
                'gender' => rand(0, 10) > 2 ? (rand(0, 100) > 52 ? 'm' : 'f') : null,
                'date_of_birth' => rand(0, 10) > 4 ? Carbon\Carbon::now()->subYears(rand(0, 60))->subMonths(rand(0,11))->subDays(rand(0,30)) : null,
            ]);
        }
    }
}
