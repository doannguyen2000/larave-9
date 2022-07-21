<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class CreateUser extends Seeder
{
    use HasFactory;

    public function run()
    {
        $faker = Factory::create();
        // User::factory()
        //     ->count(50)
        //     ->create();

        for ($i = 0; $i < 10; $i++) {
            User::insert([
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'password' => bcrypt(fake()->regexify('[A-Z]{5}[0-4]{3}')),
                'avata' => $faker->image(null, 640, 480),
                'role' => 'user',
            ]);
        }
    }
}
