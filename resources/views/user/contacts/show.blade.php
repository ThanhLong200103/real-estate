<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat với {{ (auth()->id() == $contact->user_one_id) ? $contact->userTwo->name : $contact->userOne->name }} - EstateHub</title>
    
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
            --white: #ffffff;
        }

        body { 
            background-color: var(--light-bg); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Khung chat chính */
        .chat-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            height: calc(100vh - 40px);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Header của Chat */
        .chat-header {
            background: var(--dark);
            background-image: linear-gradient(rgba(108, 92, 231, 0.1), rgba(0, 0, 0, 0.1)), url('https://www.transparenttextures.com/patterns/cubes.png');
            padding: 20px 25px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .chat-avatar {
            width: 50px; height: 50px;
            background: var(--primary);
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 20px;
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
        }

        /* Thanh thông tin BĐS đang chat */
        .post-preview {
            background: #f1f0ff;
            padding: 12px 20px;
            border-bottom: 1px solid rgba(108, 92, 231, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .post-img {
            width: 55px; height: 55px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid white;
        }

        /* Nội dung tin nhắn */
        #chat-box {
            flex-grow: 1;
            overflow-y: auto;
            padding: 25px;
            background-color: #fcfcff;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .msg-bubble {
            max-width: 75%;
            padding: 12px 18px;
            border-radius: 20px;
            font-size: 14px;
            line-height: 1.6;
            position: relative;
            font-weight: 500;
        }

        .msg-sent {
            align-self: flex-end;
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .msg-received {
            align-self: flex-start;
            background: white;
            color: var(--dark);
            border-bottom-left-radius: 4px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            border: 1px solid #eee;
        }

        .msg-time {
            font-size: 9px;
            opacity: 0.7;
            display: block;
            margin-top: 5px;
            text-transform: uppercase;
        }

        /* Ô nhập liệu */
        .chat-footer {
            padding: 20px 25px;
            background: white;
            border-top: 1px solid #f0f0f0;
        }

        .input-group-custom {
            display: flex;
            gap: 10px;
            background: #f8faff;
            padding: 8px;
            border-radius: 20px;
            border: 1px solid #eee;
        }

        .input-group-custom input {
            border: none;
            background: transparent;
            padding: 10px 15px;
            flex-grow: 1;
            outline: none;
            font-size: 14px;
        }

        .btn-send {
            width: 45px; height: 45px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 15px;
            transition: 0.3s;
            display: flex; align-items: center; justify-content: center;
        }

        .btn-send:hover {
            transform: scale(1.05);
            background: #5a4bcf;
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
        }

        /* Custom Scrollbar */
        #chat-box::-webkit-scrollbar { width: 5px; }
        #chat-box::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
    </style>
</head>
<body>

@php
    $otherUser = (auth()->id() == $contact->user_one_id) ? $contact->userTwo : $contact->userOne;
    
    $getPostImage = function($post) {
        $firstImg = $post->images->first();
        if (!$firstImg) return 'https://placehold.co/100x100?text=No+Image';
        $path = $firstImg->image_url ?? $firstImg->image_path;
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        return asset('storage/' . $path);
    };
@endphp

<div class="container-fluid h-100">
    <div class="chat-container">
        
        <div class="chat-header">
            <div class="user-info">
                <a href="{{ route('contacts.index') }}" class="text-white me-2">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <div class="chat-avatar">
                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                </div>
                <div>
                    <h6 class="m-0 fw-800">{{ $otherUser->name }}</h6>
                    <small class="opacity-75"><i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i> Trực tuyến</small>
                </div>
            </div>
            <div class="nav-actions bg-transparent p-0 border-0 shadow-none">
                <a href="{{ route('home') }}" class="text-white opacity-75 hover-opacity-100"><i class="fas fa-home"></i></a>
            </div>
        </div>

        @if($contact->salePost)
        <div class="post-preview">
            <img src="{{ $getPostImage($contact->salePost) }}" class="post-img shadow-sm">
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-800 text-dark text-truncate small uppercase">{{ $contact->salePost->title }}</div>
                <div class="text-primary fw-bold small">{{ number_format($contact->salePost->price) }} <span style="font-size: 10px">VND</span></div>
            </div>
            <a href="{{ route('create-sale-show', $contact->salePost->id) }}" class="btn btn-sm btn-white border rounded-pill px-3 fw-bold small shadow-sm">
                Chi tiết
            </a>
        </div>
        @endif

        <div id="chat-box">
            @foreach($contact->messages as $message)
                <div class="msg-bubble {{ $message->sender_id == auth()->id() ? 'msg-sent' : 'msg-received' }}">
                    {{ $message->message }}
                    <span class="msg-time">{{ $message->created_at->format('H:i') }}</span>
                </div>
            @endforeach
        </div>

        <div class="chat-footer">
            <form action="{{ route('contacts.send', $contact->id) }}" method="POST">
                @csrf
                <div class="input-group-custom">
                    <input type="text" name="message" autocomplete="off" placeholder="Hỏi về bất động sản này..." required>
                    <button type="submit" class="btn-send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tự động cuộn xuống cuối khi load trang
    const chatBox = document.getElementById('chat-box');
    window.onload = () => {
        chatBox.scrollTop = chatBox.scrollHeight;
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>