<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root { --primary-color: #6c5ce7; --bg-light: #f8faff; }
    body { background-color: var(--bg-light); font-family: 'Plus Jakarta Sans', sans-serif; }
    
    .contact-card { 
        transition: 0.3s; 
        border: none; 
        border-radius: 16px; 
        margin-bottom: 15px;
        text-decoration: none !important;
        color: inherit !important;
        display: block;
    }
    .contact-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); background-color: #fff; }
    
    .avatar-circle {
        width: 55px; height: 55px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 20px;
    }
    
    .unread-dot { width: 10px; height: 10px; background: #e74c3c; border-radius: 50%; display: inline-block; }
    .last-msg { color: #636e72; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px; }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-800 m-0">Tin nhắn của tôi</h2>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-home me-2"></i> Trang chủ
        </a>
    </div>

    @if($contacts->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="far fa-comments fa-4x text-muted mb-3"></i>
            <p class="text-muted">Bạn chưa có cuộc hội thoại nào.</p>
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill">Khám phá ngay</a>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($contacts as $contact)
                    @php
                        // Xác định người đang nhắn tin với mình
                        $otherUser = (auth()->id() == $contact->user_one_id) ? $contact->userTwo : $contact->userOne;
                        $lastMessage = $contact->messages->first(); // Database query của bạn đã lấy tin nhắn mới nhất rồi
                    @endphp
                    
                    <a href="{{ route('contacts.show', $contact->id) }}" class="card contact-card shadow-sm p-3 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3">
                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold m-0">{{ $otherUser->name }}</h6>
                                    <small class="text-muted">
                                        {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                    </small>
                                </div>
                                <div class="last-msg">
                                    @if($lastMessage)
                                        {{ $lastMessage->sender_id == auth()->id() ? 'Bạn: ' : '' }}{{ $lastMessage->message }}
                                    @else
                                        <span class="fst-italic">Chưa có tin nhắn...</span>
                                    @endif
                                </div>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-chevron-right text-muted opacity-50"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>