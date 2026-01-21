<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalePost\StoreSalePostRequest;
use App\Models\{SalePost, Category, Province, District, Ward};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Storage};
use App\Services\ProphetService;
use Illuminate\Support\Str;

class SalePostController extends Controller
{

    public function index(Request $request, ProphetService $prophetService)
    {

        $categories = Category::all();
        $provinces  = Province::all();


        $popularLocations = Province::withCount([
            'sale_posts' => fn($q) => $q->where('status', true)
        ])
            ->orderByDesc('sale_posts_count')
            ->limit(5)
            ->get();

        $latestPosts = SalePost::with(['images', 'province'])
            ->where('status', true)
            ->latest()
            ->limit(8)
            ->get();

     
        $query = SalePost::with([
            'images',
            'category',
            'province',
            'district',
            'ward'
        ])->where('status', true);

        $this->applyFilters($query, $request);

        $rentPosts = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $forecast = null;

        $targetDistrictId = $request->filled('district_id')
            ? (int) $request->district_id
            : 1;

        if ($targetDistrictId) {
            $forecast = $prophetService->predictByDistrict($targetDistrictId);
        }

    
        return view('home', compact(
            'rentPosts',
            'categories',
            'provinces',
            'popularLocations',
            'latestPosts',
            'forecast' 
        ));
    }


    public function myPosts()
    {
        $myPosts = SalePost::with(['images', 'category', 'province', 'district', 'ward'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.sale-post.index', compact('myPosts'));
    }

    public function create()
    {
        return view('user.sale-post.create', [
            'categories' => Category::all(),
            'provinces'  => Province::all(),
        ]);
    }

    public function store(StoreSalePostRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $post = SalePost::create([
                    'user_id'      => Auth::id(),
                    'type'         => $request->type,
                    'category_id'  => $request->category_id,
                    'province_id'  => $request->province_id,
                    'district_id'  => $request->district_id,
                    'ward_id'      => $request->ward_id,
                    'title'        => $request->title,
                    'slug'         => Str::slug($request->title) . '-' . uniqid(),
                    'description'  => $request->description,
                    'price'        => $request->price ?? 0,
                    'area'         => $request->area ?? 0,
                    'address'      => $request->address,
                    'bedrooms'     => $request->bedrooms ?? 0,
                    'bathrooms'    => $request->bathrooms ?? 0,
                    'is_furnished' => $request->boolean('is_furnished'),
                    'status'       => false,
                ]);

                $this->syncImages($post, $request);
            });

            return redirect()->route('user-sale-post-index')
                ->with('success', 'Tin đã gửi, vui lòng chờ duyệt!');
        } catch (\Throwable $e) {

            // Debug nhanh: Nếu vẫn lỗi, hãy tạm thời bỏ comment dòng dưới để xem lỗi thật là gì
            // dd($e->getMessage());
            Log::error($e);
            return back()->withInput()->with('error', 'Có lỗi xảy ra.');
        }
    }

    public function show($id)
    {
        $salePost = SalePost::with([
            'images',
            'user',
            'category',
            'province',
            'district',
            'ward',
            'comments.user',
            'comments.replies.user'
        ])->findOrFail($id);

        if (
            !$salePost->status &&
            !(
                Auth::check() &&
                (Auth::id() === $salePost->user_id || Auth::user()->role === 'admin')
            )
        ) {
            abort(404);
        }

        return view('user.sale-post.show', compact('salePost'));
    }

    public function edit($id)
    {
        $rentPost = SalePost::with('images')->findOrFail($id);
        abort_if($rentPost->user_id !== Auth::id(), 403);

        return view('user.sale-post.edit', [
            'rentPost'   => $rentPost,
            'categories' => Category::all(),
            'provinces'  => Province::all(),
            'districts'  => District::where('province_id', $rentPost->province_id)->get(),
            'wards'      => Ward::where('district_id', $rentPost->district_id)->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $rentPost = SalePost::findOrFail($id);
        abort_if($rentPost->user_id !== Auth::id(), 403);

        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id'     => 'required|exists:wards,id',
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:sale,rent',
            'price'       => 'numeric|min:0',
            'area'        => 'numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $rentPost) {
            $rentPost->update(
                $request->except('images') + [
                    'is_furnished' => $request->boolean('is_furnished'),
                    'status'       => false,
                ]
            );

            $this->syncImages($rentPost, $request, true);
        });

        return redirect()->route('user-sale-post-index')
            ->with('success', 'Cập nhật thành công, chờ duyệt lại.');
    }

    public function destroy($id)
    {
        $post = SalePost::with('images')->findOrFail($id);

        abort_if(
            $post->user_id !== Auth::id() &&
                Auth::user()->role !== 'admin',
            403
        );

        DB::transaction(function () use ($post) {
            foreach ($post->images as $img) {
                Storage::disk('public')->delete($img->image_url);
            }
            $post->images()->delete();
            $post->delete();
        });

        return back()->with('success', 'Đã xóa bài đăng.');
    }

    public function provinceIndex(Request $request, $slug)
    {
        $province = Province::where('slug', $slug)->firstOrFail();

        $query = SalePost::with(['images', 'category', 'province', 'district', 'ward'])
            ->where('province_id', $province->id)
            ->where('status', true);

        $this->applyFilters($query, $request);

        $rentPosts = $query->latest()->paginate(15)->withQueryString();

        return view('user.sale-post.location', [
            'rentPosts'  => $rentPosts,
            'province'   => $province,
            'provinces'  => Province::all(),
            'categories' => Category::all(),
        ]);
    }

    private function applyFilters($query, Request $request)
    {
        // 🔹 Province
        if ($request->has('province_id') && is_numeric($request->province_id)) {
            $query->where('province_id', (int) $request->province_id);
        }

        // 🔹 District
        if ($request->has('district_id') && is_numeric($request->district_id)) {
            $query->where('district_id', (int) $request->district_id);
        }

        // 🔹 Ward
        if ($request->has('ward_id') && is_numeric($request->ward_id)) {
            $query->where('ward_id', (int) $request->ward_id);
        }

        // 🔹 Category
        if ($request->has('category_id') && is_numeric($request->category_id)) {
            $query->where('category_id', (int) $request->category_id);
        }

        // 🔹 Type
        if ($request->has('type') && in_array($request->type, ['sale', 'rent'])) {
            $query->where('type', $request->type);
        }

        // 🔹 Keyword
        if ($request->has('keyword') && trim($request->keyword) !== '') {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        // 🔹 Price range
        $this->rangeFilter($query, 'price', $request->price_range);

        // 🔹 Area range
        $this->rangeFilter($query, 'area', $request->area_range);

        // 🔹 Bedrooms
        if ($request->has('bedrooms') && is_numeric($request->bedrooms)) {
            $query->where('bedrooms', '>=', (int) $request->bedrooms);
        }
    }



    private function rangeFilter($query, $field, $value)
    {
        if (!$value) return;

        if (str_contains($value, '+')) {
            $query->where($field, '>=', (float) rtrim($value, '+'));
        } else {
            [$min, $max] = explode('-', $value);
            $query->whereBetween($field, [(float) $min, (float) $max]);
        }
    }

    private function syncImages($post, Request $request, $replace = false)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // 1. Lưu file vào thư mục storage/app/public/posts
                $path = $image->store('posts', 'public');

                // 2. Lưu vào bảng trung gian hoặc bảng images
                // Đảm bảo tên cột trong create([]) phải là 'image_url'
                $post->images()->create([
                    'image_url' => $path, // Tên cột phải khớp chính xác với lỗi DB báo
                ]);
            }
        }
    }
}