<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalePost;
use App\Models\Category;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\AdminAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SalePostController extends Controller
{
    private function getTypeName($type)
    {
        return $type === 'sale' ? 'BÁN' : 'CHO THUÊ';
    }

    public function index_true(Request $request)
    {
        $type = $request->query('type', 'sale');
        $query = SalePost::with(['images', 'category', 'province', 'district', 'ward'])
            ->where('status', true)
            ->where('type', $type);

        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            $query->where('user_id', Auth::id());
        }

        $items = $query->latest()->paginate(10);
        $pendingPostsCount = SalePost::where('status', false)->count();

        return view('admin.sale-post.index-true', compact('items', 'pendingPostsCount', 'type'));
    }

    public function index_false(Request $request)
    {
        $type = $request->query('type', 'sale');
        $query = SalePost::with(['images', 'category', 'province', 'district', 'ward'])
            ->where('status', false)
            ->where('type', $type);

        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            $query->where('user_id', Auth::id());
        }

        $items = $query->latest()->paginate(10);
        $pendingPostsCount = SalePost::where('status', false)->where('type', $type)->count();

        return view('admin.sale-post.index-false', compact('items', 'pendingPostsCount', 'type'));
    }

    public function approve($id)
    {
        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            abort(403);
        }

        $post = SalePost::findOrFail($id);
        $post->update(['status' => true]);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action_type' => 'APPROVE',
            'target_id'   => $id,
            'target_type' => 'SalePost',
            'description' => "Đã duyệt bài đăng [" . $this->getTypeName($post->type) . "] ID #$id: " . $post->title,
            'action_time' => now(),
        ]);

        return redirect()->route('index-false-sale-post-admin', ['type' => $post->type])
            ->with('success', 'Đã duyệt bài viết thành công!');
    }

    public function create()
    {
        $categories = Category::all();
        $provinces = Province::all();
        return view('admin.sale-post.create', compact('categories', 'provinces'));
    }

    public function store(Request $request)
    {
        // Validation chuẩn Laravel: Tự động quay về kèm lỗi nếu không đạt yêu cầu
        $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:sale,rent',
            'category_id' => 'required|exists:categories,id',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id'     => 'required|exists:wards,id',
            'price'       => 'required|numeric|min:0',
            'area'        => 'required|numeric|min:0',
            'address'     => 'required|string',
            'description' => 'required|string',
            'images'      => 'required|array|min:1',
            'images.*'    => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'required' => ':attribute không được để trống.',
            'image'    => 'File phải là hình ảnh.',
            'exists'   => 'Dữ liệu :attribute không hợp lệ.'
        ]);

        try {
            $newPost = DB::transaction(function () use ($request) {
                // Mặc định là true (đã duyệt) nếu người đăng là admin, hoặc tùy logic của bạn
                $status = $request->has('status') ? $request->boolean('status') : true;

                $sale = SalePost::create([
                    'user_id'      => Auth::id(),
                    'type'         => $request->type,
                    'category_id'  => $request->category_id,
                    'province_id'  => $request->province_id,
                    'district_id'  => $request->district_id,
                    'ward_id'      => $request->ward_id,
                    'title'        => $request->title,
                    'description'  => $request->description,
                    'price'        => $request->price,
                    'area'         => $request->area,
                    'address'      => $request->address,
                    'bedrooms'     => (int)($request->bedrooms ?? 0),
                    'bathrooms'    => (int)($request->bathrooms ?? 0),
                    'is_furnished' => $request->boolean('is_furnished'),
                    'status'       => $status,
                ]);

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('posts', 'public');
                        $sale->images()->create(['image_url' => $path]);
                    }
                }

                // Ghi log hành động admin
                AdminAction::create([
                    'admin_id'    => Auth::id(),
                    'action_type' => 'CREATE',
                    'target_id'   => $sale->id,
                    'target_type' => 'SalePost',
                    'description' => "Đã tạo bài đăng [" . $this->getTypeName($request->type) . "]: " . $request->title,
                    'action_time' => now(),
                ]);

                return $sale;
            });

            $targetRoute = $newPost->status ? 'index-true-sale-post-admin' : 'index-false-sale-post-admin';
            return redirect()->route($targetRoute, ['type' => $newPost->type])
                ->with('success', 'Tạo bài đăng thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi Store SalePost: " . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra trong quá trình lưu dữ liệu. Vui lòng thử lại.');
        }
    }

    public function show($id)
    {
        $post = SalePost::with(['images', 'user', 'category', 'province', 'district', 'ward'])->findOrFail($id);
        if (strcasecmp(Auth::user()->role, 'admin') === 0 || $post->user_id === Auth::id()) {
            return view('admin.sale-post.show', compact('post'));
        }
        abort(403);
    }

    public function edit($id)
    {
        $salePost = SalePost::with(['images', 'province', 'district', 'ward'])->findOrFail($id);
        if (strcasecmp(Auth::user()->role, 'admin') !== 0 && $salePost->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        $provinces = Province::all();
        $districts = District::where('province_id', $salePost->province_id)->get();
        $wards = Ward::where('district_id', $salePost->district_id)->get();
        $type = $salePost->type;

        return view('admin.sale-post.edit', compact('salePost', 'categories', 'type', 'provinces', 'districts', 'wards'));
    }

    public function update(Request $request, string $id)
    {
        $rentPost = SalePost::findOrFail($id);
        if (strcasecmp(Auth::user()->role, 'admin') !== 0 && $rentPost->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id'     => 'required|exists:wards,id',
            'price'       => 'required|numeric|min:0',
            'area'        => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $rentPost) {
                $rentPost->update([
                    'type'         => $request->type,
                    'category_id'  => $request->category_id,
                    'province_id'  => $request->province_id,
                    'district_id'  => $request->district_id,
                    'ward_id'      => $request->ward_id,
                    'title'        => $request->title,
                    'price'        => $request->price,
                    'address'      => $request->address,
                    'description'  => $request->description,
                    'area'         => $request->area,
                    'bedrooms'     => (int)($request->bedrooms ?? 0),
                    'bathrooms'    => (int)($request->bathrooms ?? 0),
                    'is_furnished' => $request->boolean('is_furnished'),
                ]);

                if ($request->hasFile('images')) {
                    // Xóa ảnh cũ cả trong DB và thư mục Storage
                    foreach ($rentPost->images as $oldImage) {
                        Storage::disk('public')->delete($oldImage->image_url);
                        $oldImage->delete();
                    }
                    // Thêm ảnh mới
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('posts', 'public');
                        $rentPost->images()->create(['image_url' => $path]);
                    }
                }

                if (strcasecmp(Auth::user()->role, 'admin') === 0) {
                    $rentPost->update(['status' => $request->boolean('status')]);
                }

                AdminAction::create([
                    'admin_id'    => Auth::id(),
                    'action_type' => 'UPDATE',
                    'target_id'   => $rentPost->id,
                    'target_type' => 'SalePost',
                    'description' => "Đã cập nhật bài đăng ID #$rentPost->id",
                    'action_time' => now(),
                ]);
            });

            return redirect()->route($rentPost->status ? 'index-true-sale-post-admin' : 'index-false-sale-post-admin', ['type' => $rentPost->type])
                ->with('success', 'Cập nhật bài đăng thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi Update SalePost: " . $e->getMessage());
            return back()->withInput()->with('error', 'Cập nhật thất bại. Vùi lòng kiểm tra lại.');
        }
    }

    public function destroy(string $id)
    {
        $rentPost = SalePost::with('images')->findOrFail($id);
        if (strcasecmp(Auth::user()->role, 'admin') !== 0 && $rentPost->user_id !== Auth::id()) {
            abort(403);
        }

        $type = $rentPost->type;
        $isPending = !$rentPost->status;

        try {
            DB::transaction(function () use ($rentPost) {
                foreach ($rentPost->images as $image) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $rentPost->images()->delete();
                $rentPost->delete();
            });

            $targetRoute = $isPending ? 'index-false-sale-post-admin' : 'index-true-sale-post-admin';
            return redirect()->route($targetRoute, ['type' => $type])->with('success', 'Đã xóa bài đăng thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi Delete SalePost: " . $e->getMessage());
            return back()->with('error', 'Không thể xóa bài đăng lúc này.');
        }
    }
}