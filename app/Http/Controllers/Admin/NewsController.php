<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalePost\NewsRequest;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // Hiển thị danh sách tin tức trong Admin
    public function index()
    {
        // Lấy danh sách tin tức (News)
        $rentPosts = News::with('images')->latest()->paginate(10);

        // Đếm số lượng bài đăng Bất động sản đang chờ duyệt để hiển thị trên Sidebar
        $pendingPostsCount = \App\Models\SalePost::where('status', false)->count();

        return view('admin.news.index', compact('rentPosts', 'pendingPostsCount'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(NewsRequest $request)
    {
        DB::transaction(function () use ($request) {
            $sale = News::create([
                'author_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'status' => true,
            ]);

            // Lưu ý: Kiểm tra chính xác tên name="image_url" hay "image_array_new" từ form
            if ($request->hasFile('image_array_new')) {
                foreach ($request->file('image_array_new') as $image) {
                    $path = $image->store('posts', 'public');
                    $sale->images()->create([
                        'image_url' => $path
                    ]);
                }
            }
        });

        return redirect()->route('index-news-admin')->with('success', 'Tin tức đã được tạo!');
    }

    public function show($id)
    {
        // Eager load images để tránh lỗi undefined
        $post = News::with('images')->findOrFail($id);

        // Đếm số lượng tin chờ duyệt cho Sidebar
        $pendingPostsCount = \App\Models\SalePost::where('status', false)->count();

        // Truyền BIẾN $post vào view
        return view('admin.news.show', compact('post', 'pendingPostsCount'));
    }

    public function edit($id)
    {
        $rentPost = News::findOrFail($id);
        return view('admin.news.edit', compact('rentPost'));
    }

    public function update(Request $request, string $id)
    {
        $new = News::findOrFail($id);

        DB::transaction(function () use ($request, $new) {
            $new->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->has('status') ? $request->status : $new->status,
            ]);

            if ($request->hasFile('image_array_new')) {
                // Xóa ảnh cũ
                foreach ($new->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->image_url);
                    $oldImage->delete();
                }

                // Lưu ảnh mới
                foreach ($request->file('image_array_new') as $image) {
                    $path = $image->store('posts', 'public');
                    $new->images()->create([
                        'image_url' => $path
                    ]);
                }
            }
        });

        return redirect()->route('index-news-admin')->with('success', 'Tin tức đã được cập nhật!');
    }

    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        // Xóa ảnh trong folder storage trước khi xóa record
        foreach ($news->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }

        $news->delete();

        return redirect()->route('index-news-admin')->with('success', 'Xóa tin thành công');
    }
}