<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalePost\StoreSalePostRequest;
use App\Models\SalePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SalePostController extends Controller
{
    // Trang chủ hiển thị tin đã được Admin duyệt
    public function index()
    {
        $rentPosts = SalePost::with('images')
            ->where('status', true)
            ->latest()
            ->paginate(10);

        return view('home', compact('rentPosts'));
    }

    // MỚI: Trang danh sách tin của riêng User đang đăng nhập
    public function myPosts()
    {
        $myPosts = SalePost::with('images')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.sale-post.index', compact('myPosts'));
    }

    public function create()
    {
        return Auth::check() ? view('user.sale-post.create') : view('auth.login');
    }

    public function store(StoreSalePostRequest $request)
    {
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
                'is_furnished' => $request->is_furnished,
                'status'       => false, // Mặc định chờ duyệt
            ]);

            if ($request->hasFile('image_url')) {
                foreach ($request->file('image_url') as $file) {
                    $fileName = time() . '-' . $file->getClientOriginalName();
                    $path = $file->storeAs('posts', $fileName, 'public');
                    $sale->images()->create(['image_url' => $path]);
                }
            }
        });

        return redirect()->route('user-sale-post-index')->with('success', 'Tin đang chờ duyệt!');
    }

    public function show($id)
    {
        $rentPosts = SalePost::with('images')->findOrFail($id);

        // KIỂM TRA QUYỀN TRUY CẬP
        if (!$rentPosts->status) {
            $isAdmin = Auth::check() && strcasecmp(Auth::user()->role, 'admin') === 0;
            $isOwner = Auth::check() && $rentPosts->user_id == Auth::id();

            // Nếu không phải admin và cũng không phải chủ tin -> Giấu tin đi
            if (!$isAdmin && !$isOwner) {
                abort(404, 'Bài viết này đang chờ duyệt và không thể hiển thị công khai.');
            }
        }

        return view('user.sale-post.show', compact('rentPosts'));
    }

    public function edit($id)
    {
        $rentPost = SalePost::with('images')->findOrFail($id);

        // Kiểm tra quyền sở hữu bài đăng
        if ($rentPost->user_id == Auth::id()) {
            return view('user.sale-post.edit', compact('rentPost'));
        }

        abort(403, 'Bạn không có quyền chỉnh sửa bài đăng này.');
    }

    public function update(Request $request, string $id)
    {
        $rentPost = SalePost::findOrFail($id);

        if ($rentPost->user_id == Auth::id()) {
            DB::transaction(function () use ($request, $rentPost) {
                $rentPost->update([
                    'title'       => $request->title,
                    'price'       => $request->price,
                    'address'     => $request->address,
                    'description' => $request->description,
                    'area'        => $request->area,
                    'status'      => false, // Sửa tin thì phải duyệt lại
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
            });

            return redirect()->route('user-sale-post-index')->with('success', 'Cập nhật thành công!');
        }
        abort(403);
    }

    public function destroy(string $id)
    {
        $rentPost = SalePost::with('images')->findOrFail($id);

        if ($rentPost->user_id == Auth::id()) {
            foreach ($rentPost->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }
            $rentPost->delete();
            return redirect()->route('user-sale-post-index')->with('success', 'Đã xóa tin đăng');
        }
        abort(403);
    }
}