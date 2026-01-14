<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --sidebar-bg: #1e293b;
        --sidebar-hover: #334155;
        --primary-color: #4f46e5;
        --bg-body: #f8fafc;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: #1e293b;
    }

    /* --- SIDEBAR --- */
    .sidebar {
        background-color: var(--sidebar-bg);
        box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }

    .sidebar h4 {
        letter-spacing: 2px;
        font-weight: 700;
        color: #f1f5f9;
        border-bottom: 1px solid #334155;
        padding-bottom: 20px;
    }

    .sidebar .nav-link {
        border-radius: 8px;
        margin-bottom: 5px;
        padding: 12px 15px;
        transition: 0.2s;
        font-weight: 500;
        color: #94a3b8 !important;
        display: flex;
        align-items: center;
    }

    .sidebar .nav-link:hover {
        background-color: var(--sidebar-hover);
        color: #fff !important;
    }

    .sidebar .nav-link.active {
        background-color: var(--primary-color) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    /* --- CARDS (Stats) --- */
    .card-stats {
        border: none;
        border-radius: 12px;
        transition: transform 0.2s;
    }

    .card-stats:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        font-size: 2rem;
        opacity: 0.3;
        position: absolute;
        right: 20px;
        bottom: 10px;
    }

    /* --- CONTENT AREA --- */
    .main-content {
        background-color: var(--bg-body);
    }

    .page-title {
        font-weight: 700;
        color: #0f172a;
    }

    /* --- TABLE --- */
    .card-table {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table thead {
        background-color: #f1f5f9;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border: none;
        padding: 15px;
    }

    .table td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    /* --- BADGES --- */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
    }

    .badge.bg-danger { background-color: #fee2e2 !important; color: #ef4444 !important; }
    .badge.bg-success { background-color: #dcfce7 !important; color: #22c55e !important; }
    .badge.bg-warning { background-color: #fef9c3 !important; color: #ca8a04 !important; }

    /* --- CUSTOM BUTTONS --- */
    .btn-success { background-color: #10b981; border: none; font-weight: 600; }
    .btn-success:hover { background-color: #059669; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar min-vh-100 p-3 text-white">
            <h4 class="text-center mt-3 mb-4">REAL ESTATE</h4>
            <div class="nav flex-column nav-pills">
                <a href="{{ route('index-news-admin') }}" class="nav-link active">
                    <i class="fas fa-newspaper me-3"></i> Tin tức
                </a>
                <a href="{{ route('index-false-sale-post-admin') }}" class="nav-link">
                    <i class="fas fa-clock me-3"></i> Chờ duyệt 
                    <span class="badge bg-danger ms-auto">{{ $pendingPostsCount ?? '0' }}</span>
                </a>
                <a href="{{ route('index-true-sale-post-admin') }}" class="nav-link">
                    <i class="fas fa-check-circle me-3"></i> Đã duyệt
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

        <div class="col-md-10 p-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title">Bảng điều khiển</h2>
                <span class="text-muted"><i class="far fa-calendar-alt me-2"></i>{{ date('d/m/Y') }}</span>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-stats bg-white shadow-sm border-start border-primary border-5">
                        <div class="card-body position-relative">
                            <h6 class="text-muted text-uppercase small fw-bold">Tổng Tin Tức</h6>
                            <h3 class="fw-bold mb-0">{{ $rentPosts->total() }}</h3>
                            <i class="fas fa-newspaper stat-icon text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stats bg-white shadow-sm border-start border-warning border-5">
                        <div class="card-body position-relative">
                            <h6 class="text-muted text-uppercase small fw-bold">Chờ duyệt</h6>
                            <h3 class="fw-bold mb-0">{{ $pendingPostsCount ?? 0 }}</h3>
                            <i class="fas fa-hourglass-half stat-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-table shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Danh sách bài viết</h5>
                    <a href="{{ route('create-news-admin') }}" class="btn btn-success btn-sm px-3">
                        <i class="fas fa-plus me-2"></i>Thêm bài viết
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success m-3">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="50">ID</th>
                                    <th>Nội dung bài viết</th>
                                    <th>Trạng thái</th>
                                    <th>Hình ảnh</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rentPosts as $post)
                                <tr>
                                    <td class="text-center text-muted">#{{ $post->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $post->title }}</div>
                                        <small class="text-muted">Ngày đăng: {{ $post->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        @if($post->status)
                                            <span class="badge bg-success">Đã xuất bản</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Bản nháp</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($post->images && $post->images->count() > 0)
                                            <img src="{{ asset('storage/' . $post->images[0]->image_url) }}" class="rounded shadow-sm" style="width:60px; height:40px; object-fit:cover;">
                                        @else
                                            <div class="bg-light rounded text-center" style="width:60px; height:40px; line-height:40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('show-news-admin', $post->id) }}" class="btn btn-sm btn-light text-info" title="Xem"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('edit-news-admin', $post->id) }}" class="btn btn-sm btn-light text-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('destroy-news-admin', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa bài viết này?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                        Chưa có bài viết nào được tìm thấy.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    {{ $rentPosts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>