<?php

namespace App\Console\Commands;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Models\NewsmakerSubCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateRailwayToNewsmaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'railway:migrate-to-newsmaker {--commit : Actually write to the database. Without this flag, runs as a dry-run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One-off migration of articles from the Railway news dump (news table, via the railway_source connection) into newsmaker_articles.';

    private const SOURCE = 'Migrasi Railway News';

    private const DEFAULT_AUTHOR = 'Newsmaker23';

    private array $mapping;

    private array $mainIds = [];

    private array $subIds = [];

    private array $categoryLabels = [];

    /** @var array<string,true> lowercase, trimmed title already present (existing + inserted this run) */
    private array $seenTitles = [];

    /** @var array<string,true> slugs already present (existing + inserted this run) */
    private array $seenSlugs = [];

    public function handle(): int
    {
        $commit = (bool) $this->option('commit');

        $this->mapping = require base_path('db/railway-migration/railway_category_mapping.php');

        $this->info($commit ? 'Running migration in COMMIT mode.' : 'Running migration in DRY-RUN mode (pass --commit to write).');

        $this->resolveCategories($commit);

        $this->preloadExisting();

        $stats = [
            'to_insert' => 0,
            'skipped_empty_content' => 0,
            'skipped_duplicate_title' => 0,
            'skipped_unmapped_category' => 0,
            'title_truncated' => 0,
        ];
        $perCategory = [];
        $unmapped = [];
        $batch = [];

        DB::connection('railway_source')->table('news')
            ->orderBy('id')
            ->chunk(500, function ($rows) use (&$stats, &$perCategory, &$unmapped, &$batch, $commit) {
                foreach ($rows as $row) {
                    $catKey = strtoupper(trim((string) $row->category));

                    if (! isset($this->mapping[$catKey])) {
                        $stats['skipped_unmapped_category']++;
                        $unmapped[$catKey] = ($unmapped[$catKey] ?? 0) + 1;

                        continue;
                    }

                    $content = trim((string) $row->summary)."\n\n".trim((string) $row->detail);
                    $content = trim($content);

                    if ($content === '') {
                        $stats['skipped_empty_content']++;

                        continue;
                    }

                    $title = trim((string) $row->title);
                    $titleKey = mb_strtolower($title);

                    if (isset($this->seenTitles[$titleKey])) {
                        $stats['skipped_duplicate_title']++;

                        continue;
                    }

                    $this->seenTitles[$titleKey] = true;

                    if (mb_strlen($title) > 150) {
                        $title = mb_substr($title, 0, 150);
                        $stats['title_truncated']++;
                    }

                    $mainId = $this->mainIds[$catKey];
                    $subId = $this->subIds[$catKey];
                    $createdAt = $this->cleanDate($row->createdAt) ?? now();
                    $updatedAt = $this->cleanDate($row->updatedAt) ?? $createdAt;
                    $slug = $this->uniqueSlug($title, $createdAt);
                    $author = trim((string) $row->author_name) ?: self::DEFAULT_AUTHOR;
                    $authorInitial = trim((string) $row->author) ?: null;

                    $stats['to_insert']++;
                    $perCategory[$mainId] = ($perCategory[$mainId] ?? 0) + 1;

                    if ($commit) {
                        $batch[] = [
                            'main_category_id' => $mainId,
                            'sub_category_id' => $subId,
                            'image' => (string) $row->image,
                            'title_id' => $title,
                            'title_en' => $title,
                            'notif' => 0,
                            'slug' => $slug,
                            'content_id' => $content,
                            'content_en' => $content,
                            'author' => $author,
                            'author_id' => null,
                            'author_initial' => $authorInitial ? Str::upper(Str::limit($authorInitial, 10, '')) : null,
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
        $this->line("Skipped (unmapped category): {$stats['skipped_unmapped_category']}");
        $this->line("Titles truncated to 150 chars: {$stats['title_truncated']}");

        if (! empty($unmapped)) {
            $this->newLine();
            $this->warn('Unmapped categories (add to db/railway-migration/railway_category_mapping.php):');
            $this->table(
                ['category', 'count'],
                collect($unmapped)->map(fn ($count, $cat) => [$cat, $count])->sortByDesc(1)->values()->all()
            );
        }

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
        $pairs = [];
        foreach ($this->mapping as $catKey => $def) {
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
                $mainId = $main?->id ?? (-1 * (crc32($def['main']) % 100000));
                $subId = $sub?->id ?? (-1 * (crc32($def['main'].'/'.$def['sub']) % 100000));
            }

            $resolvedPairs[$key] = ['main_id' => $mainId, 'sub_id' => $subId];
            $this->categoryLabels[$mainId] = $def['main'].(($main ?? null) ? '' : ' (new)');
        }

        foreach ($this->mapping as $catKey => $def) {
            $resolved = $resolvedPairs[$def['main'].'|||'.$def['sub']];
            $this->mainIds[$catKey] = $resolved['main_id'];
            $this->subIds[$catKey] = $resolved['sub_id'];
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

    private function cleanDate(?string $value): ?string
    {
        if (! $value || str_starts_with($value, '0000-00-00')) {
            return null;
        }

        // TIMESTAMP columns can't hold values at/before the Unix epoch (UTC);
        // a handful of dump rows have this as a bogus default date.
        if (strtotime($value) <= 0) {
            return null;
        }

        return $value;
    }
}
