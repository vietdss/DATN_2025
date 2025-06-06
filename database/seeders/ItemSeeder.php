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
                'user_id' => 1,
                'title' => 'Mì tôm Hảo Hảo (thùng 30 gói)',
                'description' => 'Chia sẻ thùng mì còn nguyên, hạn sử dụng đến 12/2025.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.762622, 'lng' => 106.660172]),
                'quantity' => 1,
                'expired_at' => '2025-12-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 2,
                'title' => 'Nước mắm truyền thống 500ml',
                'description' => 'Chưa mở nắp, còn nguyên, tặng cho ai cần.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 21.028511, 'lng' => 105.804817]),
                'quantity' => 1,
                'expired_at' => '2026-01-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 3,
                'title' => 'Bàn chải đánh răng trẻ em (5 chiếc)',
                'description' => 'Mua dư, còn mới 100%, phù hợp trẻ 3-7 tuổi.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 16.047079, 'lng' => 108.206230]),
                'quantity' => 5,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 4,
                'title' => 'Trứng gà ta (10 quả)',
                'description' => 'Còn tươi, hạn dùng 5 ngày nữa. Giao trong khu vực gần.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.776889, 'lng' => 106.700806]),
                'quantity' => 10,
                'expired_at' => Carbon::now()->addDays(5)->toDateTimeString(),
                'status' => 'Available',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 5,
                'title' => 'Chăn mỏng mùa hè',
                'description' => 'Chăn sạch, không rách, dùng được ngay.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.820000, 'lng' => 106.630000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Reserved',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 6,
                'title' => 'Sữa bột trẻ em Friso Gold 900g',
                'description' => 'Hộp chưa mở nắp, hạn sử dụng 08/2025.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 21.030000, 'lng' => 105.800000]),
                'quantity' => 1,
                'expired_at' => '2025-08-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 7,
                'title' => 'Hộp cơm giữ nhiệt',
                'description' => 'Đã qua sử dụng nhẹ, còn dùng tốt.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.870000, 'lng' => 106.800000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Taken',
                'is_approved' => 1,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
                'deleted_at' => now()->subDay(),
            ],
            [
                'user_id' => 8,
                'title' => 'Thịt heo đông lạnh (500g)',
                'description' => 'Vẫn còn đông lạnh, tặng lại do đổi món ăn.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.790000, 'lng' => 106.700000]),
                'quantity' => 1,
                'expired_at' => Carbon::now()->addDays(3)->toDateTimeString(),
                'status' => 'Available',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 9,
                'title' => 'Đèn bàn học sinh',
                'description' => 'Đèn LED còn sáng tốt, có thể điều chỉnh góc.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 16.060000, 'lng' => 108.220000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 10,
                'title' => 'Áo khoác mỏng nam size M',
                'description' => 'Áo còn mới 80%, không rách, tặng ai cần.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.810000, 'lng' => 106.640000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],

            // 10 bản ghi mở rộng dưới đây
            [
                'user_id' => 1,
                'title' => 'Gạo thơm ST25 (5kg)',
                'description' => 'Gạo mới đóng gói, chưa sử dụng.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.775000, 'lng' => 106.700000]),
                'quantity' => 1,
                'expired_at' => '2025-11-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 2,
                'title' => 'Balo học sinh',
                'description' => 'Balo còn tốt, ngăn kéo khoá chắc chắn.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.790000, 'lng' => 106.690000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 3,
                'title' => 'Kem đánh răng P/S (tuýp lớn)',
                'description' => 'Còn mới, chưa bóp, mua dư nên chia sẻ.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.780000, 'lng' => 106.660000]),
                'quantity' => 1,
                'expired_at' => '2025-09-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 4,
                'title' => 'Mũ bảo hiểm 3/4 đầu',
                'description' => 'Đã qua sử dụng nhưng còn chắc chắn.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.800000, 'lng' => 106.660000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Reserved',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 5,
                'title' => 'Sữa tươi tiệt trùng (lốc 4 hộp)',
                'description' => 'Còn hạn sử dụng, bảo quản mát.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.810000, 'lng' => 106.680000]),
                'quantity' => 4,
                'expired_at' => '2025-07-15 00:00:00',
                'status' => 'Available',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 6,
                'title' => 'Bình nước giữ nhiệt inox 500ml',
                'description' => 'Chưa dùng, còn nguyên hộp.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.820000, 'lng' => 106.690000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 7,
                'title' => 'Giày thể thao size 41',
                'description' => 'Đã sử dụng nhẹ, còn rất mới.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.850000, 'lng' => 106.630000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Taken',
                'is_approved' => 1,
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 8,
                'title' => 'Bánh quy bơ Danisa (hộp thiếc)',
                'description' => 'Chia sẻ bánh còn mới nguyên.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.860000, 'lng' => 106.640000]),
                'quantity' => 1,
                'expired_at' => '2025-12-25 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 9,
                'title' => 'Áo mưa bộ 2 lớp',
                'description' => 'Áo chưa dùng, còn gói nilon.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.820000, 'lng' => 106.610000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Reserved',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 10,
                'title' => 'Chai dầu ăn Tường An 1L',
                'description' => 'Còn nguyên, hạn dùng xa.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.770000, 'lng' => 106.680000]),
                'quantity' => 1,
                'expired_at' => '2026-01-20 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 1,
                'title' => 'Khẩu trang y tế 4 lớp (hộp 50 cái)',
                'description' => 'Hộp mới, chưa mở, chia sẻ lại.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.765000, 'lng' => 106.683000]),
                'quantity' => 1,
                'expired_at' => '2026-03-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 2,
                'title' => 'Nước suối Lavie 1.5L (thùng 12 chai)',
                'description' => 'Chưa khui thùng, hạn dùng dài.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.770000, 'lng' => 106.680000]),
                'quantity' => 1,
                'expired_at' => '2025-12-31 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 3,
                'title' => 'Vở học sinh 96 trang (10 quyển)',
                'description' => 'Mới 100%, còn bọc nilon.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.800000, 'lng' => 106.650000]),
                'quantity' => 10,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 4,
                'title' => 'Mì Ý khô 500g',
                'description' => 'Chưa sử dụng, còn nguyên tem.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.780000, 'lng' => 106.700000]),
                'quantity' => 1,
                'expired_at' => '2026-02-10 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 5,
                'title' => 'Nồi cơm điện Sharp cũ',
                'description' => 'Đã dùng, còn hoạt động tốt.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.850000, 'lng' => 106.640000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Reserved',
                'is_approved' => 1,
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 6,
                'title' => 'Sữa đặc Ông Thọ (2 lon)',
                'description' => 'Chia sẻ 2 lon chưa mở nắp.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.765000, 'lng' => 106.650000]),
                'quantity' => 2,
                'expired_at' => '2026-04-30 00:00:00',
                'status' => 'Available',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 7,
                'title' => 'Bộ bàn ghế học sinh gỗ ép',
                'description' => 'Còn chắc chắn, ít trầy xước.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.800000, 'lng' => 106.670000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Taken',
                'is_approved' => 1,
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
                'deleted_at' => now()->subDay(),
            ],
            [
                'user_id' => 8,
                'title' => 'Bánh mì gối (2 ổ)',
                'description' => 'Còn mới, nên dùng trong 2 ngày.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.820000, 'lng' => 106.680000]),
                'quantity' => 2,
                'expired_at' => Carbon::now()->addDays(2)->toDateTimeString(),
                'status' => 'Available',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 9,
                'title' => 'Máy ép trái cây mini',
                'description' => 'Đã sử dụng 3 lần, còn tốt.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.810000, 'lng' => 106.660000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Reserved',
                'is_approved' => 1,
                'created_at' => now()->subDays(3),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 10,
                'title' => 'Sữa chua Vinamilk (6 hộp)',
                'description' => 'Hạn dùng còn 1 tuần, chia sẻ kịp ăn.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.780000, 'lng' => 106.700000]),
                'quantity' => 6,
                'expired_at' => Carbon::now()->addDays(7)->toDateTimeString(),
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],

            // 10 bản ghi tiếp theo, tiếp tục user_id từ 1-10
            [
                'user_id' => 1,
                'title' => 'Tấm trải bàn học sinh',
                'description' => 'Mới 95%, còn sạch, không rách.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.765000, 'lng' => 106.680000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 2,
                'title' => 'Nho khô Úc 250g',
                'description' => 'Chia lại 1 túi chưa mở, hạn dài.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.770000, 'lng' => 106.675000]),
                'quantity' => 1,
                'expired_at' => '2026-01-10 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 3,
                'title' => 'Kẹp tóc nữ dễ thương',
                'description' => 'Bộ 5 cái, còn mới chưa dùng.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.800000, 'lng' => 106.660000]),
                'quantity' => 5,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 4,
                'title' => 'Bơ lạt Anchor 227g',
                'description' => 'Còn nguyên, dùng nướng bánh.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.790000, 'lng' => 106.700000]),
                'quantity' => 1,
                'expired_at' => '2025-10-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 5,
                'title' => 'Túi xách nữ màu be',
                'description' => 'Đã sử dụng, còn sạch sẽ.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.850000, 'lng' => 106.620000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Reserved',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 6,
                'title' => 'Thanh socola KitKat (hộp 10 thanh)',
                'description' => 'Hạn dùng còn 2 tháng, chưa bóc.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.810000, 'lng' => 106.690000]),
                'quantity' => 10,
                'expired_at' => Carbon::now()->addMonths(2)->toDateTimeString(),
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 7,
                'title' => 'Áo sơ mi trắng nam size L',
                'description' => 'Mới 90%, không ố, không rách.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.810000, 'lng' => 106.690000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 8,
                'title' => 'Gói rong biển ăn liền Hàn Quốc',
                'description' => 'Mới nguyên bao bì, ăn vặt tiện lợi.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.790000, 'lng' => 106.670000]),
                'quantity' => 1,
                'expired_at' => '2025-09-10 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 9,
                'title' => 'Túi đựng laptop 14 inch',
                'description' => 'Chưa dùng lần nào, còn nguyên tag.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.765000, 'lng' => 106.640000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 10,
                'title' => 'Thùng bánh tráng trộn mini',
                'description' => 'Tự làm ăn không hết, chia sẻ lại.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.775000, 'lng' => 106.680000]),
                'quantity' => 5,
                'expired_at' => Carbon::now()->addDays(1)->toDateTimeString(),
                'status' => 'Available',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 1,
                'title' => 'Bình giữ nhiệt Lock&Lock 500ml',
                'description' => 'Còn mới, giữ nhiệt tốt.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.770000, 'lng' => 106.680000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 2,
                'title' => 'Sữa tươi không đường TH True Milk (1 lít)',
                'description' => 'Chia sẻ vì mua dư, còn nguyên hộp.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.775000, 'lng' => 106.685000]),
                'quantity' => 1,
                'expired_at' => Carbon::now()->addDays(5)->toDateTimeString(),
                'status' => 'Available',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 3,
                'title' => 'Áo khoác gió Uniqlo size M',
                'description' => 'Đã mặc vài lần, còn mới 95%.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.800000, 'lng' => 106.660000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Reserved',
                'is_approved' => 1,
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 4,
                'title' => 'Hộp cá ngừ ngâm dầu (3 hộp)',
                'description' => 'Chưa mở, hạn dài.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.790000, 'lng' => 106.670000]),
                'quantity' => 3,
                'expired_at' => '2026-05-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 5,
                'title' => 'Đèn bàn học LED',
                'description' => 'Còn dùng tốt, có nhiều chế độ sáng.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.850000, 'lng' => 106.620000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now()->subDays(3),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 6,
                'title' => 'Bánh quy Danisa (hộp thiếc)',
                'description' => 'Mới, chưa bóc, phù hợp tặng hoặc dùng Tết.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.760000, 'lng' => 106.670000]),
                'quantity' => 1,
                'expired_at' => '2026-01-01 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 7,
                'title' => 'Mũ bảo hiểm 3/4 đầu màu đen',
                'description' => 'Còn mới 90%, không nứt vỡ.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.800000, 'lng' => 106.660000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 8,
                'title' => 'Thùng nước ngọt Pepsi lon (24 lon)',
                'description' => 'Chia lại do mua nhầm vị.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.770000, 'lng' => 106.700000]),
                'quantity' => 24,
                'expired_at' => '2025-11-30 00:00:00',
                'status' => 'Available',
                'is_approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 9,
                'title' => 'Bộ sách giáo khoa lớp 9',
                'description' => 'Bộ sách đầy đủ, sạch đẹp.',
                'category_id' => 2,
                'location' => json_encode(['lat' => 10.815000, 'lng' => 106.660000]),
                'quantity' => 1,
                'expired_at' => '2025-06-07 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'user_id' => 10,
                'title' => 'Hộp bánh Oreo (3 gói)',
                'description' => 'Hạn còn dài, mua dư.',
                'category_id' => 1,
                'location' => json_encode(['lat' => 10.765000, 'lng' => 106.670000]),
                'quantity' => 3,
                'expired_at' => '2026-03-15 00:00:00',
                'status' => 'Available',
                'is_approved' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }
}
