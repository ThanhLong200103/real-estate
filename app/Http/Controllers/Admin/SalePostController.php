<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalePost\StoreSalePostRequest;
use App\Models\SalePost;
use App\Models\Category; // Thêm
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

        $query = SalePost::with(['images', 'category'])
            ->where('status', true)
            ->where('type', $type);

        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            $query->where('user_id', Auth::id());
        }

        $rentPosts = $query->latest()->paginate(10);
        $pendingPostsCount = SalePost::where('status', false)->count();

        return view('admin.sale-post.index-true', compact('rentPosts', 'pendingPostsCount', 'type'));
    }

    public function index_false(Request $request)
    {
        $type = $request->query('type', 'sale');

        $query = SalePost::with(['images', 'category'])
            ->where('status', false)
            ->where('type', $type);

        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            $query->where('user_id', Auth::id());
        }

        $rentPosts = $query->latest()->paginate(10);
        $pendingPostsCount = SalePost::where('status', false)->where('type', $type)->count();

        return view('admin.sale-post.index-false', compact('rentPosts', 'pendingPostsCount', 'type'));
    }

    public function approve($id)
    {
        if (strcasecmp(Auth::user()->role, 'admin') !== 0) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
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
        $categories = Category::all(); // Lấy danh mục cho form
        return view('admin.sale-post.create', compact('categories'));
    }

    public function store(StoreSalePostRequest $request)
    {
        try {
            $newPost = DB::transaction(function () use ($request) {
                $sale = SalePost::create([
                    'user_id'      => Auth::id(),
                    'type'         => $request->type,
                    'category_id'  => $request->category_id, // Sử dụng ID
                    'title'        => $request->title,
                    'description'  => $request->description,
                    'price'        => $request->price ?? 0,
                    'area'         => $request->area ?? 0,
                    'address'      => $request->address,
                    'bedrooms'     => (int)($request->bedrooms ?? 0),
                    'bathrooms'    => (int)($request->bathrooms ?? 0),
                    'is_furnished' =>$request->boolean('is_furnished'),
                    'status'       =>$request->boolean('status'), // Mặc định chờ duyệt
                ]);

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('posts', 'public');
                        $sale->images()->create(['image_url' => $path]);
                    }
                }

                AdminAction::create([
                    'admin_id'    => Auth::id(),
                    'action_type' => 'CREATE',
                    'target_id'   => $sale->id,
                    'target_type' => 'SalePost',
                    'description' => "Đã tạo bài đăng [" . $this->getTypeName($request->type) . "] mới: " . $request->title,
                    'action_time' => now(),
                ]);

                return $sale;
            });

            $targetRoute = $newPost->status ? 'index-true-sale-post-admin' : 'index-false-sale-post-admin';
            return redirect()->route($targetRoute)->with('success', 'Bài đăng đã được tạo thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi tạo BĐS: " . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $post = SalePost::with(['images', 'user', 'category'])->findOrFail($id);
        if (strcasecmp(Auth::user()->role, 'admin') === 0 || $post->user_id === Auth::id()) {
            return view('admin.sale-post.show', compact('post'));
        }
        abort(403);
    }

    public function edit($id)
    {
        $salePost = \App\Models\SalePost::with('images')->findOrFail($id);

        // 2. Lấy danh sách danh mục để đổ vào dropdown
        $categories = \App\Models\Category::all();

        // 3. ĐỊNH NGHĨA BIẾN $type (Lấy từ dữ liệu của bài đăng)
        $type = $salePost->type;

        // 4. Truyền toàn bộ ra view
        return view('admin.sale-post.edit', compact('salePost', 'categories', 'type'));
    }

    public function update(Request $request, string $id)
    {
        $rentPost = SalePost::findOrFail($id);

        if (strcasecmp(Auth::user()->role, 'admin') === 0 || $rentPost->user_id === Auth::id()) {
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
                        'is_furnished' => $request->boolean('is_furnished'),
                    ]);

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


                    if (strcasecmp(Auth::user()->role, 'admin') === 0) {
                        $rentPost->update(['status' => $request->boolean('status')]);
                    }

                    AdminAction::create([
                        'admin_id'    => Auth::id(),
                        'action_type' => 'UPDATE',
                        'target_id'   => $rentPost->id,
                        'target_type' => 'SalePost',
                        'description' => "Đã cập nhật bài đăng [" . $this->getTypeName($rentPost->type) . "] ID #$rentPost->id: " . $request->title,
                        'action_time' => now(),
                    ]);
                });

                return redirect()->route($rentPost->status ? 'index-true-sale-post-admin' : 'index-false-sale-post-admin')
                    ->with('success', 'Cập nhật bài viết thành công!');
            } catch (\Exception $e) {
                Log::error("Lỗi cập nhật BĐS: " . $e->getMessage());
                return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
            }
        }
        abort(403);
    }

    public function destroy(string $id)
    {
        $rentPost = SalePost::with('images')->findOrFail($id);
        $title = $rentPost->title;
        $typeName = $this->getTypeName($rentPost->type);

        if (strcasecmp(Auth::user()->role, 'admin') === 0 || $rentPost->user_id === Auth::id()) {
            $isPending = $rentPost->status == false;

            DB::transaction(function () use ($rentPost, $title, $typeName) {
                foreach ($rentPost->images as $image) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $idForLog = $rentPost->id;
                $rentPost->images()->delete();
                $rentPost->delete();

                AdminAction::create([
                    'admin_id'    => Auth::id(),
                    'action_type' => 'DELETE',
                    'target_id'   => $idForLog,
                    'target_type' => 'SalePost',
                    'description' => "Đã xóa bài đăng [$typeName]: " . $title,
                    'action_time' => now(),
                ]);
            });

            return redirect()->route($isPending ? 'index-false-sale-post-admin' : 'index-true-sale-post-admin')
                ->with('success', 'Đã xóa bài viết thành công!');
        }
        abort(403);
    }
}
