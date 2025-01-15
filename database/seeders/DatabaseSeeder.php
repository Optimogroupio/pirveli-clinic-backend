<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BackendUserSeeder::class,
            RoleAndPermissionSeeder::class,
            LocaleSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            LanguageSeeder::class,
            SpecialtySeeder::class,
            DoctorSeeder::class,
            DoctorDetailSeeder::class
        ]);
    }
}
