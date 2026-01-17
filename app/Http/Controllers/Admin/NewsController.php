<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalePost\NewsRequest;
use App\Models\News;
use App\Models\AdminAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $rentPosts = News::with('images')->latest()->paginate(10);
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

            if ($request->hasFile('image_array_new')) {
                foreach ($request->file('image_array_new') as $image) {
                    $path = $image->store('posts', 'public');
                    $sale->images()->create(['image_url' => $path]);
                }
            }

            // Ghi Log Hành Động
            AdminAction::create([
                'admin_id' => Auth::id(),
                'action_type' => 'CREATE',
                'target_type' => 'News',
                'target_id' => $sale->id,
                'description' => "Đã tạo tin tức mới: " . $request->title,
            ]);
        });

        return redirect()->route('index-news-admin')->with('success', 'Tin tức đã được tạo!');
    }

    public function show($id)
    {
        $post = News::with('images')->findOrFail($id);
        $pendingPostsCount = \App\Models\SalePost::where('status', false)->count();
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
                foreach ($new->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->image_url);
                    $oldImage->delete();
                }
                foreach ($request->file('image_array_new') as $image) {
                    $path = $image->store('posts', 'public');
                    $new->images()->create(['image_url' => $path]);
                }
            }

            // Ghi Log Hành Động
            AdminAction::create([
                'admin_id' => Auth::id(),
                'action_type' => 'UPDATE',
                'target_type' => 'News',
                'target_id' => $new->id,
                'description' => "Đã cập nhật tin tức ID #$new->id: " . $request->title,
            ]);
        });

        return redirect()->route('index-news-admin')->with('success', 'Tin tức đã được cập nhật!');
    }

    public function destroy(string $id)
    {
        $news = News::findOrFail($id);
        $title = $news->title;

        DB::transaction(function () use ($news, $title) {
            foreach ($news->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }
            $news->delete();

            // Ghi Log Hành Động
            AdminAction::create([
                'admin_id' => Auth::id(),
                'action_type' => 'DELETE',
                'description' => "Đã xóa tin tức: " . $title,
                'target_type' => 'News',
                'target_id' => $news->id,
            ]);
        });

        return redirect()->route('index-news-admin')->with('success', 'Xóa tin thành công');
    }
}