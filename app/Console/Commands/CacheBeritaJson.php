<?php

namespace App\Console\Commands;

use App\Models\Berita;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CacheBeritaJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'berita:cache-json {--path=cache/berita.json : Storage path for the JSON file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate berita JSON cache for API responses.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $beritas = Berita::select([
            'id',
            'title',
            'title_sg',
            'title_rfb',
            'title_kpf',
            'title_ewf',
            'title_bpf',
            'slug',
            'content',
            'image1',
            'image2',
            'image3',
            'image4',
            'image5',
            'image6',
            'category_id',
            'created_at',
            'updated_at',
        ])
            ->with(['category:id,name,slug'])
            ->get()
            ->transform(function ($berita) {
                return [
                    'id' => $berita->id,
                    'title' => $berita->title,
                    'titles' => [
                        'default' => $berita->title,
                        'sg' => $berita->title_sg ?? $berita->title,
                        'rfb' => $berita->title_rfb ?? $berita->title,
                        'kpf' => $berita->title_kpf ?? $berita->title,
                        'ewf' => $berita->title_ewf ?? $berita->title,
                        'bpf' => $berita->title_bpf ?? $berita->title,
                    ],
                    'slug' => $berita->slug,
                    'content' => $berita->content,
                    'category_id' => $berita->category_id,
                    'kategori' => $berita->category,
                    'images' => $berita->images,
                    'created_at' => $berita->created_at,
                    'updated_at' => $berita->updated_at,
                ];
            })
            ->values();

        $payload = [
            'status' => 'success',
            'data' => $beritas,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        Storage::disk('local')->put($path, $json);

        $this->info('Berita JSON cache saved to storage/app/'.$path);

        return self::SUCCESS;
    }
}
