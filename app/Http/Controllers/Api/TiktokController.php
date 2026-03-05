<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tiktok;
use Illuminate\Http\JsonResponse;

class TiktokController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Tiktok::query()
            ->orderByDesc('created_at')
            ->get(['id', 'title', 'embed_code', 'backup_video_url', 'created_at', 'updated_at']);

        return response()->json(
            [
                'status' => 200,
                'message' => 'Data TikTok berhasil diambil',
                'data' => $items,
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
