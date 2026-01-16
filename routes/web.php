<?php

use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\SalePostController as AdminSalePostController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\AdminActionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SalePostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController; // --- ĐÃ THÊM ---
use Illuminate\Support\Facades\Route;

// --- 1. GIAO DIỆN CÔNG KHAI (Ai cũng xem được) ---
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [SalePostController::class, 'index'])->name('home');
Route::get('/sale-post/{id}', [SalePostController::class, 'show'])->name('create-sale-show');

// Tin tức cho khách xem
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');


// --- 2. AUTHENTICATION (Khách chưa đăng nhập) ---
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login-form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


// --- 3. KHU VỰC USER (Yêu cầu đăng nhập - middleware: auth) ---
Route::middleware('auth')->group(function () {

    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Quản lý bài đăng cá nhân của User
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

    // --- BÁO CÁO (REPORTS) ---
    Route::post('/report/store', [ReportController::class, 'store'])->name('user.report.store');
    Route::get('/my-reports', [ReportController::class, 'index'])->name('user.report.index');

    // --- YÊU THÍCH (FAVORITES) ---
    Route::post('/favorite/toggle/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
    Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorite.index');

    // --- MỚI THÊM: BÌNH LUẬN (COMMENTS) ---
    Route::post('/sale-post/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});


// --- KHU VỰC ADMIN (auth, admin) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // --- NHẬT KÝ HOẠT ĐỘNG ---
    Route::get('/logs', [AdminActionController::class, 'index'])->name('admin.logs.index');

    // --- QUẢN LÝ TIN TỨC ---
    Route::prefix('news')->group(function () {
        Route::get('/index', [AdminNewsController::class, 'index'])->name('index-news-admin');
        Route::get('/create', [AdminNewsController::class, 'create'])->name('create-news-admin');
        Route::post('/store', [AdminNewsController::class, 'store'])->name('store-news-admin');
        Route::get('/show/{id}', [AdminNewsController::class, 'show'])->name('show-news-admin');
        Route::get('/edit/{id}', [AdminNewsController::class, 'edit'])->name('edit-news-admin');
        Route::put('/update/{id}', [AdminNewsController::class, 'update'])->name('update-news-admin');
        Route::delete('/delete/{id}', [AdminNewsController::class, 'destroy'])->name('destroy-news-admin');
    });

    // --- QUẢN LÝ BẤT ĐỘNG SẢN ---
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

    // --- QUẢN LÝ BÁO CÁO ---
    Route::prefix('report')->group(function () {
        Route::get('/index', [AdminReportController::class, 'index'])->name('index-report-admin');
        Route::patch('/update/{id}', [AdminReportController::class, 'updateStatus'])->name('update-report-admin');
    });
});