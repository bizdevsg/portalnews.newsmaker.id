<?php

namespace App\Console\Commands;

use App\Models\Iklan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CacheIklanJson extends Command
{
    protected $signature = 'iklan:cache-json {--path=cache/iklan.json : Storage path for the JSON file}';

    protected $description = 'Generate iklan JSON cache for API responses.';

    public function handle(): int
    {
        if (!Schema::hasTable((new Iklan())->getTable())) {
            $this->error('Iklan table is not available.');

            return self::FAILURE;
        }

        $iklans = Iklan::query()
            ->orderBy('sort_order')
            ->latest()
            ->get()
            ->map(fn (Iklan $iklan) => $this->transformIklan($iklan))
            ->values();

        $payload = [
            'status' => 'success',
            'data' => $iklans,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        $written = Storage::disk('local')->put($path, $json);

        if (! $written) {
            $this->error('Failed to write Iklan JSON cache to storage/app/'.$path.' (check file permissions/ownership).');

            return self::FAILURE;
        }

        $this->info('Iklan JSON cache saved to storage/app/'.$path);

        return self::SUCCESS;
    }

    private function transformIklan(Iklan $iklan): array
    {
        $modalHtml = $iklan->modal_html;

        return [
            'id' => $iklan->id,
            'title' => $iklan->title,
            'description' => $iklan->description,
            'image' => $iklan->image,
            'image_url' => $iklan->image ? asset($iklan->image) : null,
            'cta_label' => $iklan->cta_label,
            'cta_url' => $iklan->cta_url,
            'modal_html' => $modalHtml,
            'design_mode' => filled($modalHtml) ? 'html' : 'standard',
            'has_custom_html' => filled($modalHtml),
            'start_at' => optional($iklan->start_at)->toISOString(),
            'end_at' => optional($iklan->end_at)->toISOString(),
            'is_active' => (bool) $iklan->is_active,
            'sort_order' => (int) $iklan->sort_order,
            'created_at' => optional($iklan->created_at)->toISOString(),
            'updated_at' => optional($iklan->updated_at)->toISOString(),
        ];
    }
}

