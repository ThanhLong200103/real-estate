<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin nhắn của tôi - EstateHub</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary: #6c5ce7;
            --primary-light: #a29bfe;
            --dark: #2d3436;
            --light-bg: #f8faff;
            --success: #00b894;
        }


        
        body { 
            background-color: var(--light-bg); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--dark);
        }

        /* Header Đồng Bộ Hero-style */
        /* Header Đồng Bộ Hero-style */
.hero-banner-mini {
    background-color: #2d3436; /* Màu nền dự phòng */
    background-image: linear-gradient(rgba(45, 52, 54, 0.85), rgba(45, 52, 54, 0.85)), 
                      url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1546&auto=format&fit=crop');
    background-size: cover;
    background-position: center center;
    background-attachment: scroll; /* Thay đổi thành fixed nếu bạn muốn hiệu ứng parallax */
    padding: 60px 0 100px 0;
    color: white;
    margin-bottom: -50px;
    position: relative;
}

        .nav-actions {
            display: flex;
            gap: 12px;
            background: rgba(255,255,255,0.1);
            padding: 8px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }

        .btn-custom {
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-glass { background: rgba(255,255,255,0.2); color: white; }
        .btn-glass:hover { background: white; color: var(--primary); }
        .btn-primary-custom { background: var(--primary); color: white; }

        /* Contact Cards */
        .contact-container {
            position: relative;
            z-index: 10;
        }

        .contact-card { 
            background: white;
            border-radius: 24px; 
            border: 1px solid rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 16px;
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        }

        .contact-card:hover { 
            transform: translateX(8px); 
            box-shadow: 0 15px 35px rgba(108, 92, 231, 0.1); 
            border-color: var(--primary-light);
        }
        
        .avatar-circle {
            width: 65px; height: 65px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 24px;
            box-shadow: 0 8px 16px rgba(108, 92, 231, 0.2);
            flex-shrink: 0;
        }
        
        .post-badge {
            font-size: 11px;
            font-weight: 700;
            background: #f1f2f6;
            padding: 6px 12px;
            border-radius: 10px;
            color: #57606f;
            display: inline-flex;
            align-items: center;
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 8px;
        }

        .last-msg { 
            color: #636e72; 
            font-size: 14px; 
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .unread-indicator {
            width: 12px; height: 12px;
            background: #ff4757;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 4px rgba(255, 71, 87, 0.1);
        }

        .empty-state {
            background: white;
            border-radius: 30px;
            padding: 80px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body>

<header class="hero-banner-mini">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="fw-800 m-0 text-white" style="cursor: pointer" onclick="window.location='{{ route('home') }}'">
                ESTATE<span class="text-info">HUB</span>
            </h2>
            
            <div class="nav-actions">
                <a href="{{ route('home') }}" class="btn-custom btn-glass"><i class="fas fa-home"></i> Trang chủ</a>
                <a href="{{ route('contacts.index') }}" class="btn-custom btn-primary-custom"><i class="fas fa-comment-dots"></i> Tin nhắn</a>
                <a href="{{ route('favorite.index') }}" class="btn-custom btn-glass"><i class="fas fa-heart text-danger"></i></a>
                <a href="{{ route('user.report.index') }}" class="btn-custom btn-glass"><i class="fas fa-flag"></i></a>
                
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn-custom btn-glass border-0"><i class="fas fa-power-off"></i></button>
                </form>
            </div>
        </div>
        <h1 class="fw-800 mb-2">Hộp thư tin nhắn</h1>
        <p class="opacity-75 fw-500">Kết nối trực tiếp với người mua và người bán</p>
    </div>
</header>

<main class="container contact-container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            @if($contacts->isEmpty())
                <div class="empty-state">
                    <div class="mb-4">
                        <i class="fas fa-comment-slash display-1 text-muted opacity-25"></i>
                    </div>
                    <h3 class="fw-800">Chưa có cuộc hội thoại nào</h3>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 450px;">
                        Các tin nhắn của bạn với người đăng tin sẽ xuất hiện tại đây. Hãy bắt đầu hỏi về ngôi nhà bạn thích!
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-primary-custom px-5 py-3 shadow-lg">
                        <i class="fas fa-search me-2"></i> Đi tìm bất động sản
                    </a>
                </div>
            @else
                @foreach($contacts as $contact)
                    @php
                        $otherUser = (auth()->id() == $contact->user_one_id) ? $contact->userTwo : $contact->userOne;
                        $lastMessage = $contact->messages->first();
                        $post = $contact->salePost;
                    @endphp
                    
                    <a href="{{ route('contacts.show', $contact->id) }}" class="contact-card">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3">
                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            </div>

                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="fw-800 m-0 text-dark">{{ $otherUser->name }}</h5>
                                    <small class="text-muted fw-600" style="font-size: 11px;">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                    </small>
                                </div>

                                @if($post)
                                    <div>
                                        <span class="post-badge">
                                            <i class="fas fa-home me-1 text-primary"></i>
                                            {{ $post->title }}
                                        </span>
                                    </div>
                                @endif

                                <div class="last-msg">
                                    @if($lastMessage)
                                        <span class="{{ $lastMessage->sender_id == auth()->id() ? '' : 'fw-bold text-dark' }}">
                                            @if($lastMessage->sender_id == auth()->id())
                                                <i class="fas fa-reply me-1 opacity-50"></i>Bạn: 
                                            @endif
                                            {{ $lastMessage->message }}
                                        </span>
                                    @else
                                        <span class="fst-italic text-muted small">Chưa có tin nhắn...</span>
                                    @endif
                                </div>
                            </div>

                            <div class="ms-3 d-flex align-items-center">
                                {{-- Giả sử bạn có logic kiểm tra tin nhắn chưa đọc --}}
                                {{-- @if($lastMessage && !$lastMessage->is_read && $lastMessage->sender_id != auth()->id())
                                    <div class="unread-indicator"></div>
                                @endif --}}
                                <i class="fas fa-chevron-right text-muted opacity-25 ms-3"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</main>

@include('layouts.footer')