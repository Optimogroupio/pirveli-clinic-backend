<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DoctorDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'type' => 'education',
                'name' => 'თბილისის სახელმწიფო სამედიცინო აკადემია',
                'title' => 'რეზიდენტურა',
                'start_date' => now()->setDate(2020,11,1),
                'end_date' => now()->setDate(2024,10,1),
                'to_this_day' => false,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'education',
                'name' => 'დიპლომის შემდგომი განათლების სასწავლო ციკლი',
                'title' => 'ოფთალმოლოგია',
                'start_date' => now()->setDate(2016,6,1),
                'end_date' => now()->setDate(2023,12,1),
                'to_this_day' => false,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'education',
                'name' => 'თბილისის სახელმწიფო სამედიცინო უნივერსიტეტი',
                'title' => 'რეზიდენტურა',
                'start_date' => now()->setDate(2015, 1,1),
                'end_date' => now()->setDate(2024,4,1),
                'to_this_day' => false,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'experience',
                'name' => 'მედკაპიტალი',
                'title' => 'ექიმი-ოფთალმოლოგი',
                'start_date' => now()->setDate(2019,12,1),
                'end_date' => now()->setDate(2023,12,1),
                'to_this_day' => false,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'experience',
                'name' => '"ავერსის კლინიკა" ',
                'title' => 'ექიმი ოფთალმოლოგი',
                'start_date' => now()->setDate(2018,8,1),
                'end_date' => now()->setDate(2023,2,1),
                'to_this_day' => false,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'experience',
                'name' => 'ოპტიკურ სალონთა ქსელი “СМОТРИ”',
                'title' => 'ექიმი-ოფთალმოლოგი',
                'start_date' => now()->setDate(2022,5,1),
                'end_date' => null,
                'to_this_day' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'experience',
                'name' => 'კლინიკა პირველი ',
                'title' => 'ექიმი-ოფთალმოლოგი',
                'start_date' => now()->setDate(2018,12,1),
                'end_date' => now()->setDate(2022,3,1),
                'to_this_day' => false,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'certificate',
                'name' => 'დავით ტვილდიანის სამედიცინო უნივერსიტეტი',
                'title' => 'უმაღლესი სამედიცინო განათლება',
                'start_date' => now()->setDate(2023,1,1),
                'end_date' => now()->setDate(2024,7,1),
                'to_this_day' => false,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'certificate',
                'name' => 'თბილსის სახელმწიფო სამედიცინო უნივერსიტეტი',
                'title' => 'უმაღლესი სამედიცინო განათლება',
                'start_date' => now()->setDate(2022,11,1),
                'end_date' => now()->setDate(2024,9,1),
                'to_this_day' => false,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'certificate',
                'name' => 'სამედიცინო აკადემია, მინსკი, ბელარუსი',
                'title' => 'რეზიდენტურა',
                'start_date' => now()->setDate(2013, 1,1),
                'end_date' => now()->setDate(2024,3,1),
                'to_this_day' => false,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach (Doctor::all() as $doctor) {
            foreach ($data as $detail) {
                $doctor->doctorDetails()->create($detail);
            }
        }
    }
}
