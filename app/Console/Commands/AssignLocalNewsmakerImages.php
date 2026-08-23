<?php

namespace App\Console\Commands;

use App\Models\NewsmakerArticle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AssignLocalNewsmakerImages extends Command
{
    protected $signature = 'newsmaker:assign-local-images
        {--source=/home/saiadmin/news-images : Folder containing category subfolders of images}
        {--dry-run : Preview counts without writing}';

    protected $description = 'Assign locally-available images to newsmaker_articles, matched by category, reusing images round-robin where supply is limited.';

    private const TARGET_DIR = 'newsmaker23/archive';

    /** main_category_id => list of source subfolder names to draw images from */
    private const CATEGORY_FOLDERS = [
        2 => ['OIL', 'OIL2'],
        4 => ['GOLD', 'GOLD_2', 'GOLD3'],
        5 => ['Market_Analisis', 'ANALYSIS', 'ANALYSIS_2'],
        6 => ['SILVER', 'SILVER2'],
        7 => ['GOLD', 'GOLD_2', 'GOLD3'],
        8 => ['EKONOMI', 'EKONOMI2', 'EKONOMI3'],
        9 => ['EKONOMI', 'EKONOMI2', 'EKONOMI3', 'GLOBAL', 'GLOBAL_2', 'GLOBAL3'],
        10 => ['Market_Analisis', 'OUTLOOK'],
        11 => ['FOREX', 'FOREX_2', 'USMARKET'],
        12 => ['HANGSENG'],
        13 => ['NIKKEI'],
        14 => ['FISKAL'],
        15 => ['FOREX', 'NIKKEI'],
        16 => ['GLOBAL', 'GLOBAL_2', 'GLOBAL3'],
        17 => ['FOREX', 'EUROPE'],
        18 => ['FOREX'],
        19 => ['FOREX', 'EUROPE'],
        20 => ['ANALYSIS', 'ANALYSIS_2', 'Market_Analisis'],
        21 => ['CRYPTO'],
        22 => ['FOREX', 'EUROPE'],
        23 => ['Market_Analisis'],
        24 => ['FOREX', 'FOREX_2'],
        25 => ['GOLD', 'GOLD_2', 'GOLD3', 'SILVER', 'SILVER2'],
        26 => ['NIKKEI', 'HANGSENG'],
        27 => ['OIL', 'OIL2'],
        28 => ['GLOBAL', 'GLOBAL_2', 'GLOBAL3'],
        29 => ['TOKOH'],
        30 => ['EUROPE'],
        32 => ['USMARKET'],
        33 => ['GOLD', 'SILVER', 'OIL'],
        34 => ['HANGSENG'],
        35 => ['NIKKEI'],
        // 31 (Announcement) intentionally has no folder — left untouched.
    ];

    public function handle(): int
    {
        $sourceRoot = rtrim($this->option('source'), '/');
        $dryRun = (bool) $this->option('dry-run');
        $disk = Storage::disk('public');

        if (!is_dir($sourceRoot)) {
            $this->error("Source folder not found: {$sourceRoot}");

            return self::FAILURE;
        }

        // Pass 1: exact URL->filename match for categories whose old image URL
        // already encodes the folder+filename (currently only Nikkei/Hang Seng).
        $exactCount = $this->exactMatchPass($sourceRoot, $disk, $dryRun);

        // Pass 2: category-based round-robin assignment for everything else.
        $roundRobinCount = 0;
        foreach (self::CATEGORY_FOLDERS as $categoryId => $folders) {
            $files = $this->collectFolderFiles($sourceRoot, $folders);
            if (empty($files)) {
                continue;
            }

            $articles = NewsmakerArticle::query()
                ->where('main_category_id', $categoryId)
                ->where(function ($q) {
                    $q->where('image', 'like', 'https://www.newsmaker.id/%')
                        ->orWhereNull('image')
                        ->orWhere('image', '');
                })
                ->orderBy('id')
                ->get(['id']);

            $n = count($files);
            $i = 0;
            foreach ($articles as $article) {
                $srcFile = $files[$i % $n];
                $i++;

                $localPath = $this->ensureCopied($srcFile, $sourceRoot, $disk, $dryRun);
                if (!$dryRun) {
                    NewsmakerArticle::whereKey($article->id)->update(['image' => $localPath]);
                }
                $roundRobinCount++;
            }

            $this->info(sprintf('Category %d: %d article(s) <- %d source image(s) in %s', $categoryId, $articles->count(), $n, implode(',', $folders)));
        }

        $this->newLine();
        $this->info("Exact-match assigned: {$exactCount}");
        $this->info("Round-robin assigned: {$roundRobinCount}");
        if ($dryRun) {
            $this->warn('Dry run — no database changes were made.');
        }

        return self::SUCCESS;
    }

    private function exactMatchPass(string $sourceRoot, $disk, bool $dryRun): int
    {
        $count = 0;
        $articles = NewsmakerArticle::query()
            ->where('image', 'like', 'https://www.newsmaker.id/images/%')
            ->get(['id', 'image']);

        foreach ($articles as $article) {
            $path = trim((string) parse_url($article->image, PHP_URL_PATH), '/');
            $parts = explode('/', $path);
            if (count($parts) < 3 || strtolower($parts[0]) !== 'images') {
                continue;
            }
            $folder = $parts[1];
            $filename = $parts[2];
            $srcAbs = $sourceRoot.'/'.$folder.'/'.$filename;

            if (!is_file($srcAbs)) {
                continue;
            }

            $relative = self::TARGET_DIR.'/'.$folder.'/'.$filename;
            if (!$disk->exists($relative) && !$dryRun) {
                $disk->put($relative, file_get_contents($srcAbs));
            }

            if (!$dryRun) {
                NewsmakerArticle::whereKey($article->id)->update(['image' => 'uploads/'.$relative]);
            }
            $count++;
        }

        return $count;
    }

    /** @return string[] absolute file paths */
    private function collectFolderFiles(string $sourceRoot, array $folders): array
    {
        $files = [];
        foreach ($folders as $folder) {
            $dir = $sourceRoot.'/'.$folder;
            if (!is_dir($dir)) {
                continue;
            }
            foreach (scandir($dir) as $f) {
                if ($f === '.' || $f === '..' || stripos($f, 'index.html') === 0) {
                    continue;
                }
                $full = $dir.'/'.$f;
                if (is_file($full)) {
                    $files[] = $full;
                }
            }
        }
        sort($files);

        return $files;
    }

    private function ensureCopied(string $srcAbs, string $sourceRoot, $disk, bool $dryRun): string
    {
        $relativeFromRoot = ltrim(str_replace($sourceRoot, '', $srcAbs), '/');
        $relative = self::TARGET_DIR.'/'.$relativeFromRoot;

        if (!$disk->exists($relative) && !$dryRun) {
            $disk->put($relative, file_get_contents($srcAbs));
        }

        return 'uploads/'.$relative;
    }
}
