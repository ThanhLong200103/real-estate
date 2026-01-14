<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --sidebar-bg: #1e293b;
        --sidebar-hover: #334155;
        --primary-color: #4f46e5;
        --warning-color: #f59e0b;
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
    .header-icon { color: var(--warning-color); margin-right: 10px; }

    /* --- TABLE STYLING --- */
    .card-table { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; background: white; }
    .table thead { background-color: #f1f5f9; }
    .table th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b; border: none; padding: 15px; }
    .table td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

    /* --- THÔNG SỐ BĐS --- */
    .property-spec { font-size: 0.85rem; color: #64748b; }
    .property-spec i { width: 20px; color: var(--primary-color); }
    .price-text { font-size: 1rem; color: #ef4444; font-weight: 700; }
    .area-badge { font-size: 0.75rem; padding: 4px 8px; background: #f1f5f9; border-radius: 4px; font-weight: 600; }

    /* --- BUTTONS --- */
    .btn-approve { background-color: #10b981; color: white; border: none; font-weight: 600; transition: 0.2s; }
    .btn-approve:hover { background-color: #059669; transform: translateY(-1px); color: white; }
    .btn-action-group .btn { border: none; background-color: #f1f5f9; margin-left: 2px; }
    .btn-action-group .btn:hover { background-color: #e2e8f0; }

    /* --- EMPTY STATE --- */
    .empty-state { padding: 80px 0; background: white; border-radius: 12px; border: 2px dashed #e2e8f0; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar min-vh-100 p-3 text-white">
            <h4 class="text-center mt-3 mb-4">REAL ESTATE</h4>
            <div class="nav flex-column nav-pills">
                <a href="{{ route('index-news-admin') }}" class="nav-link">
                    <i class="fas fa-newspaper me-3"></i> Quản lý Tin tức
                </a>
                <a href="{{ route('index-false-sale-post-admin') }}" class="nav-link active">
                    <i class="fas fa-clock me-3"></i> Duyệt bài đăng 
                </a>
                <a href="{{ route('index-true-sale-post-admin') }}" class="nav-link">
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
                    <h2 class="page-title mb-0"><i class="fas fa-user-check header-icon"></i>Duyệt Bất Động Sản</h2>
                    <p class="text-muted small mb-0">Yêu cầu đăng tin đang chờ xử lý từ người dùng</p>
                </div>
                <a href="{{ route('create-sale-post-admin') }}" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 10px;">
                    <i class="fas fa-plus me-2"></i>Tạo bài viết mới
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
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
                                    <th>Thông tin bài đăng</th>
                                    <th>Giá & Diện tích</th>
                                    <th>Thông số</th>
                                    <th>Địa chỉ</th>
                                    <th>Ảnh</th>
                                    <th class="text-center">Quyết định</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rentPosts as $post)
                                    <tr>
                                        <td class="text-center text-muted fw-bold">#{{ $post->id }}</td>
                                        <td style="max-width: 250px;">
                                            <div class="fw-bold text-dark mb-1">{{ $post->title }}</div>
                                            <div class="small text-muted"><i class="fas fa-user-circle me-1"></i>{{ $post->user->name ?? 'Khách' }}</div>
                                        </td>
                                        <td>
                                            <div class="price-text">{{ number_format($post->price) }} đ</div>
                                            <span class="area-badge">{{ $post->area }} m²</span>
                                        </td>
                                        <td class="property-spec">
                                            <div class="mb-1"><i class="fas fa-bed"></i> {{ $post->bedrooms }} PN</div>
                                            <div><i class="fas fa-bath"></i> {{ $post->bathrooms }} PT</div>
                                        </td>
                                        <td>
                                            <span class="small text-muted" title="{{ $post->address }}">
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>{{ Str::limit($post->address, 25) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($post->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $post->images->first()->image_url) }}" 
                                                     class="rounded shadow-sm border" style="width:60px; height:45px; object-fit:cover;">
                                            @else
                                                <div class="bg-light rounded border text-center" style="width:60px; height:45px; line-height:45px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                {{-- Nút Duyệt --}}
                                                <form action="{{ route('approve-sale-post-admin', $post->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-approve px-3 py-2" onclick="return confirm('Duyệt bài đăng này?');">
                                                        <i class="fas fa-check me-1"></i> Duyệt
                                                    </button>
                                                </form>
                                                
                                                {{-- Nhóm hành động phụ --}}
                                                <div class="btn-group btn-action-group shadow-sm">
                                                    <a href="{{ route('show-sale-post-admin', $post->id) }}" class="btn btn-sm text-info" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('edit-sale-post-admin', $post->id) }}" class="btn btn-sm text-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('destroy-sale-post-admin', $post->id) }}" method="POST" onsubmit="return confirm('Xóa vĩnh viễn bài này?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm text-danger" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $rentPosts->links() }}
                    </div>
                </div>
            @else
                <div class="empty-state text-center shadow-sm">
                    <div class="mb-4">
                        <i class="fas fa-check-double fa-4x" style="color: #10b981; opacity: 0.5;"></i>
                    </div>
                    <h4 class="fw-bold">Hệ thống đã sạch bài chờ!</h4>
                    <p class="text-muted">Hiện tại không có yêu cầu phê duyệt bất động sản nào.</p>
                    <a href="{{ route('index-true-sale-post-admin') }}" class="btn btn-outline-primary btn-sm mt-2">
                        Xem bài đã duyệt
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>