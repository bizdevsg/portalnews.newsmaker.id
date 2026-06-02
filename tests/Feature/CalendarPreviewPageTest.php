<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CalendarPreviewPageTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_calendar_preview_page_renders_all_period_sections(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                [
                    'id' => 1,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-26',
                    'time' => '08:30',
                    'figures' => 'Today Data',
                    'country' => 'US',
                    'impact' => 'High',
                    'sources' => 'Bureau of Labor Statistics',
                    'previous' => '2.9%',
                    'forecast' => '3.0%',
                    'actual' => '3.1%',
                ],
                [
                    'id' => 2,
                    'economic_calendar_category_id' => 11,
                    'date' => '2026-03-24',
                    'time' => '09:00',
                    'figures' => 'This Week Data',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'sources' => 'National Bureau of Statistics of China',
                    'previous' => '2.6%',
                    'forecast' => '2.8%',
                    'actual' => '3.1%',
                ],
                [
                    'id' => 3,
                    'economic_calendar_category_id' => 12,
                    'date' => '2026-03-18',
                    'time' => '10:00',
                    'figures' => 'Previous Week Data',
                    'country' => 'JP',
                    'impact' => 'Low',
                    'sources' => 'Bank of Japan',
                    'previous' => '0.1%',
                    'forecast' => '0.2%',
                    'actual' => '0.2%',
                ],
                [
                    'id' => 4,
                    'economic_calendar_category_id' => 13,
                    'date' => '2026-04-01',
                    'time' => '07:00',
                    'figures' => 'Next Week Data',
                    'country' => 'EUR',
                    'impact' => 'Medium',
                    'sources' => 'Eurostat',
                    'previous' => '1.9%',
                    'forecast' => '2.0%',
                    'actual' => '2.1%',
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this
            ->actingAs(User::factory()->make())
            ->get(route('calendar.preview'));

        $response->assertOk()
            ->assertSee('Economic Calendar')
            ->assertSee('Today')
            ->assertSee('This Week')
            ->assertSee('Previous Week')
            ->assertSee('Next Week')
            ->assertSee('Today Data')
            ->assertSee('This Week Data')
            ->assertDontSee('Previous Week Data')
            ->assertDontSee('Next Week Data');
    }

    public function test_calendar_preview_page_paginates_active_period(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');

        $data = [];
        for ($i = 1; $i <= 22; $i++) {
            $data[] = [
                'id' => $i,
                'economic_calendar_category_id' => 100 + $i,
                'date' => '2026-03-26',
                'time' => sprintf('%02d:00', $i % 24),
                'figures' => 'Today Data ' . $i,
                'country' => 'US',
                'impact' => 'Low',
                'sources' => 'Bureau of Labor Statistics',
                'previous' => '1.0%',
                'forecast' => '1.1%',
                'actual' => '1.2%',
            ];
        }

        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $firstPage = $this
            ->actingAs(User::factory()->make())
            ->get(route('calendar.preview', ['period' => 'today']));

        $firstPage->assertOk()
            ->assertSee('Today Data 1')
            ->assertSee('Today Data 10')
            ->assertDontSee('Today Data 11');

        $secondPage = $this
            ->actingAs(User::factory()->make())
            ->get(route('calendar.preview', ['period' => 'today', 'page' => 2]));

        $secondPage->assertOk()
            ->assertSee('Today Data 11')
            ->assertSee('Today Data 12')
            ->assertDontSee('Today Data 10');
    }

    public function test_calendar_preview_page_filters_by_country_dropdown(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                [
                    'id' => 1,
                    'economic_calendar_category_id' => 20,
                    'date' => '2026-03-26',
                    'time' => '08:30',
                    'figures' => 'US Data',
                    'country' => 'USD',
                    'impact' => 'High',
                    'sources' => 'BLS',
                ],
                [
                    'id' => 2,
                    'economic_calendar_category_id' => 21,
                    'date' => '2026-03-26',
                    'time' => '09:00',
                    'figures' => 'China Data',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'sources' => 'NBS',
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this
            ->actingAs(User::factory()->make())
            ->get(route('calendar.preview', [
                'period' => 'today',
                'country' => 'US',
            ]));

        $response->assertOk()
            ->assertSee('US Data')
            ->assertDontSee('China Data')
            ->assertSee('selected');
    }
}
