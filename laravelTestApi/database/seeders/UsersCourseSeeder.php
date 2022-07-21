<?php

namespace Database\Seeders;
use Faker\Factory;
use App\Models\UsersCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersCourseSeeder extends Seeder
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
            UsersCourse::insert([
                'status' => 1,
                'userID' => $faker->numberBetween(1,10),
                'courseID' => $faker->randomDigit(1,10)
            ]);
        }
    }
}
