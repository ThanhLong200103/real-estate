<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// --- THỨ NHẤT: Phải import các Model này để tránh lỗi "Undefined type" ---
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SalePost extends Model
{
    use HasFactory;

    protected $table = 'sale_posts';

    protected $fillable = [
        'user_id',
        'category_id',
        'province_id',
        'district_id',
        'ward_id',      // --- THỨ HAI: Phải thêm ward_id vào fillable ---
        'type',
        'title',
        'slug',
        'description',
        'price',
        'area',
        'address',
        'bedrooms',
        'bathrooms',
        'is_furnished',
        'status'
    ];

    protected static function booted()
    {
        // Hàm dùng chung để cập nhật bảng xu hướng và xóa cache
        $updateMarketStats = function ($districtId, $date) {
            if (!$districtId) return;

            $monthYear = Carbon::parse($date)->format('Y-m-01');

            $stats = DB::table('sale_posts')
                ->where('district_id', $districtId)
                ->whereBetween('created_at', [
                    Carbon::parse($monthYear)->startOfMonth(),
                    Carbon::parse($monthYear)->endOfMonth()
                ])
                ->where('price', '>', 100000000)
                ->where('area', '>', 5)
                ->select(
                    DB::raw("AVG(price / area) as avg_price"),
                    DB::raw("COUNT(id) as total")
                )
                ->first();

            if ($stats && $stats->total > 0) {
                DB::table('market_trends')->updateOrInsert(
                    ['district_id' => $districtId, 'month_year' => $monthYear],
                    [
                        'avg_price_per_m2' => $stats->avg_price,
                        'post_count' => $stats->total,
                        'updated_at' => now()
                    ]
                );
            } else {
                // Nếu không còn bài đăng nào trong tháng đó, xóa luôn dòng đó trong trends
                DB::table('market_trends')->where(['district_id' => $districtId, 'month_year' => $monthYear])->delete();
            }

            Cache::forget("forecast_district_{$districtId}");
        };

        static::created(fn($post) => $updateMarketStats($post->district_id, $post->created_at));

        static::updated(function ($post) use ($updateMarketStats) {
            $updateMarketStats($post->district_id, $post->created_at);
            // Nếu đổi quận, cập nhật cả quận cũ
            if ($post->wasChanged('district_id')) {
                $updateMarketStats($post->getOriginal('district_id'), $post->created_at);
            }
        });

        static::deleted(fn($post) => $updateMarketStats($post->district_id, $post->created_at));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(SalePostImage::class, 'sale_post_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function chats()
    {
        return $this->hasMany(SalePostChat::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'sale_post_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}