<?php

namespace Database\Seeders;

use App\Models\Course;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Course::insert([
                'courseName' => $faker->name,
                'courseDescription' => $faker->paragraph(2),
                'courseContent' => $faker->paragraph(2),
                'courseNote' => $faker->paragraph(2),
            ]);
        }
    }
}
