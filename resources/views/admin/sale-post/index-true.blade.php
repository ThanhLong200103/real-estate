<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --sidebar-bg: #1e293b;
        --sidebar-hover: #334155;
        --primary-color: #4f46e5;
        --success-color: #10b981;
        --bg-body: #f8fafc;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: #1e293b;
    }

    /* --- SIDEBAR --- */
    .sidebar { background-color: var(--sidebar-bg); box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
    .sidebar h4 { letter-spacing: 2px; font-weight: 700; color: #f1f5f9; border-bottom: 1px solid #334155; padding-bottom: 20px; }
    .sidebar .nav-link { border-radius: 8px; margin-bottom: 5px; padding: 12px 15px; transition: 0.2s; font-weight: 500; color: #94a3b8 !important; display: flex; align-items: center; }
    .sidebar .nav-link:hover { background-color: var(--sidebar-hover); color: #fff !important; }
    .sidebar .nav-link.active { background-color: var(--primary-color) !important; color: #fff !important; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }

    /* --- PAGE HEADER --- */
    .page-title { font-weight: 700; color: #0f172a; }
    .header-icon { color: var(--success-color); margin-right: 10px; }

    /* --- TABLE STYLING --- */
    .card-table { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; background: white; }
    .table thead { background-color: #ecfdf5; } /* Nhạt xanh lá */
    .table th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #065f46; border: none; padding: 15px; }
    .table td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

    /* --- BADGES & TEXT --- */
    .price-text { font-size: 1rem; color: #ef4444; font-weight: 700; }
    .badge-info-custom { background-color: #e0f2fe; color: #0369a1; border: none; font-weight: 600; }
    .status-online { width: 10px; height: 10px; background-color: var(--success-color); border-radius: 50%; display: inline-block; margin-right: 5px; box-shadow: 0 0 8px var(--success-color); }

    /* --- ACTION BUTTONS --- */
    .btn-action { border: none; background-color: #f8fafc; color: #64748b; transition: 0.2s; }
    .btn-action:hover { background-color: #f1f5f9; color: var(--primary-color); }
    .btn-delete:hover { color: #ef4444 !important; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar min-vh-100 p-3 text-white">
            <h4 class="text-center mt-3 mb-4">REAL ESTATE</h4>
            <div class="nav flex-column nav-pills">
                <a href="{{ route('index-news-admin') }}" class="nav-link">
                    <i class="fas fa-newspaper me-3"></i> Quản lý Tin tức
                </a>
                <a href="{{ route('index-false-sale-post-admin') }}" class="nav-link">
                    <i class="fas fa-clock me-3"></i> Duyệt bài đăng 
                </a>
                <a href="{{ route('index-true-sale-post-admin') }}" class="nav-link active">
                    <i class="fas fa-check-circle me-3"></i> Bài đăng đã duyệt
                </a>
                <div class="mt-auto pt-4">
                    <hr class="opacity-20">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm w-100 py-2 border-0">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="page-title mb-0"><i class="fas fa-list-alt header-icon"></i>Tin Đang Hiển Thị</h2>
                    <p class="text-muted small mb-0">Danh sách bất động sản đang hoạt động trên hệ thống</p>
                </div>
                <a href="{{ route('create-sale-post-admin') }}" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 10px;">
                    <i class="fas fa-plus me-2"></i>Tạo bài mới
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                    <i class="fas fa-check-circle me-3 fa-lg"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if($rentPosts->count() > 0)
                <div class="card card-table shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Thông tin BĐS</th>
                                    <th>Giá niêm yết</th>
                                    <th>Diện tích</th>
                                    <th>Cơ sở vật chất</th>
                                    <th>Khu vực</th>
                                    <th>Ảnh minh họa</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rentPosts as $post)
                                    <tr>
                                        <td class="text-center text-muted fw-bold">#{{ $post->id }}</td>
                                        <td style="max-width: 250px;">
                                            <div class="fw-bold text-dark mb-1">{{ $post->title }}</div>
                                            <div class="d-flex align-items-center small text-success fw-medium">
                                                <span class="status-online"></span> Đang hiển thị
                                            </div>
                                        </td>
                                        <td>
                                            <div class="price-text">{{ number_format($post->price) }} đ</div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $post->area }} m²</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info-custom me-1">{{ $post->bedrooms }} PN</span>
                                            <span class="badge bg-light text-dark border">{{ $post->bathrooms }} PT</span>
                                        </td>
                                        <td>
                                            <small class="text-muted" title="{{ $post->address }}">
                                                <i class="fas fa-map-marker-alt text-secondary me-1"></i>{{ Str::limit($post->address, 25) }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($post->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $post->images->first()->image_url) }}" 
                                                     class="rounded shadow-sm border" style="width:60px; height:42px; object-fit:cover;">
                                            @else
                                                <div class="bg-light rounded border text-center text-muted small" style="width:60px; height:42px; line-height:42px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                                <a href="{{ route('show-sale-post-admin', $post->id) }}" class="btn btn-sm btn-action" title="Xem bài đăng"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('edit-sale-post-admin', $post->id) }}" class="btn btn-sm btn-action" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('destroy-sale-post-admin', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn gỡ bài viết này khỏi hệ thống?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-action btn-delete" title="Gỡ bài"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <p class="text-muted small">Hiển thị {{ $rentPosts->count() }} trên tổng số {{ $rentPosts->total() }} bài đăng</p>
                    {{ $rentPosts->links() }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded shadow-sm border">
                    <i class="fas fa-layer-group fa-3x text-light mb-3"></i>
                    <h5 class="text-muted">Chưa có bất động sản nào công khai</h5>
                    <p class="text-muted small">Hãy kiểm tra mục "Duyệt bài đăng" để phê duyệt tin mới.</p>
                </div>
            @endif
        </div>
    </div>
</div>