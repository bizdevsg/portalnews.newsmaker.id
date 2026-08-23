<?php

namespace App\Console\Commands;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Models\NewsmakerSubCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateJoomlaToNewsmaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joomla:migrate-to-newsmaker {--commit : Actually write to the database. Without this flag, runs as a dry-run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One-off migration of published articles from the legacy Joomla dump (s3cur3_content, via the joomla_source connection) into newsmaker_articles.';

    private const AUTHOR = 'MRV';

    private const SOURCE = 'Arsip Newsmaker23';

    private array $mapping;

    private array $mainIds = [];

    private array $subIds = [];

    private array $categoryLabels = [];

    /** @var array<string,true> lowercase, trimmed title_id already present (existing + inserted this run) */
    private array $seenTitles = [];

    /** @var array<string,true> slugs already present (existing + inserted this run) */
    private array $seenSlugs = [];

    public function handle(): int
    {
        $commit = (bool) $this->option('commit');

        $this->mapping = require base_path('db/joomla-migration/newsmaker_category_mapping.php');
        $catids = array_keys($this->mapping);

        $this->info($commit ? 'Running migration in COMMIT mode.' : 'Running migration in DRY-RUN mode (pass --commit to write).');

        $this->resolveCategories($commit);

        $this->preloadExisting();

        $stats = [
            'to_insert' => 0,
            'skipped_empty_content' => 0,
            'skipped_duplicate_title' => 0,
            'title_truncated' => 0,
        ];
        $perCategory = [];
        $batch = [];

        DB::connection('joomla_source')->table('s3cur3_content')
            ->whereIn('catid', $catids)
            ->where('state', 1)
            ->orderBy('id')
            ->chunk(500, function ($rows) use (&$stats, &$perCategory, &$batch, $commit) {
                foreach ($rows as $row) {
                    $content = trim($this->fixMojibake((string) $row->introtext)).trim($this->fixMojibake((string) $row->fulltext));

                    if ($content === '') {
                        $stats['skipped_empty_content']++;

                        continue;
                    }

                    $title = $this->fixMojibake($row->title);
                    $titleKey = mb_strtolower(trim($title));

                    if (isset($this->seenTitles[$titleKey])) {
                        $stats['skipped_duplicate_title']++;

                        continue;
                    }

                    $this->seenTitles[$titleKey] = true;

                    if (mb_strlen($title) > 150) {
                        $title = mb_substr($title, 0, 150);
                        $stats['title_truncated']++;
                    }

                    $mainId = $this->mainIds[$row->catid];
                    $subId = $this->subIds[$row->catid];
                    $createdAt = $this->cleanDate($row->created) ?? now();
                    $updatedAt = $this->cleanDate($row->modified) ?? $createdAt;
                    $slug = $this->uniqueSlug($title, $createdAt);

                    $stats['to_insert']++;
                    $perCategory[$mainId] = ($perCategory[$mainId] ?? 0) + 1;

                    if ($commit) {
                        $batch[] = [
                            'main_category_id' => $mainId,
                            'sub_category_id' => $subId,
                            'image' => '',
                            'title_id' => $title,
                            'title_en' => $title,
                            'notif' => 0,
                            'slug' => $slug,
                            'content_id' => $content,
                            'content_en' => $content,
                            'author' => self::AUTHOR,
                            'author_id' => null,
                            'author_initial' => self::AUTHOR,
                            'source' => self::SOURCE,
                            'created_at' => $createdAt,
                            'updated_at' => $updatedAt,
                        ];

                        if (count($batch) >= 500) {
                            DB::transaction(fn () => NewsmakerArticle::insert($batch));
                            $batch = [];
                        }
                    }
                }
            });

        if ($commit && ! empty($batch)) {
            DB::transaction(fn () => NewsmakerArticle::insert($batch));
        }

        $this->newLine();
        $this->info('=== Summary ===');
        $this->line("Articles inserted".($commit ? '' : ' (would insert)').": {$stats['to_insert']}");
        $this->line("Skipped (empty content): {$stats['skipped_empty_content']}");
        $this->line("Skipped (duplicate title): {$stats['skipped_duplicate_title']}");
        $this->line("Titles truncated to 150 chars: {$stats['title_truncated']}");

        $this->newLine();
        $this->table(
            ['main_category_id', 'main_category', 'count'],
            collect($perCategory)->map(fn ($count, $mainId) => [
                $mainId,
                $this->categoryLabels[$mainId] ?? '(unknown)',
                $count,
            ])->sortByDesc(2)->values()->all()
        );

        if (! $commit) {
            $this->newLine();
            $this->comment('Dry-run only — nothing was written. Re-run with --commit to insert for real.');
        }

        return self::SUCCESS;
    }

