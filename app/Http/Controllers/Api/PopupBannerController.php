<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PopupBanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class PopupBannerController extends Controller
{
    public function index(): JsonResponse
    {
        if (!Schema::hasTable((new PopupBanner())->getTable())) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Tabel popup banner belum tersedia.',
                ],
                503,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $popupBanners = PopupBanner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_at')
                    ->orWhere('start_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            })
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return response()->json(
            [
                'status' => 'success',
                'data' => $popupBanners
                    ->map(fn (PopupBanner $popupBanner) => $this->transformPopupBanner($popupBanner))
                    ->values(),
                'meta' => [
                    'total' => $popupBanners->count(),
                    'generated_at' => now()->toISOString(),
                ],
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function transformPopupBanner(PopupBanner $popupBanner): array
    {
        $modalHtml = $popupBanner->modal_html;

        return [
            'id' => $popupBanner->id,
            'title' => $popupBanner->title,
            'description' => $popupBanner->description,
            'image' => $popupBanner->image,
            'image_url' => $popupBanner->image ? asset($popupBanner->image) : null,
            'cta_label' => $popupBanner->cta_label,
            'cta_url' => $popupBanner->cta_url,
            'modal_html' => $modalHtml,
            'design_mode' => filled($modalHtml) ? 'html' : 'standard',
            'has_custom_html' => filled($modalHtml),
            'start_at' => optional($popupBanner->start_at)->toISOString(),
            'end_at' => optional($popupBanner->end_at)->toISOString(),
            'is_active' => (bool) $popupBanner->is_active,
            'sort_order' => (int) $popupBanner->sort_order,
            'created_at' => optional($popupBanner->created_at)->toISOString(),
            'updated_at' => optional($popupBanner->updated_at)->toISOString(),
        ];
    }
}
