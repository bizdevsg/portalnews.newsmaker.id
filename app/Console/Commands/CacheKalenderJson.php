<?php

namespace App\Console\Commands;

use App\Models\EconomicCalendar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CacheKalenderJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kalender:cache-json {--path=cache/kalender.json : Storage path for the JSON file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate kalender ekonomi JSON cache for API responses.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $kalender = EconomicCalendar::all();

        $payload = [
            'status' => 'success',
            'data'   => $kalender,
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');
            return self::FAILURE;
        }

        $path = $this->option('path');
        Storage::disk('local')->put($path, $json);

        $this->info('Kalender JSON cache saved to storage/app/' . $path);
        return self::SUCCESS;
    }
}
