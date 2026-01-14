<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalePost\StoreSalePostRequest;
use App\Models\SalePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SalePostController extends Controller
{
    public function index_true()
    {
        $query = SalePost::with('images')->where('status', true);

        // Chỉ Admin mới xem được toàn bộ, nếu không phải Admin thì chỉ xem bài của mình
        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            $query->where('user_id', Auth::id());
        }

        $rentPosts = $query->latest()->paginate(10);
        return view('admin.sale-post.index-true', compact('rentPosts'));
    }

    public function index_false()
    {
        $query = SalePost::with('images')->where('status', false);

        // ĐỒNG BỘ: Kiểm tra role không phân biệt hoa thường để Admin thấy được tin chờ duyệt
        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            $query->where('user_id', Auth::id());
        }

        $rentPosts = $query->latest()->paginate(10);
        return view('admin.sale-post.index-false', compact('rentPosts'));
    }

    public function approve($id)
    {
        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        $post = SalePost::findOrFail($id);
        $post->update(['status' => true]);

        return redirect()->route('index-false-sale-post-admin')->with('success', 'Đã duyệt bài viết thành công!');
    }

    public function create()
    {
        return view('admin.sale-post.create');
    }

    public function store(StoreSalePostRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $sale = SalePost::create([
                    'user_id'      => Auth::id(),
                    'title'        => $request->title,
                    'description'  => $request->description,
                    'price'        => $request->price,
                    'area'         => $request->area,
                    'address'      => $request->address,
                    'bedrooms'     => $request->bedrooms,
                    'bathrooms'    => $request->bathrooms,
                    'is_furnished' => (bool)$request->is_furnished,
                    'status'       => false, // MẶC ĐỊNH LÀ FALSE để tin vào danh sách chờ duyệt
                ]);

                if ($request->hasFile('image_url')) {
                    foreach ($request->file('image_url') as $image) {
                        $path = $image->store('posts', 'public');
                        $sale->images()->create(['image_url' => $path]);
                    }
                }
            });

            return redirect()->route('index-false-sale-post-admin')
                ->with('success', 'Bất động sản đã được gửi và đang chờ duyệt!');
        } catch (\Exception $e) {
            Log::error("Lỗi tạo BĐS: " . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $post = SalePost::with('images')->findOrFail($id);

        if (strcasecmp(Auth::user()->role, 'admin') === 0 || $post->user_id === Auth::id()) {
            return view('admin.sale-post.show', compact('post'));
        }

        abort(403, 'Bạn không có quyền xem bài này.');
    }

    public function edit($id)
    {
        $rentPost = SalePost::with('images')->findOrFail($id);

        if (strcasecmp(Auth::user()->role, 'admin') === 0 || $rentPost->user_id === Auth::id()) {
            return view('admin.sale-post.edit', compact('rentPost'));
        }

        abort(403, 'Bạn không có quyền sửa tin này');
    }

    public function update(Request $request, string $id)
    {
        $rentPost = SalePost::findOrFail($id);

        if (strcasecmp(Auth::user()->role, 'admin') === 0 || $rentPost->user_id === Auth::id()) {
            DB::transaction(function () use ($request, $rentPost) {
                $rentPost->update([
                    'title'        => $request->title,
                    'price'        => $request->price,
                    'address'      => $request->address,
                    'description'  => $request->description,
                    'area'         => $request->area,
                    'bedrooms'     => $request->bedrooms,
                    'bathrooms'    => $request->bathrooms,
                    'is_furnished' => (bool)$request->is_furnished,
                ]);

                if ($request->hasFile('image_url')) {
                    foreach ($rentPost->images as $oldImage) {
                        Storage::disk('public')->delete($oldImage->image_url);
                        $oldImage->delete();
                    }

                    foreach ($request->file('image_url') as $image) {
                        $path = $image->store('posts', 'public');
                        $rentPost->images()->create(['image_url' => $path]);
                    }
                }

                if ($request->has('status') && strcasecmp(Auth::user()->role, 'admin') === 0) {
                    $rentPost->update(['status' => (bool)$request->status]);
                }
            });

            return redirect()->route($rentPost->status ? 'index-true-sale-post-admin' : 'index-false-sale-post-admin')
                ->with('success', 'Cập nhật bài viết thành công!');
        }

        abort(403, 'Không thực hiện được');
    }

    public function destroy(string $id)
    {
        $rentPost = SalePost::with('images')->findOrFail($id);

        if (strcasecmp(Auth::user()->role, 'admin') === 0 || $rentPost->user_id === Auth::id()) {
            $isPending = $rentPost->status == false;

            DB::transaction(function () use ($rentPost) {
                foreach ($rentPost->images as $image) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $rentPost->images()->delete();
                $rentPost->delete();
            });

            return redirect()->route($isPending ? 'index-false-sale-post-admin' : 'index-true-sale-post-admin')
                ->with('success', 'Đã xóa bài viết thành công!');
        }

        abort(403, 'Bạn không có quyền xóa tin này');
    }
}