<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - EstateHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Tùy chỉnh thanh cuộn cho chat box mượt hơn */
        #chat-box::-webkit-scrollbar { width: 6px; }
        #chat-box::-webkit-scrollbar-track { background: transparent; }
        #chat-box::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col font-[Plus_Jakarta_Sans,sans-serif]">
    <div class="container mx-auto max-w-4xl h-full flex flex-col p-4">
        
        <div class="bg-white shadow-xl rounded-2xl flex flex-col h-full overflow-hidden border border-gray-200">
            <div class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md z-10">
                <div class="flex items-center gap-3">
                    <a href="{{ route('contacts.index') }}" class="hover:bg-blue-700 p-2 rounded-full transition">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="flex flex-col">
                        <h2 class="font-bold text-lg leading-none">
                            @php $otherUser = (auth()->id() == $contact->user_one_id) ? $contact->userTwo : $contact->userOne; @endphp
                            {{ $otherUser->name }}
                        </h2>
                        <span class="text-[10px] text-blue-100 mt-1 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span> Trực tuyến
                        </span>
                    </div>
                </div>
            </div>

            @if($contact->salePost)
            <div class="flex items-center p-3 bg-blue-50 border-b border-blue-100 gap-4 transition-all hover:bg-blue-100/50">
                <div class="flex-shrink-0">
                    @if($contact->salePost->images && $contact->salePost->images->count() > 0)
                        <img src="{{ asset('storage/' . $contact->salePost->images->first()->image_url) }}" 
                             class="w-14 h-14 object-cover rounded-xl shadow-sm border-2 border-white">
                    @else
                        <div class="w-14 h-14 bg-gray-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-grow min-w-0">
                    <h3 class="font-bold text-gray-800 text-sm truncate uppercase tracking-tight">
                        {{ $contact->salePost->title }}
                    </h3>
                    <p class="text-sm text-red-600 font-extrabold">
                        {{ number_format($contact->salePost->price) }} <span class="text-[10px]">VND</span>
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('create-sale-show', $contact->salePost->id) }}" 
                       class="inline-flex items-center gap-1 text-xs bg-white text-blue-600 border border-blue-200 px-3 py-2 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm font-bold">
                        <i class="fas fa-external-link-alt text-[10px]"></i> Xem tin
                    </a>
                </div>
            </div>
            @endif

            <div class="flex-grow overflow-y-auto p-4 space-y-4 bg-gray-50/50" id="chat-box">
                @foreach($contact->messages as $message)
                    <div class="flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] md:max-w-[70%] px-4 py-2.5 rounded-2xl shadow-sm relative {{ $message->sender_id == auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white text-gray-800 rounded-tl-none border border-gray-200' }}">
                            <p class="text-[14px] leading-relaxed">{{ $message->message }}</p>
                            <span class="text-[9px] opacity-70 mt-1 block {{ $message->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 bg-white border-t border-gray-100">
                <form action="{{ route('contacts.send', $contact->id) }}" method="POST" class="flex gap-2 items-center">
                    @csrf
                    <div class="flex-grow relative">
                        <input type="text" name="message" autocomplete="off" placeholder="Nhập tin nhắn..." 
                               class="w-full border border-gray-200 bg-gray-50 rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm" required>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:bg-blue-700 hover:scale-105 transition-all shadow-lg shadow-blue-200 shrink-0">
                        <i class="fas fa-paper-plane text-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Luôn cuộn xuống tin nhắn cuối cùng khi load trang
        const chatBox = document.getElementById('chat-box');
        window.onload = () => {
            chatBox.scrollTop = chatBox.scrollHeight;
        };
    </script>
</body>
</html>