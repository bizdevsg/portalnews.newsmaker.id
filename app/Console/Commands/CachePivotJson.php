<?php

namespace App\Console\Commands;

use App\Models\pivot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CachePivotJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pivot:cache-json {--path=cache/pivot.json : Storage path for the JSON file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate pivot JSON cache for API responses.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pivots = pivot::all();

        $payload = [
            'Code'   => 200,
            'status' => 'success',
            'data'   => $pivots,
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');
            return self::FAILURE;
        }

        $path = $this->option('path');
        Storage::disk('local')->put($path, $json);

        $this->info('Pivot JSON cache saved to storage/app/' . $path);
        return self::SUCCESS;
    }
}
