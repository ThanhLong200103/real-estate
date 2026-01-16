<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class NewsSeederfinal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsImages = [
            "https://images.pexels.com/photos/8292794/pexels-photo-8292794.jpeg",
            "https://images.pexels.com/photos/6343/pexels-photo-6343.jpeg",
            "https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg",
            "https://images.pexels.com/photos/11815873/pexels-photo-11815873.jpeg",
            "https://images.pexels.com/photos/32170343/pexels-photo-32170343.jpeg",
            "https://images.pexels.com/photos/17095081/pexels-photo-17095081.jpeg",
            "https://images.pexels.com/photos/9317443/pexels-photo-9317443.jpeg",
            "https://images.pexels.com/photos/33326698/pexels-photo-33326698.jpeg"
        ];

        // 1️⃣ Insert news
        DB::table('news')->insert([
            [   'author_id' => 1,
                'title' => 'Thị trường bất động sản Việt Nam năm 2025: Cơ hội và thách thức',
                'description' => $this->content1(),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [   'author_id' => 1,
                'title' => 'Xu hướng đầu tư bất động sản vùng ven và tiềm năng tăng trưởng dài hạn',
                'description' => $this->content2(),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [   'author_id' => 1,
                'title' => 'Nhà ở xã hội: Giải pháp cho bài toán an cư của người thu nhập thấp',
                'description' => $this->content3(),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [   'author_id' => 1,
                'title' => 'Bất động sản xanh và xu hướng phát triển bền vững trong tương lai',
                'description' => $this->content4(),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [   'author_id' => 1,
                'title' => 'Những lưu ý pháp lý quan trọng khi mua bán bất động sản',
                'description' => $this->content5(), 
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // 2️⃣ Lấy ID các news vừa insert
        $newsIds = DB::table('news')->orderBy('id', 'desc')->limit(5)->pluck('id');

        // 3️⃣ Seed mỗi news 3 ảnh
        foreach ($newsIds as $newsId) {
            $randomImages = collect($newsImages)->shuffle()->take(3);

            foreach ($randomImages as $img) {
                DB::table('news_images')->insert([
                    'news_id' => $newsId,
                    'image_url' => $img,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }


    private function content1()
    {
        return str_repeat(
            "Thị trường bất động sản Việt Nam trong năm 2025 đang trải qua giai đoạn chuyển mình mạnh mẽ với nhiều yếu tố tác động đan xen. 
            Sau thời kỳ trầm lắng do ảnh hưởng của lạm phát, chính sách tín dụng và những biến động kinh tế toàn cầu, thị trường đang dần hồi phục 
            với các tín hiệu tích cực từ cả phía cung và cầu. Các chuyên gia nhận định rằng niềm tin của nhà đầu tư đang từng bước được cải thiện, 
            đặc biệt là ở phân khúc nhà ở thực và bất động sản phục vụ nhu cầu an sinh xã hội. 
            Song song đó, thách thức về pháp lý, nguồn vốn và quy hoạch vẫn là những vấn đề cần được giải quyết triệt để. 
            Việc điều chỉnh chính sách vĩ mô một cách linh hoạt sẽ đóng vai trò then chốt trong việc ổn định và phát triển thị trường bất động sản 
            trong giai đoạn sắp tới. ",
            8
        );
    }

    private function content2()
    {
        return str_repeat(
            "Trong những năm gần đây, bất động sản vùng ven các thành phố lớn như Hà Nội và TP.HCM đang thu hút sự quan tâm đặc biệt của nhà đầu tư. 
            Giá đất tại khu vực trung tâm ngày càng tăng cao khiến xu hướng dịch chuyển ra vùng ven trở nên rõ rệt. 
            Các khu đô thị vệ tinh với hạ tầng giao thông được đầu tư đồng bộ đã mở ra nhiều cơ hội phát triển mới. 
            Tuy nhiên, nhà đầu tư cần có cái nhìn dài hạn, tránh tâm lý đầu cơ ngắn hạn dẫn đến rủi ro tài chính. 
            Việc nghiên cứu kỹ quy hoạch, pháp lý và tiềm năng phát triển của khu vực là yếu tố then chốt quyết định hiệu quả đầu tư. ",
            9
        );
    }

    private function content3()
    {
        return str_repeat(
            "Nhà ở xã hội đang được xem là giải pháp quan trọng nhằm giải quyết nhu cầu nhà ở cho người thu nhập thấp và công nhân tại các khu công nghiệp. 
            Trong bối cảnh đô thị hóa diễn ra nhanh chóng, áp lực về chỗ ở ngày càng gia tăng, việc phát triển các dự án nhà ở xã hội trở nên cấp thiết hơn bao giờ hết. 
            Nhà nước đã và đang ban hành nhiều chính sách ưu đãi nhằm khuyến khích doanh nghiệp tham gia đầu tư vào phân khúc này. 
            Tuy nhiên, vẫn còn tồn tại nhiều khó khăn liên quan đến quỹ đất, thủ tục hành chính và nguồn vốn. 
            Để nhà ở xã hội thực sự phát huy hiệu quả, cần có sự phối hợp chặt chẽ giữa các cơ quan quản lý, doanh nghiệp và người dân. ",
            9
        );
    }

    private function content4()
    {
        return str_repeat(
            "Bất động sản xanh là xu hướng tất yếu trong bối cảnh biến đổi khí hậu và yêu cầu phát triển bền vững ngày càng được đề cao. 
            Các dự án bất động sản xanh không chỉ chú trọng đến yếu tố môi trường mà còn hướng tới việc nâng cao chất lượng sống cho cư dân. 
            Việc sử dụng vật liệu thân thiện với môi trường, tối ưu hóa năng lượng và không gian xanh đang trở thành tiêu chí quan trọng trong thiết kế. 
            Dù chi phí đầu tư ban đầu có thể cao hơn, nhưng về lâu dài, bất động sản xanh mang lại nhiều lợi ích kinh tế và xã hội. ",
            10
        );
    }

    private function content5()
    {
        return str_repeat(
            "Pháp lý luôn là yếu tố then chốt trong các giao dịch mua bán bất động sản. 
            Việc thiếu hiểu biết hoặc chủ quan trong khâu kiểm tra giấy tờ có thể dẫn đến nhiều rủi ro nghiêm trọng cho người mua. 
            Trước khi quyết định giao dịch, cần xác minh rõ ràng quyền sở hữu, tình trạng quy hoạch và các nghĩa vụ tài chính liên quan đến bất động sản. 
            Ngoài ra, việc tham khảo ý kiến của luật sư hoặc chuyên gia pháp lý là điều cần thiết để đảm bảo quyền lợi hợp pháp. 
            Sự cẩn trọng và minh bạch trong pháp lý sẽ góp phần xây dựng một thị trường bất động sản lành mạnh và bền vững. ",
            10
        );
    }
}
