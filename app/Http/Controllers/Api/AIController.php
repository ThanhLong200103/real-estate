<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalePost;
use Illuminate\Http\Request;

class AIController extends Controller
{
    // app/Http/Controllers/Api/AIController.php
    public function getPostsForAI()
    {
        return SalePost::with(['images', 'province', 'district', 'ward', 'category'])
            ->where('status', 1)
            ->latest()
            ->get();
    }
}