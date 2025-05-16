<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'ამბულატორიული მომსახურება',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'სტაციონალური მომსახურება',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ლაბორატორიულ - დიაგნოსტიკური კვლევები',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        ServiceCategory::insert($data);
    }
}
