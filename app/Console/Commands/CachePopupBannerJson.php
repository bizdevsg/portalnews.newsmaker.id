<?php

namespace App\Console\Commands;

use App\Models\PopupBanner;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CachePopupBannerJson extends Command
{
    protected $signature = 'popup-banner:cache-json {--path=cache/popup-banner.json : Storage path for the JSON file}';

    protected $description = 'Generate popup banner JSON cache for API responses.';

    public function handle(): int
    {
        if (!Schema::hasTable((new PopupBanner())->getTable())) {
            $this->error('Popup banner table is not available.');

            return self::FAILURE;
        }

        $banners = PopupBanner::query()
            ->orderBy('sort_order')
            ->latest()
            ->get()
            ->map(fn (PopupBanner $popupBanner) => $this->transformPopupBanner($popupBanner))
            ->values();

        $payload = [
            'status' => 'success',
            'data' => $banners,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        Storage::disk('local')->put($path, $json);

        $this->info('Popup banner JSON cache saved to storage/app/'.$path);

        return self::SUCCESS;
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
