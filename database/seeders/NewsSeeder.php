<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy ID của Admin hoặc User đầu tiên để làm tác giả
        $author = User::first() ?: User::factory()->create();

        $newsData = [
            [
                'title' => 'Thị trường Bất động sản năm 2026: Xu hướng dịch chuyển về vùng ven',
                'description' => 'Trong bối cảnh quỹ đất trung tâm ngày càng hạn hẹp, các nhà đầu tư đang có xu hướng tìm kiếm cơ hội tại các tỉnh lân cận...',
            ],
            [
                'title' => 'Lãi suất ngân hàng giảm mạnh, cơ hội cho người mua nhà lần đầu',
                'description' => 'Nhiều ngân hàng lớn vừa công bố gói vay ưu đãi chỉ từ 5%/năm, giúp giấc mơ an cư của nhiều gia đình trẻ gần hơn bao giờ hết...',
            ],
            [
                'title' => '5 lưu ý phong thủy khi chọn mua căn hộ chung cư bạn cần biết',
                'description' => 'Hướng ban công, vị trí cửa chính và cách sắp xếp nội thất phòng bếp đóng vai trò quan trọng trong việc thu hút tài lộc...',
            ],
        ];

        foreach ($newsData as $item) {
            News::create([
                'author_id'   => $author->id, // Khớp với foreignId('author_id')
                'title'       => $item['title'], // Khớp với string('title', 300)
                'description' => $item['description'], // Khớp với text('description')
                'status'      => 1, // Khớp với boolean('status')
            ]);
        }
    }
}