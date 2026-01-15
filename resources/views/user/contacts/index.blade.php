<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin nhắn của tôi - EstateHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root { --primary-color: #6c5ce7; --bg-light: #f8faff; }
        body { background-color: var(--bg-light); font-family: 'Plus Jakarta Sans', sans-serif; color: #2d3436; }
        
        .contact-card { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            border: 1px solid #edf2f7; 
            border-radius: 20px; 
            margin-bottom: 16px;
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            position: relative;
            overflow: hidden;
        }
        .contact-card:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 12px 24px rgba(108, 92, 231, 0.1); 
            background-color: #fff;
            border-color: var(--primary-color);
        }
        
        .avatar-circle {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 22px;
            box-shadow: 0 4px 10px rgba(108, 92, 231, 0.2);
        }
        
        .last-msg { color: #636e72; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        
        .post-badge {
            font-size: 11px;
            background: #f1f2f6;
            padding: 4px 10px;
            border-radius: 8px;
            color: #57606f;
            display: inline-flex;
            align-items: center;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .unread-indicator {
            width: 10px; height: 10px;
            background: #ff4757;
            border-radius: 50%;
            position: absolute;
            right: 20px; top: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 0 4px rgba(255, 71, 87, 0.1);
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 m-0" style="letter-spacing: -1px;">Hộp thư tin nhắn</h2>
            <p class="text-muted small m-0">Quản lý các cuộc hội thoại mua bán của bạn</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-white shadow-sm rounded-pill px-4 border">
            <i class="fas fa-home me-2 text-primary"></i> Trang chủ
        </a>
    </div>

    @if($contacts->isEmpty())
        <div class="text-center py-5 bg-white rounded-5 shadow-sm border border-dashed">
            <div class="mb-4">
                <i class="fas fa-envelope-open-text fa-4x text-light"></i>
            </div>
            <h4 class="fw-bold">Hộp thư trống</h4>
            <p class="text-muted px-4">Hãy bắt đầu nhắn tin với người bán để tìm kiếm ngôi nhà mơ ước của bạn.</p>
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">Tìm bất động sản ngay</a>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @foreach($contacts as $contact)
                    @php
                        $otherUser = (auth()->id() == $contact->user_one_id) ? $contact->userTwo : $contact->userOne;
                        $lastMessage = $contact->messages->first();
                        $post = $contact->salePost;
                    @endphp
                    
                    <a href="{{ route('contacts.show', $contact->id) }}" class="card contact-card shadow-sm p-3 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3">
                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            </div>

                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold m-0 text-dark">{{ $otherUser->name }}</h6>
                                    <small class="text-muted" style="font-size: 11px;">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                    </small>
                                </div>

                                @if($post)
                                    <div class="mb-2">
                                        <span class="post-badge">
                                            <i class="fas fa-home me-1 text-primary"></i>
                                            {{ $post->title }}
                                        </span>
                                    </div>
                                @endif

                                <div class="last-msg">
                                    @if($lastMessage)
                                        <span class="{{ $lastMessage->sender_id == auth()->id() ? '' : 'fw-bold text-dark' }}">
                                            {{ $lastMessage->sender_id == auth()->id() ? 'Bạn: ' : '' }}{{ $lastMessage->message }}
                                        </span>
                                    @else
                                        <span class="fst-italic text-muted small">Bắt đầu cuộc trò chuyện...</span>
                                    @endif
                                </div>
                            </div>

                            <div class="ms-3 pe-2">
                                <i class="fas fa-chevron-right text-muted opacity-25"></i>
                            </div>
                        </div>

                        {{-- @if(!$lastMessage->is_read && $lastMessage->sender_id != auth()->id())
                            <div class="unread-indicator"></div>
                        @endif --}}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>