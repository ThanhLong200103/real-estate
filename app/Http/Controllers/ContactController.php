<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Hiển thị danh sách các cuộc hội thoại của User
     */
    public function index()
    {
        $userId = Auth::id();

        $contacts = Contact::with(['userOne', 'userTwo', 'salePost', 'messages' => function ($q) {
            $q->latest()->limit(1); // Lấy tin nhắn mới nhất để hiển thị preview
        }])
            ->where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->latest() // Đưa cuộc hội thoại mới nhất lên đầu
            ->get();

        return view('user.contacts.index', compact('contacts'));
    }

    /**
     * Hiển thị chi tiết một cuộc hội thoại
     */
    public function show($id)
    {
        $contact = Contact::with(['userOne', 'userTwo', 'salePost.images', 'messages.sender'])
            ->findOrFail($id);

        return view('user.contacts.show', compact('contact'));
    }

    /**
     * Bắt đầu hoặc lấy ra cuộc hội thoại dựa trên sản phẩm
     */
    public function startConversation(Request $request)
    {
        // XÓA HOẶC COMMENT DÒNG NÀY
        // dd($request->all()); 

        $request->validate([
            'user_two_id' => 'required|exists:users,id',
            'sale_post_id' => 'required|exists:sale_posts,id', // Đảm bảo dòng này khớp với database
        ]);

        $userOneId = Auth::id();
        $userTwoId = $request->user_two_id;
        $salePostId = $request->sale_post_id;

        if ($userOneId == $userTwoId) {
            return redirect()->back()->with('error', 'Bạn không thể liên hệ với chính mình.');
        }

        // Tìm cuộc hội thoại đã tồn tại cho cặp user này VÀ bài đăng này
        $contact = Contact::where('sale_post_id', $salePostId)
            ->where(function ($query) use ($userOneId, $userTwoId) {
                $query->where('user_one_id', $userOneId)->where('user_two_id', $userTwoId);
            })
            ->orWhere(function ($query) use ($userOneId, $userTwoId, $salePostId) {
                $query->where('user_one_id', $userTwoId)
                    ->where('user_two_id', $userOneId)
                    ->where('sale_post_id', $salePostId);
            })
            ->first();

        if (!$contact) {
            $contact = Contact::create([
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
                'sale_post_id' => $salePostId,
            ]);
        }

        return redirect()->route('contacts.show', $contact->id);
    }

    /**
     * Gửi tin nhắn
     */
    public function send(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        ContactMessage::create([
            'contact_id' => $id,
            'sender_id' => Auth::id(),
            'message' => $request->message
        ]);

        return back();
    }
}