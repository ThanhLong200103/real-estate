
<style>
    .contact-list { max-width: 800px; margin: 40px auto; background: white; border-radius: 15px; overflow: hidden; shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .contact-item { 
        display: flex; align-items: center; padding: 20px; 
        border-bottom: 1px solid #f1f2f6; transition: 0.3s; text-decoration: none; color: inherit;
    }
    .contact-item:hover { background: #f8f9fa; }
    .avatar-circle { 
        width: 55px; height: 55px; border-radius: 50%; background: #6c5ce7; 
        color: white; display: flex; align-items: center; justify-content: center; 
        font-weight: bold; font-size: 20px; margin-right: 15px; overflow: hidden;
    }
    .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }
    .contact-info { flex: 1; }
    .contact-name { font-weight: 700; font-size: 16px; margin-bottom: 4px; color: #2d3436; }
    .last-msg { font-size: 14px; color: #636e72; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px; }
    .time { font-size: 12px; color: #b2bec3; }
</style>

<div class="container">
    <h3 class="fw-bold mb-4"><i class="fas fa-comments me-2"></i> Tin nhắn của tôi</h3>

    <div class="contact-list shadow-sm">
        @forelse($contacts as $contact)
            @php
                // Xác định người kia là ai
                $otherUser = ($contact->user_one_id == Auth::id()) ? $contact->userTwo : $contact->userOne;
                $lastMessage = $contact->messages->first();
            @endphp
            
            <a href="{{ route('contacts.show', $contact->id) }}" class="contact-item">
                <div class="avatar-circle">
                    @if($otherUser->profile_picture)
                        <img src="{{ asset('storage/'.$otherUser->profile_picture) }}">
                    @else
                        {{ substr($otherUser->name, 0, 1) }}
                    @endif
                </div>
                <div class="contact-info">
                    <div class="contact-name">{{ $otherUser->name }}</div>
                    <div class="last-msg">
                        {{ $lastMessage ? $lastMessage->message : 'Chưa có tin nhắn...' }}
                    </div>
                </div>
                <div class="time">
                    {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                </div>
            </a>
        @empty
            <div class="p-5 text-center text-muted">
                <i class="fas fa-comment-slash fa-3x mb-3"></i>
                <p>Bạn chưa có cuộc hội thoại nào.</p>
            </div>
        @endforelse
    </div>
</div>
