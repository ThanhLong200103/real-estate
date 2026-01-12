<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalePost\NewsRequest;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB ;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           if (Auth::check() && Auth::user()->role === 'Admin') {
          $rentPosts = News::where('status', 'pending')->with('images')->paginate(10);
        }
        else{
            $rentPosts = News::where('status', 'approved')
            ->with('images')
            ->paginate(10);


        }
        //view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(Auth::check()){
              return view('rent-posts.create');
        }else{
            // tra
    }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsRequest $request)
    {
        DB::transaction(function () use ($request) {

        $sale = News::create([
            'author_id' =>Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        if ($request->hasFile('image_url')) {

            foreach ($request->file('image_array_new') as $image) {

                $path = $image->store('posts', 'public');

                $sale->images()->create([
                    'image_url' => $path
                ]);
            }
        }
    });
    //view
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         if(Auth::user()->role === 'Admin'){
        $rentPosts = News::where('status', 'pending')->with('images')->firstOrFail($id);
        }
        else{
              $rentPosts = News::where('status', 'approved')
            ->with('images')
            ->firstOrFail($id);
        }
        //tra ve view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rentPost = News::findOrFail($id);

        // Kiểm tra chỉ user tạo mới được edit
        if ($rentPost->user_id == Auth::id() ||  Auth::user()->role === 'Admin')
           {
            //view
           }
        else {
             abort(403, 'Bạn không có quyền sửa tin này');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $new = News::findOrFail($id);
        if ($new->user_id == Auth::id() ||  Auth::user()->role === 'Admin') {
        DB::transaction(function () use ($request ,$new) {

        $new->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

          if ($request->hasFile('image_array_new')) {

    foreach ($new->images as $oldImage) {
        Storage::disk('public')->delete($oldImage->image_url);
        $oldImage->delete();
    }

    foreach ($request->file('image_array_new') as $image) {
        $path = $image->store('posts', 'public');
        $new->images()->create([
            'image_url' => $path
        ]);
    }
    }

    });
/// view
 }
        else{
                abort(403, 'Bạn không có quyền sửa tin này');
        }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        // Kiểm tra authorization
        if ($news->user_id == Auth::id() ||  Auth::user()->role === 'Admin') {

            $news->delete();


        return redirect()->route('rent-posts.index')->with('success', 'Xóa tin thành công');

        }else{
             abort(403, 'Bạn không có quyền xóa tin này');
        }
    }
}
