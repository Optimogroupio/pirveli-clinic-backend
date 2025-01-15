<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'ექიმი-ოტორინოლარინგოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ექიმი-რევმატოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ქირურგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ორთოპედ-ტრავმატოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ექიმი-ოფთალმოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'გასტროენტეროლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'პედიატრი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ექიმი კარდიოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ენდიკრინოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'პედიატრი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'გინეკოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'რეპროდუქტიოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ოფთალმოლოგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ქირურგი',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Specialty::insert($data);
    }
}
