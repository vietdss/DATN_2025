<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $categories = DB::table('categories')->pluck('id')->toArray(); // Lấy danh sách ID của categories

        for ($i = 0; $i < 20; $i++) {
            DB::table('items')->insert([
                'user_id' => rand(1, 5), // Giả sử có 5 người dùng
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'category_id' => $faker->randomElement($categories),
                'location' => $faker->city,
                'status' => $faker->randomElement(['Available', 'Reserved', 'Taken']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
