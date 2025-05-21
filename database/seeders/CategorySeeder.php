<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Thực phẩm', 'slug' => Str::slug('Thực phẩm'), 'icon' => 'fas fa-apple-alt'], 
            ['name' => 'Đồ dùng', 'slug' => Str::slug('Đồ dùng'), 'icon' => 'fas fa-box-open'], 
            ['name' => 'Khác', 'slug' => Str::slug('Khác'), 'icon' => 'fas fa-ellipsis-h'],  
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
