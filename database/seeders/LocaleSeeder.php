<?php

namespace Database\Seeders;

use App\Models\Locale;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'РУС',
                'code' => 'ru',
                'is_default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ENG',
                'code' => 'en',
                'is_default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ქარ',
                'code' => 'ka',
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Locale::insert($data);
    }
}
