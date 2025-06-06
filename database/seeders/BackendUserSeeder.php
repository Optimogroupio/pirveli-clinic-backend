<?php

namespace Database\Seeders;

use App\Models\DashboardUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BackendUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @param $str
     */
    public function run(): void
    {
        $data = [
            [
                'first_name' => 'Dashboard',
                'last_name' => 'User',
                'login' => 'optimogroup',
                'password' => Hash::make('lamera22'),
                'email' => 'mail@example.com',
                'super_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DashboardUser::insert($data);
    }
}
