<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'ქართული',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ინგლისური',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'რუსული',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Language::insert($data);
    }
}
