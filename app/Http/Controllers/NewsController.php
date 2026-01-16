<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Hiển thị danh sách tin tức cho Guest và User
     */
    public function index()
    {
        // Lấy các tin có status là true (đã duyệt/công khai)
        $newsList = News::where('status', true)
            ->with('images')
            ->latest()
            ->paginate(10);

        // Trả về view index nằm trong folder news
        return view('news.index', compact('newsList'));
    }

    /**
     * Hiển thị chi tiết một bản tin
     */
    public function show(string $id)
    {
        // Tìm tin tức theo ID và phải có status là true
        $newsPost = News::where('status', true)
            ->where('id', $id)
            ->with('images')
            ->firstOrFail();

        // Lấy 5 bài viết được xem nhiều nhất (bài mới nhất, trừ bài hiện tại)
        $popularPosts = News::where('status', true)
            ->where('id', '!=', $id)
            ->with('images')
            ->latest()
            ->take(5)
            ->get();

        // Trả về view show nằm trong folder news
        return view('news.show', compact('newsPost', 'popularPosts'));
    }
}