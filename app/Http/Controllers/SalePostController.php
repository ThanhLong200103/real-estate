<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalePost\ImageRequest;
use App\Http\Requests\SalePost\StoreSalePostRequest;
use App\Models\SalePost;
use App\Models\SalePostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SalePostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           if (Auth::check() && Auth::user()->role === 'Admin') {
          $rentPosts = SalePost::where('status', 'pending')->with('images')->paginate(10);
        }
        else{
            $rentPosts = SalePost::where('status', 'approved')
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
            // tra ve view dang nhap
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalePostRequest $request)
    {
        DB::transaction(function () use ($request) {

        $sale = SalePost::create([
            'user_id' =>Auth::id(),
            'title' => $request->title,
            'price' => $request->price,
            'address' => $request->address,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        if ($request->hasFile('image_url')) {

            foreach ($request->file('image_url') as $image) {

                $path = $image->store('posts', 'public');

                $sale->images()->create([
                    'image_url' => $path
                ]);
            }
        }
    });

   // tra ve view
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        if(Auth::check() && Auth::user()->role === 'Admin'){
        $rentPosts = SalePost::where('status', 'pending')->with('images')->firstOrFail($id);
        }
        else{
              $rentPosts = SalePost::where('status', 'approved')
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
        $rentPost = SalePost::findOrFail($id);

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
        $rentPost = SalePost::findOrFail($id);

        // Kiểm tra authorization
        if ($rentPost->user_id == Auth::id() ||  Auth::user()->role === 'Admin') {
         DB::transaction(function () use ($request , $rentPost) {

           $rentPost->update([
            'title' => $request->title,
            'price' => $request->price,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        if ($request->hasFile('image_url')) {

    foreach ($rentPost->images as $oldImage) {
        Storage::disk('public')->delete($oldImage->image_url);
        $oldImage->delete();
    }

    foreach ($request->file('image_url') as $image) {
        $path = $image->store('posts', 'public');
        $rentPost->images()->create([
            'image_url' => $path
        ]);
    }
}

         if(Auth::check()  && Auth::user()->role === 'Admin'){
            $rentPost->update([
                'status'=>$request->status
            ]);
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
        $rentPost = SalePost::findOrFail($id);

        // Kiểm tra authorization
        if ($rentPost->user_id == Auth::id() ||  Auth::user()->role === 'Admin') {

            $rentPost->delete();


        return redirect()->route('rent-posts.index')->with('success', 'Xóa tin thành công');

        }else{
             abort(403, 'Bạn không có quyền xóa tin này');
        }
    }
}

