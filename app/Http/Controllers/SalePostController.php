<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalePost\StoreSalePostRequest;
use App\Models\SalePost;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SalePostController extends Controller
{
    /**
     * Trang chủ hiển thị tin đã được duyệt + Bộ lọc tìm kiếm
     */
    public function index(Request $request)
    {
        // Lấy danh sách Categories để hiển thị ở Select box bộ lọc
        $categories = Category::all();

        // Eager loading 'category' và 'images' để tối ưu hiệu năng
        $query = SalePost::with(['images', 'category'])->where('status', true);

        // 1. Lọc theo từ khóa
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('address', 'like', '%' . $request->keyword . '%');
            });
        }

        // 2. Lọc theo hình thức (sale/rent)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 3. Lọc theo ID danh mục mới
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. Lọc theo mức giá
        if ($request->filled('price_range')) {
            $price = $request->price_range;
            if ($price === '10000000000+') {
                $query->where('price', '>=', 10000000000);
            } else {
                $range = explode('-', $price);
                if (count($range) == 2) {
                    $query->whereBetween('price', [(float)$range[0], (float)$range[1]]);
                }
            }
        }

        // 5. Lọc theo diện tích
        if ($request->filled('area_range')) {
            $area = $request->area_range;
            if ($area === '200+') {
                $query->where('area', '>=', 200);
            } else {
                $range = explode('-', $area);
                if (count($range) == 2) {
                    $query->whereBetween('area', [(float)$range[0], (float)$range[1]]);
                }
            }
        }

        // 6. Lọc theo số phòng ngủ
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        $rentPosts = $query->latest()->paginate(12);

        return view('home', compact('rentPosts', 'categories'));
    }

    /**
     * Danh sách tin đăng cá nhân của User
     */
    public function myPosts()
    {
        $myPosts = SalePost::with(['images', 'category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.sale-post.index', compact('myPosts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('user.sale-post.create', compact('categories'));
    }

    public function store(StoreSalePostRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $sale = SalePost::create([
                    'user_id'      => Auth::id(),
                    'type'         => $request->type,
                    'category_id'  => $request->category_id,
                    'title'        => $request->title,
                    'description'  => $request->description,
                    'price'        => $request->price ?? 0,
                    'area'         => $request->area ?? 0,
                    'address'      => $request->address,
                    'bedrooms'     => (int)($request->bedrooms ?? 0),
                    'bathrooms'    => (int)($request->bathrooms ?? 0),
                    'is_furnished' => $request->has('is_furnished') ? 1 : 0,
                    'status'       => false,
                ]);

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $path = $file->store('posts', 'public');
                        $sale->images()->create(['image_url' => $path]);
                    }
                }
            });

            return redirect()->route('user-sale-post-index')->with('success', 'Tin đăng đã được gửi, vui lòng chờ duyệt!');
        } catch (\Exception $e) {
            Log::error("User Store Post Error: " . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $salePost = SalePost::with(['images', 'user', 'category', 'comments.user'])->findOrFail($id);

        if (!$salePost->status) {
            $isAdmin = Auth::check() && strcasecmp(Auth::user()->role, 'admin') === 0;
            $isOwner = Auth::check() && $salePost->user_id == Auth::id();

            if (!$isAdmin && !$isOwner) {
                abort(404, 'Bài viết đang chờ duyệt.');
            }
        }

        // Truyền đúng tên salePost ra view
        return view('user.sale-post.show', compact('salePost'));
    }

    public function edit($id)
    {
        // Eager load images để hiển thị trong trang edit
        $rentPost = SalePost::with('images')->findOrFail($id);
        $categories = Category::all();

        if ($rentPost->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền sửa tin này.');
        }

        return view('user.sale-post.edit', compact('rentPost', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $rentPost = SalePost::findOrFail($id);

        if ($rentPost->user_id !== Auth::id()) {
            abort(403);
        }

        // Thêm Validation cho Category ID và các trường quan trọng
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:sale,rent',
            'price'       => 'required|numeric|min:0',
            'area'        => 'required|numeric|min:0',
            'address'     => 'required|string',
            'description' => 'required|string',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::transaction(function () use ($request, $rentPost) {
                $rentPost->update([
                    'type'         => $request->type,
                    'category_id'  => $request->category_id,
                    'title'        => $request->title,
                    'price'        => $request->price,
                    'address'      => $request->address,
                    'description'  => $request->description,
                    'area'         => $request->area,
                    'bedrooms'     => (int)($request->bedrooms ?? 0),
                    'bathrooms'    => (int)($request->bathrooms ?? 0),
                    'is_furnished' => $request->has('is_furnished') ? 1 : 0,
                    'status'       => false, // Sửa tin thì bắt duyệt lại
                ]);

                // Nếu upload ảnh mới, xóa sạch ảnh cũ (theo logic file Blade bạn gửi)
                if ($request->hasFile('images')) {
                    foreach ($rentPost->images as $oldImage) {
                        Storage::disk('public')->delete($oldImage->image_url);
                        $oldImage->delete();
                    }
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('posts', 'public');
                        $rentPost->images()->create(['image_url' => $path]);
                    }
                }
            });

            return redirect()->route('user-sale-post-index')->with('success', 'Cập nhật thành công, vui lòng chờ duyệt lại!');
        } catch (\Exception $e) {
            Log::error("User Update Post Error: " . $e->getMessage());
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $rentPost = SalePost::with('images')->findOrFail($id);

        if ($rentPost->user_id !== Auth::id() && strcasecmp(Auth::user()->role, 'admin') !== 0) {
            abort(403);
        }

        DB::transaction(function () use ($rentPost) {
            foreach ($rentPost->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }
            $rentPost->images()->delete();
            $rentPost->delete();
        });

        return redirect()->route('user-sale-post-index')->with('success', 'Đã xóa bài đăng thành công.');
    }
}