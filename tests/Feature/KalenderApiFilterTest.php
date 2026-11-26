<?php

namespace Tests\Feature;

use App\Http\Middleware\BearerNewsmakerToken;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class KalenderApiFilterTest extends TestCase
{
    private const URL = '/api/v1/newsmaker/kalender-ekonomi';

    protected function setUp(): void
    {
        parent::setUp();

        // Database di memori, supaya database asli tidak tersentuh.
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.url' => null,
        ]);
        DB::purge('sqlite');

        Schema::create('economic_calendars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('economic_calendar_category_id')->nullable();
            $table->date('date')->nullable();
            $table->boolean('isBankHoliday')->nullable();
            foreach ([
                'time', 'country', 'impact', 'figures', 'previous', 'forecast', 'actual', 'sources',
                'measures', 'usual_effect', 'frequency', 'next_released', 'notes', 'bankHolidayNote',
                'why_trader_care', 'created_at', 'updated_at',
            ] as $column) {
                $table->string($column)->nullable();
            }
        });

        Carbon::setTestNow('2026-03-26 10:00:00');
        CarbonImmutable::setTestNow('2026-03-26 10:00:00');

        $this->withoutMiddleware(BearerNewsmakerToken::class);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    private function seedEvent(array $attributes): void
    {
        DB::table('economic_calendars')->insert(array_merge([
            'date' => '2026-03-26',
            'time' => '08:30',
            'country' => 'US',
            'impact' => 'High',
            'figures' => 'Event',
        ], $attributes));
    }

    private function seedMixedEvents(): void
    {
        $this->seedEvent(['figures' => 'US High', 'country' => 'US', 'impact' => 'High', 'date' => '2026-03-24']);
        $this->seedEvent(['figures' => 'USD Medium', 'country' => 'USD', 'impact' => 'Medium', 'date' => '2026-03-25']);
        $this->seedEvent(['figures' => 'EUR Low', 'country' => 'EUR', 'impact' => 'Low', 'date' => '2026-03-26']);
        $this->seedEvent(['figures' => 'JP High', 'country' => 'JP', 'impact' => 'High', 'date' => '2026-04-02']);
    }

    private function figuresFor(string $query): array
    {
        return $this->getJson(self::URL.$query)->assertOk()->json('data.*.figures') ?? [];
    }

    public function test_without_filters_returns_all_events_with_default_page_size(): void
    {
        $this->seedMixedEvents();

        $this->getJson(self::URL)
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonPath('meta.pagination.per_page', 20)
            ->assertJsonPath('meta.filters.impact', null)
            ->assertJsonPath('meta.filters.country', null);
    }

    public function test_filters_by_one_or_many_impacts_case_insensitively(): void
    {
        $this->seedMixedEvents();

        $this->assertSame(['US High', 'JP High'], $this->figuresFor('?impact=high'));
        $this->assertSame(['US High', 'USD Medium', 'JP High'], $this->figuresFor('?impact=High,MEDIUM'));
    }

    public function test_country_filter_matches_currency_and_country_codes(): void
    {
        $this->seedMixedEvents();

        $this->assertSame(['US High', 'USD Medium'], $this->figuresFor('?country=usd'));
        $this->assertSame(['US High', 'USD Medium', 'EUR Low'], $this->figuresFor('?country=US,EU'));
    }

    public function test_response_country_uses_the_same_code_as_the_parameter(): void
    {
        $this->seedMixedEvents();

        $this->assertSame(
            ['USD', 'USD', 'EUR', 'JPN'],
            $this->getJson(self::URL)->assertOk()->json('data.*.country')
        );

        $this->assertSame('USD', $this->getJson(self::URL.'/periode')->json('data.this-week.1.country'));
    }

    public function test_every_country_button_in_the_app_design_returns_the_same_code(): void
    {
        // Ejaan di database sengaja dibuat beda dari kode di desain.
        foreach (['US', 'GB', 'CH', 'EU', 'AU', 'JP', 'CN'] as $stored) {
            $this->seedEvent(['country' => $stored]);
        }

        foreach (['USD', 'GBP', 'CHF', 'EUR', 'AUD', 'JPN', 'CHN'] as $code) {
            $this->getJson(self::URL."?country=$code")
                ->assertOk()
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.country', $code)
                ->assertJsonPath('meta.filters.country', [$code]);
        }
    }

    public function test_filters_by_inclusive_date_range(): void
    {
        $this->seedMixedEvents();

        $this->assertSame(['USD Medium', 'EUR Low'], $this->figuresFor('?date_from=2026-03-25&date_to=2026-03-26'));
        $this->assertSame(['JP High'], $this->figuresFor('?date_from=2026-03-27'));
        $this->assertSame(['US High'], $this->figuresFor('?date_to=2026-03-24'));
    }

    public function test_filters_combine_with_period(): void
    {
        $this->seedMixedEvents();

        $this->assertSame(['US High'], $this->figuresFor('/this-week?impact=High'));
        $this->assertSame([], $this->figuresFor('/this-week?date_from=2026-04-01'));
    }

    public function test_periods_endpoint_applies_impact_and_country_to_every_period(): void
    {
        $this->seedMixedEvents();

        $response = $this->getJson(self::URL.'/periode?impact=High&country=USD,JPN')
            ->assertOk()
            ->assertJsonPath('meta.filters.impact', ['High'])
            ->assertJsonPath('meta.filters.country', ['USD', 'JPN']);

        $this->assertSame([], $response->json('data.today'));
        $this->assertSame(['US High'], array_column($response->json('data.this-week'), 'figures'));
        $this->assertSame(['JP High'], array_column($response->json('data.next-week'), 'figures'));
        $this->assertSame([], $response->json('data.previous-week'));
    }

    public function test_periods_endpoint_date_range_narrows_each_period(): void
    {
        $this->seedMixedEvents();

        $response = $this->getJson(self::URL.'/periode?date_from=2026-03-25&date_to=2026-03-26')->assertOk();

        $this->assertSame(['USD Medium', 'EUR Low'], array_column($response->json('data.this-week'), 'figures'));
        $this->assertSame([], $response->json('data.next-week'));
    }

    public function test_periods_endpoint_without_filters_reports_null_filters(): void
    {
        $this->seedMixedEvents();

        $this->getJson(self::URL.'/periode')
            ->assertOk()
            ->assertJsonCount(3, 'data.this-week')
            ->assertJsonPath('meta.filters', [
                'impact' => null, 'country' => null, 'date_from' => null, 'date_to' => null,
            ]);
    }

    public function test_periods_endpoint_rejects_invalid_filters(): void
    {
        $this->getJson(self::URL.'/periode?impact=Extreme')
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['impact']]);
    }

    public function test_per_page_is_applied_and_filters_survive_in_pagination_links(): void
    {
        $this->seedMixedEvents();

        $response = $this->getJson(self::URL.'?impact=High&per_page=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.pagination.per_page', 1)
            ->assertJsonPath('meta.pagination.total', 2)
            ->assertJsonPath('meta.filters.impact', ['High']);

        $nextUrl = $response->json('meta.pagination.next_page_url');
        $this->assertStringContainsString('impact=High', $nextUrl);
        $this->assertStringContainsString('per_page=1', $nextUrl);
    }

    /**
     * @dataProvider invalidQueryProvider
     */
    public function test_rejects_invalid_filters(string $query, string $field): void
    {
        $this->getJson(self::URL.$query)
            ->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['message', 'errors' => [$field]]);
    }

    public static function invalidQueryProvider(): array
    {
        return [
            'unknown impact' => ['?impact=Extreme', 'impact'],
            'impact as array' => ['?impact[]=High', 'impact'],
            'country as array' => ['?country[]=US', 'country'],
            'wrong date format' => ['?date_from=26-03-2026', 'date_from'],
            'reversed range' => ['?date_from=2026-03-26&date_to=2026-03-20', 'date_to'],
            'per_page above max' => ['?per_page=101', 'per_page'],
            'per_page below min' => ['?per_page=0', 'per_page'],
        ];
    }

    public function test_history_is_per_category_limited_to_five_and_newest_first(): void
    {
        foreach (range(18, 23) as $day) {
            $this->seedEvent(['economic_calendar_category_id' => 10, 'date' => "2026-03-$day"]);
        }
        $this->seedEvent(['economic_calendar_category_id' => 20, 'date' => '2026-03-19']);
        $this->seedEvent(['economic_calendar_category_id' => 10, 'date' => '2026-03-26', 'figures' => 'A']);
        $this->seedEvent(['economic_calendar_category_id' => 20, 'date' => '2026-03-26', 'figures' => 'B']);
        $this->seedEvent(['economic_calendar_category_id' => null, 'date' => '2026-03-26', 'figures' => 'C']);

        $data = collect($this->getJson(self::URL.'/today')->assertOk()->json('data'))->keyBy('figures');

        $this->assertSame(
            ['2026-03-23', '2026-03-22', '2026-03-21', '2026-03-20', '2026-03-19'],
            array_column($data['A']['history'], 'date')
        );
        $this->assertSame(['2026-03-19'], array_column($data['B']['history'], 'date'));
        $this->assertSame([], $data['C']['history']);
    }

    public function test_history_uses_a_single_query_regardless_of_page_size(): void
    {
        foreach (range(1, 10) as $categoryId) {
            $this->seedEvent(['economic_calendar_category_id' => $categoryId]);
        }

        DB::enableQueryLog();
        $this->getJson(self::URL.'/today?per_page=100')->assertOk()->assertJsonCount(10, 'data');

        // Satu query untuk daftar event, satu untuk seluruh history.
        $this->assertCount(2, DB::getQueryLog());
    }
}
