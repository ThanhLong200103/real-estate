<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $rentPosts->title }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfdfe; }
        .gallery-grid { 
            display: grid; 
            grid-template-columns: 2fr 1fr; 
            grid-template-rows: 240px 240px; 
            gap: 12px; 
        }
        .gallery-item-main { grid-row: span 2; }
        .back-link-pill { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .back-link-pill:hover { 
            transform: translateX(-5px); 
            background-color: #ffffff;
            border-color: #4f46e5;
            color: #4f46e5;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-fast { animation: spin 0.8s linear infinite; }
    </style>
</head>
<body class="text-gray-900 leading-relaxed">

@php
    /**
     * LOGIC XỬ LÝ ẢNH THÔNG MINH
     */
    $convertImage = function($path) {
        if (!$path) return 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=80';
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        if (str_starts_with($path, 'images/')) return asset($path);
        return asset('storage/' . $path);
    };
    
    $imgs = $rentPosts->images;
@endphp

{{-- THANH THÔNG BÁO TRẠNG THÁI CHỜ DUYỆT --}}
@if(!$rentPosts->status)
<div class="bg-amber-500 text-white py-3 px-4 shadow-lg sticky top-0 z-50 border-b border-amber-600/20 backdrop-blur-md bg-amber-500/95">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-3 font-bold text-sm">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
                <i class="fas fa-clock text-white"></i>
            </div>
            <span>TIN ĐANG CHỜ DUYỆT: <span class="font-normal opacity-90">Chỉ bạn và Quản trị viên mới thấy tin này.</span></span>
        </div>
        
        @if(auth()->check() && strcasecmp(auth()->user()->role, 'admin') === 0)
            <form action="{{ route('approve-sale-post-admin', $rentPosts->id) }}" method="POST" class="shrink-0">
                @csrf 
                @method('PATCH')
                <button type="submit" class="bg-white text-amber-600 px-6 py-2 rounded-full hover:bg-amber-50 transition-all duration-300 shadow-md hover:shadow-lg flex items-center gap-2 active:scale-95 text-xs font-black uppercase">
                    Duyệt bài ngay
                </button>
            </form>
        @endif
    </div>
</div>
@endif

<div class="max-w-6xl mx-auto px-4 py-8">
    
    {{-- NAVIGATION --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <a href="javascript:history.back()" class="back-link-pill inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-full text-gray-600 font-bold text-sm shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Quay lại
        </a>
        <nav class="flex items-center space-x-2 text-sm font-medium">
            <a href="/" class="text-gray-400 hover:text-indigo-600 transition">Trang chủ</a>
            <span class="text-gray-300">/</span>
            <span class="text-indigo-600 font-bold">Chi tiết bài đăng</span>
        </nav>
    </div>

    {{-- HEADER INFO --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-md border border-indigo-100">Ưu tiên</span>
                    <span class="px-3 py-1 {{ $rentPosts->status ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }} text-[10px] font-black uppercase tracking-widest rounded-md border">
                        {{ $rentPosts->status ? 'Đã xác thực' : 'Đang chờ duyệt' }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">{{ $rentPosts->title }}</h1>
                <p class="mt-4 text-gray-500 flex items-center font-medium">
                    <i class="fas fa-map-marker-alt text-rose-500 mr-2"></i> {{ $rentPosts->address }}
                </p>
            </div>
            <div class="bg-white p-4 md:p-0 rounded-2xl md:bg-transparent">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mb-1">Giá thuê</p>
                <p class="text-4xl font-black text-rose-600 tracking-tighter">{{ number_format($rentPosts->price) }} <span class="text-xl font-bold">VND</span></p>
            </div>
        </div>
    </div>

    {{-- PHOTO GALLERY --}}
    <div class="gallery-grid rounded-[32px] overflow-hidden shadow-2xl mb-12 border-[6px] border-white bg-gray-100">
        <div class="gallery-item-main overflow-hidden group">
            <img src="{{ $convertImage($imgs[0]->image_url ?? null) }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                 onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=80'">
        </div>
        <div class="overflow-hidden group">
            <img src="{{ $convertImage($imgs[1]->image_url ?? ($imgs[0]->image_url ?? null)) }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                 onerror="this.src='https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'">
        </div>
        <div class="relative overflow-hidden group">
            <img src="{{ $convertImage($imgs[2]->image_url ?? ($imgs[0]->image_url ?? null)) }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                 onerror="this.src='https://images.unsplash.com/photo-1448630360428-6542e085c95e?auto=format&fit=crop&w=800&q=80'">
            @if($imgs->count() > 3)
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                    <span class="text-white font-bold underline italic">Xem tất cả {{ $imgs->count() }} ảnh</span>
                </div>
                <div class="absolute bottom-5 right-5 bg-black/70 text-white px-4 py-2 rounded-xl text-xs font-bold backdrop-blur-md border border-white/20 pointer-events-none">
                    <i class="fas fa-images mr-2"></i> +{{ $imgs->count() - 3 }} ảnh khác
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- CHI TIẾT NỘI DUNG (Trái) --}}
        <div class="lg:col-span-2">
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
                @php
                    $specs = [
                        ['icon' => 'fa-vector-square', 'label' => 'Diện tích', 'value' => $rentPosts->area . ' m²'],
                        ['icon' => 'fa-bed', 'label' => 'Phòng ngủ', 'value' => $rentPosts->bedrooms . ' PN'],
                        ['icon' => 'fa-bath', 'label' => 'Phòng tắm', 'value' => $rentPosts->bathrooms . ' WC'],
                        ['icon' => 'fa-couch', 'label' => 'Nội thất', 'value' => $rentPosts->is_furnished ? 'Đầy đủ' : 'Cơ bản'],
                    ];
                @endphp
                @foreach($specs as $spec)
                <div class="bg-white p-6 rounded-3xl border border-gray-100 text-center shadow-sm hover:shadow-md transition">
                    <i class="fas {{ $spec['icon'] }} text-indigo-500 text-2xl mb-3"></i>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">{{ $spec['label'] }}</p>
                    <p class="text-base font-black text-gray-800">{{ $spec['value'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="mb-12">
                <h3 class="text-2xl font-black mb-6 flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    Thông tin chi tiết
                </h3>
                <div class="text-gray-600 text-lg leading-[1.8] whitespace-pre-line">
                    {{ $rentPosts->description }}
                </div>
            </div>

            {{-- CẢNH BÁO --}}
            <div class="p-6 bg-slate-900 rounded-[32px] text-white flex gap-5 items-center mb-12">
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-user-shield text-indigo-400"></i>
                </div>
                <p class="text-sm text-slate-300 font-medium">
                    <strong class="text-white">Lưu ý an toàn:</strong> Tuyệt đối không đặt cọc hoặc chuyển khoản trước khi xem nhà và xác nhận pháp lý chính chủ.
                </p>
            </div>

            {{-- PHẦN BÌNH LUẬN (GUEST ĐỌC ĐƯỢC) --}}
            <div class="pt-12 border-t border-gray-100">
                <h3 class="text-2xl font-black mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    Hỏi đáp & Bình luận
                    <span class="text-sm font-medium text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                        {{ $rentPosts->comments->count() }}
                    </span>
                </h3>

                {{-- Khối nhập bình luận --}}
                @auth
                    <div class="bg-white border border-gray-100 p-6 rounded-[32px] shadow-sm mb-10 transition hover:border-indigo-100">
                        <form action="{{ route('comments.store', $rentPosts->id) }}" method="POST">
                            @csrf
                            <div class="flex gap-4">
                                <div class="shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff" 
                                         class="w-10 h-10 rounded-2xl shadow-sm">
                                </div>
                                <div class="flex-1 text-right">
                                    <textarea name="content" rows="3" required
                                              class="w-full bg-gray-50 border border-transparent focus:border-indigo-500 focus:bg-white rounded-2xl p-4 text-gray-700 outline-none transition duration-300 placeholder:text-gray-400"
                                              placeholder="Để lại câu hỏi cho chủ nhà..."></textarea>
                                    <button type="submit" 
                                            class="mt-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition duration-300 inline-flex items-center gap-2 text-sm shadow-lg shadow-indigo-100 active:scale-95">
                                        Gửi bình luận <i class="fas fa-paper-plane text-[10px]"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-indigo-50/50 border border-indigo-100/50 p-8 rounded-[32px] text-center mb-10 group transition hover:bg-indigo-50">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-lock text-indigo-500"></i>
                        </div>
                        <p class="text-indigo-900 font-bold mb-4 text-sm">Đăng nhập để đặt câu hỏi cho chủ nhà</p>
                        <a href="{{ route('login-form') }}" class="inline-block bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest px-8 py-3 rounded-full hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                            Đăng nhập ngay
                        </a>
                    </div>
                @endauth

                {{-- Danh sách bình luận --}}
                <div class="space-y-6">
                    @forelse($rentPosts->comments as $comment)
                        <div class="flex gap-4">
                            <div class="shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=f1f5f9&color=64748b" 
                                     class="w-10 h-10 rounded-2xl border border-gray-100 shadow-sm">
                            </div>
                            <div class="flex-1">
                                <div class="bg-white border border-gray-100 p-5 rounded-[24px] shadow-sm hover:shadow-md transition duration-300">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-black text-gray-900 text-sm">{{ $comment->user->name }}</h4>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-[15px] leading-relaxed">
                                        {{ $comment->content }}
                                    </p>
                                </div>
                                
                                {{-- Nút xóa (Nếu là chủ comment hoặc admin) --}}
                                @auth
                                    @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                                        <div class="mt-2 flex gap-4 px-2">
                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-[10px] font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest flex items-center gap-1 transition" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">
                                                    <i class="fas fa-trash-alt"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-gray-50/50 rounded-[32px] border border-dashed border-gray-200">
                            <i class="far fa-comments text-4xl mb-3 text-gray-300"></i>
                            <p class="text-sm font-bold text-gray-400">Chưa có bình luận nào. Hãy là người đầu tiên đặt câu hỏi!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- SIDEBAR LIÊN HỆ (Phải) --}}
        <div class="lg:col-span-1">
            <div class="sticky top-8">
                <div class="bg-white border border-gray-100 p-8 rounded-[48px] shadow-2xl shadow-indigo-100/50 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
                    
                    <div class="relative flex items-center gap-5 mb-8 pb-8 border-b border-gray-50">
                        <div class="w-16 h-16 bg-indigo-600 rounded-[22px] flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-200">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Chủ tin đăng</p>
                            <p class="text-xl font-black text-gray-900">ID: #RE-{{ $rentPosts->user_id }}</p>
                        </div>
                    </div>

                    @auth
                        @if(auth()->id() !== (int)$rentPosts->user_id)
                            <form action="{{ route('contacts.start') }}" method="POST" id="mainContactForm" onsubmit="return handleFormSubmit(this)">
                                @csrf
                                <input type="hidden" name="user_two_id" value="{{ $rentPosts->user_id }}">
                                <input type="hidden" name="sale_post_id" value="{{ $rentPosts->id }}"> 
                                
                                <button type="submit" id="submitBtn" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-5 px-6 rounded-3xl transition-all duration-300 flex items-center justify-center gap-3 shadow-xl shadow-indigo-200 group">
                                    <span id="btnText" class="flex items-center gap-2">
                                        <i class="fas fa-comment-dots text-xl group-hover:rotate-12 transition-transform"></i>
                                        Nhắn tin trao đổi
                                    </span>
                                    <div id="btnLoading" class="hidden flex items-center gap-3">
                                        <i class="fas fa-circle-notch animate-spin-fast"></i> Đang kết nối...
                                    </div>
                                </button>
                            </form>
                        @else
                            <div class="bg-gray-50 text-gray-500 p-5 rounded-3xl text-xs font-black text-center border border-gray-100 italic">
                                Đây là bài đăng của chính bạn
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login-form') }}" class="block w-full bg-slate-900 hover:bg-black text-white text-center font-bold py-5 px-6 rounded-3xl transition duration-300 shadow-xl shadow-slate-200">
                            Đăng nhập để liên hệ
                        </a>
                    @endauth

                    <a href="tel:0123456789" class="block w-full mt-4 bg-emerald-500 hover:bg-emerald-600 text-white text-center font-bold py-5 px-6 rounded-3xl transition duration-300 shadow-xl shadow-emerald-100">
                        <i class="fas fa-phone-alt mr-2 text-sm"></i> Gọi 0123.456.789
                    </a>

                    <div class="mt-8 text-center">
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-[0.2em] mb-4">Mã bài đăng: #RP-{{ $rentPosts->id }}</p>
                        <a href="#" class="text-[10px] font-black text-gray-400 hover:text-rose-500 uppercase tracking-widest transition border-b-2 border-gray-100 hover:border-rose-100 pb-1">
                            <i class="fas fa-flag mr-1"></i> Báo cáo tin này
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function handleFormSubmit(form) {
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        const submitBtn = document.getElementById('submitBtn');

        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

        return true;
    }
</script>

</body>
</html>