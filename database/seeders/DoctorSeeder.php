<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'full_name' => 'ნინო გუბიანური',
                'service_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'ნანა ქაჯაია',
                'service_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'გიორგი მიკუჩაძე',
                'service_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'ანა ბენიძე',
                'service_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'ირაკლი ხმალაძე',
                'service_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'ნინო სამადაშვილი',
                'service_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'ნათია ხუბუა',
                'service_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'ქეთევან ტაბატაძე',
                'service_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        Doctor::insert($data);
        foreach(Doctor::all() as $doctor){
            $doctor->languages()->attach([1,2,3]);
        }
    }
}
