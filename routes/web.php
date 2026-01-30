<?php

use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\SalePostController as AdminSalePostController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\AdminActionController;
use App\Http\Controllers\Api\MarketTrendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SalePostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Services\ProphetService;

/*
|--------------------------------------------------------------------------
| 1. GIAO DIỆN CÔNG KHAI (Public Routes)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-ai/{id}', function ($id) {
    $ai = new ProphetService();
    return $ai->predictByDistrict($id);
});

Route::get('/home', [SalePostController::class, 'index'])->name('home');
// web.php

// Thêm vào sau route /home
Route::get('/nha-dat-tai-{province_slug}', [SalePostController::class, 'provinceIndex'])->name('posts.province');
Route::get('/sale-post/{id}', [SalePostController::class, 'show'])->name('create-sale-show');

// Tin tức cho khách xem
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

/**
 * --- API ĐỊA LÝ (Dùng cho AJAX) ---
 * Cho phép lấy Quận theo Tỉnh và Phường theo Quận.
 */
// Lấy Quận/Huyện
Route::get('/api/get-districts/{province_id}', function ($province_id) {
    return \App\Models\District::where('province_id', $province_id)
        ->select('id', 'name')
        ->get();
})->name('api.get-districts');

// routes/api.php
Route::get('/api/market-forecast/{district_id}', [MarketTrendController::class, 'getForecast']);

// Lấy Phường/Xã (MỚI THÊM)
Route::get('/api/get-wards/{district_id}', function ($district_id) {
    return \App\Models\Ward::where('district_id', $district_id)
        ->select('id', 'name')
        ->get();
})->name('api.get-wards');

Route::get('/api/forecast', [
    App\Http\Controllers\Api\ForecastController::class,
    'show'
])->name('api.forecast');


/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATION (Dành cho khách chưa đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login-form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


/*
|--------------------------------------------------------------------------
| 3. KHU VỰC THÀNH VIÊN (Yêu cầu đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Quản lý hồ sơ cá nhân
    Route::get('/profile', [UserProfileController::class, 'showProfile'])->name('user.profile');
    Route::put('/profile', [UserProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/profile/reset-password', [UserProfileController::class, 'showResetPassword'])->name('user.profile.reset-password');
    Route::post('/profile/reset-password', [UserProfileController::class, 'resetPassword'])->name('user.profile.reset-password.post');

    // Quản lý bài đăng cá nhân
    Route::get('/sale-post-add', [SalePostController::class, 'create'])->name('create-sale-post');
    Route::post('/sale-post', [SalePostController::class, 'store'])->name('store-sale-post');
    Route::get('/my-posts/{id}', [SalePostController::class, 'show'])->name('user-sale-post-show');
    Route::get('/my-posts', [SalePostController::class, 'myPosts'])->name('user-sale-post-index');
    Route::get('/user/sale-post/edit/{id}', [SalePostController::class, 'edit'])->name('user-edit-sale-post');
    Route::put('/user/sale-post/update/{id}', [SalePostController::class, 'update'])->name('user-update-sale-post');
    Route::delete('/user/sale-post/delete/{id}', [SalePostController::class, 'destroy'])->name('user-destroy-sale-post');

    // Hệ thống liên hệ & Tin nhắn
    Route::get('/my-contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/my-contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
    Route::post('/contacts/start', [ContactController::class, 'startConversation'])->name('contacts.start');
    Route::post('/my-contacts/{id}/send', [ContactController::class, 'send'])->name('contacts.send');

    // Báo cáo (Reports)
    Route::post('/report/store', [ReportController::class, 'store'])->name('user.report.store');
    Route::get('/my-reports', [ReportController::class, 'index'])->name('user.report.index');

    // Yêu thích (Favorites)
    Route::post('/favorite/toggle/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
    Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorite.index');

    // Bình luận (Comments)
    Route::post('/sale-post/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});


/*
|--------------------------------------------------------------------------
| 4. KHU VỰC QUẢN TRỊ (Yêu cầu quyền Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Nhật ký hoạt động
    Route::get('/logs', [AdminActionController::class, 'index'])->name('admin.logs.index');

    // Quản lý tin tức
    Route::prefix('news')->group(function () {
        Route::get('/index', [AdminNewsController::class, 'index'])->name('index-news-admin');
        Route::get('/create', [AdminNewsController::class, 'create'])->name('create-news-admin');
        Route::post('/store', [AdminNewsController::class, 'store'])->name('store-news-admin');
        Route::get('/show/{id}', [AdminNewsController::class, 'show'])->name('show-news-admin');
        Route::get('/edit/{id}', [AdminNewsController::class, 'edit'])->name('edit-news-admin');
        Route::put('/update/{id}', [AdminNewsController::class, 'update'])->name('update-news-admin');
        Route::delete('/delete/{id}', [AdminNewsController::class, 'destroy'])->name('destroy-news-admin');
    });

    // Quản lý bất động sản
    Route::prefix('sale-post')->group(function () {
        Route::get('/index_true', [AdminSalePostController::class, 'index_true'])->name('index-true-sale-post-admin');
        Route::get('/index_false', [AdminSalePostController::class, 'index_false'])->name('index-false-sale-post-admin');
        Route::patch('/approve/{id}', [AdminSalePostController::class, 'approve'])->name('approve-sale-post-admin');
        Route::get('/create', [AdminSalePostController::class, 'create'])->name('create-sale-post-admin');
        Route::post('/store', [AdminSalePostController::class, 'store'])->name('store-sale-post-admin');
        Route::get('/show/{id}', [AdminSalePostController::class, 'show'])->name('show-sale-post-admin');
        Route::get('/edit/{id}', [AdminSalePostController::class, 'edit'])->name('edit-sale-post-admin');
        Route::put('/update/{id}', [AdminSalePostController::class, 'update'])->name('update-sale-post-admin');
        Route::delete('/delete/{id}', [AdminSalePostController::class, 'destroy'])->name('destroy-sale-post-admin');
    });

    // Quản lý báo cáo
    Route::prefix('report')->group(function () {
        Route::get('/index', [AdminReportController::class, 'index'])->name('index-report-admin');
        Route::patch('/update/{id}', [AdminReportController::class, 'updateStatus'])->name('update-report-admin');
    });
});
//redirect sang home 
Route::get('/', function () {
    return redirect('/home');
});