    private function resolveCategories(bool $commit): void
    {
        // Collapse the mapping to unique (main, sub) pairs first so we only
        // create/lookup each category once even if several catids share it.
        $pairs = [];
        foreach ($this->mapping as $catid => $def) {
            $pairs[$def['main'].'|||'.$def['sub']] = $def;
        }

        $resolvedPairs = [];

        foreach ($pairs as $key => $def) {
            if ($commit) {
                $main = NewsmakerMainCategory::firstOrCreate(['name' => $def['main']]);
                $sub = NewsmakerSubCategory::firstOrCreate([
                    'main_category_id' => $main->id,
                    'name' => $def['sub'],
                ]);
                $mainId = $main->id;
                $subId = $sub->id;
            } else {
                $main = NewsmakerMainCategory::where('name', $def['main'])->first();
                $sub = $main
                    ? NewsmakerSubCategory::where('main_category_id', $main->id)->where('name', $def['sub'])->first()
                    : null;
                // Dry-run: fake negative ids for not-yet-created rows so the summary can still group by them.
                $mainId = $main?->id ?? (-1 * (crc32($def['main']) % 100000));
                $subId = $sub?->id ?? (-1 * (crc32($def['main'].'/'.$def['sub']) % 100000));
            }

            $resolvedPairs[$key] = ['main_id' => $mainId, 'sub_id' => $subId];
            $this->categoryLabels[$mainId] = $def['main'].(($main ?? null) ? '' : ' (new)');
        }

        foreach ($this->mapping as $catid => $def) {
            $resolved = $resolvedPairs[$def['main'].'|||'.$def['sub']];
            $this->mainIds[$catid] = $resolved['main_id'];
            $this->subIds[$catid] = $resolved['sub_id'];
        }
    }

    private function preloadExisting(): void
    {
        NewsmakerArticle::query()->select('title_id', 'slug')->orderBy('id')->chunk(1000, function ($rows) {
            foreach ($rows as $row) {
                $this->seenTitles[mb_strtolower(trim($row->title_id))] = true;
                if ($row->slug) {
                    $this->seenSlugs[$row->slug] = true;
                }
            }
        });
    }

    private function uniqueSlug(string $title, $date): string
    {
        $titleSlug = Str::slug($title) ?: 'berita-newsmaker';
        $basePrefix = $date instanceof \DateTimeInterface ? $date->format('dmY') : now()->format('dmY');
        $base = $basePrefix.'-'.$titleSlug;
        $slug = $base;
        $suffix = 1;

        while (isset($this->seenSlugs[$slug])) {
            $slug = $base.'-'.$suffix++;
        }

        $this->seenSlugs[$slug] = true;

        return Str::limit($slug, 180, '');
    }

    /**
     * A handful of Joomla rows got their text UTF-8 encoded multiple times over the years
     * (curly quotes etc turning into "Ã¢â‚¬Ëœ" style garbage). Undo it by repeatedly
     * re-interpreting the bytes as Windows-1252 -> UTF-8 until it stops shrinking or
     * stops being valid UTF-8, whichever comes first. Cheap no-op for normal text.
     */
    private function fixMojibake(string $s): string
    {
        if (! str_contains($s, 'Ã')) {
            return $s;
        }

        $prev = $s;

        for ($i = 0; $i < 6; $i++) {
            $candidate = @iconv('UTF-8', 'Windows-1252', $prev);

            if ($candidate === false || ! mb_check_encoding($candidate, 'UTF-8')) {
                break;
            }

            if (strlen($candidate) >= strlen($prev)) {
                break;
            }

            $prev = $candidate;
        }

        return $prev;
    }

    private function cleanDate(?string $value): ?string
    {
        if (! $value || str_starts_with($value, '0000-00-00')) {
            return null;
        }

        return $value;
    }
}
