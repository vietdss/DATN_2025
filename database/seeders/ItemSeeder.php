<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'user_id'     => 1,
                'title'       => 'Mì tôm Hảo Hảo (thùng 30 gói)',
                'description' => 'Chia sẻ thùng mì còn nguyên, hạn sử dụng đến 12/2025.',
                'category_id' => 1,
                'location'    => json_encode(['lat' => 10.762622, 'lng' => 106.660172]),
                'quantity'    => 1,
                'expired_at'  => '2025-12-01 00:00:00',
                'status'      => 'Available',
                'is_approved' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'user_id'     => 2,
                'title'       => 'Nước mắm truyền thống 500ml',
                'description' => 'Chưa mở nắp, còn nguyên, tặng cho ai cần.',
                'category_id' => 1,
                'location'    => json_encode(['lat' => 21.028511, 'lng' => 105.804817]),
                'quantity'    => 1,
                'expired_at'  => '2026-01-01 00:00:00',
                'status'      => 'Available',
                'is_approved' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'user_id'     => 3,
                'title'       => 'Bàn chải đánh răng trẻ em (5 chiếc)',
                'description' => 'Mua dư, còn mới 100%, phù hợp trẻ 3-7 tuổi.',
                'category_id' => 2,
                'location'    => json_encode(['lat' => 16.047079, 'lng' => 108.206230]),
                'quantity'    => 5,
                'expired_at'  => null,
                'status'      => 'Available',
                'is_approved' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'user_id'     => 4,
                'title'       => 'Trứng gà ta (10 quả)',
                'description' => 'Còn tươi, hạn dùng 5 ngày nữa. Giao trong khu vực gần.',
                'category_id' => 1,
                'location'    => json_encode(['lat' => 10.776889, 'lng' => 106.700806]),
                'quantity'    => 10,
                'expired_at'  => Carbon::now()->addDays(5)->toDateTimeString(),
                'status'      => 'Available',
                'is_approved' => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'user_id'     => 5,
                'title'       => 'Chăn mỏng mùa hè',
                'description' => 'Chăn sạch, không rách, dùng được ngay.',
                'category_id' => 2,
                'location'    => json_encode(['lat' => 10.820000, 'lng' => 106.630000]),
                'quantity'    => 1,
                'expired_at'  => null,
                'status'      => 'Reserved',
                'is_approved' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'user_id'     => 6,
                'title'       => 'Sữa bột trẻ em Friso Gold 900g',
                'description' => 'Hộp chưa mở nắp, hạn sử dụng 08/2025.',
                'category_id' => 1,
                'location'    => json_encode(['lat' => 21.030000, 'lng' => 105.800000]),
                'quantity'    => 1,
                'expired_at'  => '2025-08-01 00:00:00',
                'status'      => 'Available',
                'is_approved' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'user_id'     => 7,
                'title'       => 'Hộp cơm giữ nhiệt',
                'description' => 'Đã qua sử dụng nhẹ, còn dùng tốt.',
                'category_id' => 2,
                'location'    => json_encode(['lat' => 10.870000, 'lng' => 106.800000]),
                'quantity'    => 1,
                'expired_at'  => null,
                'status'      => 'Taken',
                'is_approved' => 1,
                'created_at'  => now()->subDays(2),
                'updated_at'  => now()->subDays(1),
                'deleted_at'  => now()->subDay(),
            ],
            [
                'user_id'     => 8,
                'title'       => 'Thịt heo đông lạnh (500g)',
                'description' => 'Vẫn còn đông lạnh, tặng lại do đổi món ăn.',
                'category_id' => 1,
                'location'    => json_encode(['lat' => 10.790000, 'lng' => 106.700000]),
                'quantity'    => 1,
                'expired_at'  => Carbon::now()->addDays(3)->toDateTimeString(),
                'status'      => 'Available',
                'is_approved' => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'user_id'     => 9,
                'title'       => 'Đèn bàn học sinh',
                'description' => 'Đèn LED còn sáng tốt, có thể điều chỉnh góc.',
                'category_id' => 2,
                'location'    => json_encode(['lat' => 16.060000, 'lng' => 108.220000]),
                'quantity'    => 1,
                'expired_at'  => null,
                'status'      => 'Available',
                'is_approved' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'user_id'     => 10,
                'title'       => 'Áo khoác mỏng nam size M',
                'description' => 'Áo còn mới 80%, không rách, tặng ai cần.',
                'category_id' => 2,
                'location'    => json_encode(['lat' => 10.810000, 'lng' => 106.640000]),
                'quantity'    => 1,
                'expired_at'  => null,
                'status'      => 'Available',
                'is_approved' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
        ]);
    }
}
