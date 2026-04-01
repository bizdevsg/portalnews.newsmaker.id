<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KalenderApiPeriodTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_kalender_api_can_filter_today_period(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'date' => '2026-03-26', 'time' => '08:30', 'figures' => 'Today Data'],
                ['id' => 2, 'date' => '2026-03-24', 'time' => '08:30', 'figures' => 'This Week Data'],
                ['id' => 3, 'date' => '2026-03-18', 'time' => '08:30', 'figures' => 'Previous Week Data'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi/today');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('meta.period', 'today')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.figures', 'Today Data');
    }

    public function test_kalender_api_can_group_periods(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'date' => '2026-03-26', 'time' => '08:30', 'figures' => 'Today Data'],
                ['id' => 2, 'date' => '2026-03-24', 'time' => '08:30', 'figures' => 'This Week Data'],
                ['id' => 3, 'date' => '2026-03-18', 'time' => '08:30', 'figures' => 'Previous Week Data'],
                ['id' => 4, 'date' => '2026-04-01', 'time' => '08:30', 'figures' => 'Next Week Data'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi/periode');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data.today')
            ->assertJsonCount(2, 'data.this-week')
            ->assertJsonCount(1, 'data.previous-week')
            ->assertJsonCount(1, 'data.next-week')
            ->assertJsonPath('data.next-week.0.figures', 'Next Week Data');
    }

    public function test_kalender_api_accepts_hyphenated_period_slug(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'date' => '2026-04-01', 'time' => '08:30', 'figures' => 'Next Week Data'],
                ['id' => 2, 'date' => '2026-03-26', 'time' => '08:30', 'figures' => 'Today Data'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi/next-week');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('meta.period', 'next-week')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.figures', 'Next Week Data');
    }

    public function test_kalender_api_accepts_underscored_period_slug(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'date' => '2026-03-18', 'time' => '08:30', 'figures' => 'Previous Week Data'],
                ['id' => 2, 'date' => '2026-03-26', 'time' => '08:30', 'figures' => 'Today Data'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi/previous_week');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('meta.period', 'previous-week')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.figures', 'Previous Week Data');
    }

    public function test_kalender_api_normalizes_iso_date_to_plain_date(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'date' => '2026-03-18T17:00:00.000000Z', 'time' => '08:30', 'figures' => 'ISO Date Data'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.0.date', '2026-03-18')
            ->assertJsonPath('data.0.figures', 'ISO Date Data');
    }

    public function test_kalender_api_payload_contains_previous_history(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                [
                    'id' => 1,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-18',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'previous' => '2.6%',
                    'forecast' => '2.8%',
                    'actual' => '3.1%',
                ],
                [
                    'id' => 2,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-24',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'previous' => '3.1%',
                    'forecast' => '3.2%',
                    'actual' => '3.2%',
                ],
                [
                    'id' => 3,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-26',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'previous' => '3.2%',
                    'forecast' => '3.3%',
                    'actual' => '3.4%',
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi/today');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data')
            ->assertJsonCount(2, 'data.0.history')
            ->assertJsonPath('data.0.history.0.date', '2026-03-24')
            ->assertJsonPath('data.0.history.0.actual', '3.2%')
            ->assertJsonPath('data.0.history.1.date', '2026-03-18')
            ->assertJsonPath('data.0.history.1.actual', '3.1%');
    }

    public function test_kalender_api_history_is_limited_to_five_items(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                [
                    'id' => 1,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-18',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'actual' => '3.1%',
                ],
                [
                    'id' => 2,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-19',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'actual' => '3.2%',
                ],
                [
                    'id' => 3,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-20',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'actual' => '3.3%',
                ],
                [
                    'id' => 4,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-21',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'actual' => '3.4%',
                ],
                [
                    'id' => 5,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-22',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'actual' => '3.5%',
                ],
                [
                    'id' => 6,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-23',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'actual' => '3.6%',
                ],
                [
                    'id' => 7,
                    'economic_calendar_category_id' => 10,
                    'date' => '2026-03-26',
                    'time' => '08:30',
                    'figures' => 'CPI y/y (CHN)',
                    'country' => 'CHN',
                    'impact' => 'Medium',
                    'actual' => '3.7%',
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi/today');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonCount(5, 'data.0.history')
            ->assertJsonPath('data.0.history.0.date', '2026-03-23')
            ->assertJsonPath('data.0.history.4.date', '2026-03-19');
    }

    public function test_kalender_api_sorts_response_by_date_and_time(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'date' => '2026-03-27', 'time' => '20.30', 'figures' => 'Late Tomorrow'],
                ['id' => 2, 'date' => '2026-03-26', 'time' => 'All Day', 'figures' => 'Holiday Today', 'isBankHoliday' => true],
                ['id' => 3, 'date' => '2026-03-26', 'time' => '08:30', 'figures' => 'Morning Today'],
                ['id' => 4, 'date' => '2026-03-27', 'time' => 'Tentative', 'figures' => 'Pending Tomorrow'],
                ['id' => 5, 'date' => '2026-03-26', 'time' => '14:00', 'figures' => 'Afternoon Today'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi');

        $response->assertOk()
            ->assertJsonPath('data.0.figures', 'Morning Today')
            ->assertJsonPath('data.1.figures', 'Afternoon Today')
            ->assertJsonPath('data.2.figures', 'Holiday Today')
            ->assertJsonPath('data.3.figures', 'Late Tomorrow')
            ->assertJsonPath('data.4.figures', 'Pending Tomorrow');
    }

    public function test_kalender_api_periods_are_sorted_by_date_and_time(): void
    {
        Carbon::setTestNow('2026-03-26 10:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('cache/kalender.json', json_encode([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'date' => '2026-03-26', 'time' => '20:30', 'figures' => 'Late Today'],
                ['id' => 2, 'date' => '2026-03-26', 'time' => '08:30', 'figures' => 'Early Today'],
                ['id' => 3, 'date' => '2026-03-28', 'time' => 'Tentative', 'figures' => 'Later This Week'],
                ['id' => 4, 'date' => '2026-03-27', 'time' => 'All Day', 'figures' => 'Bank Holiday', 'isBankHoliday' => true],
                ['id' => 5, 'date' => '2026-03-27', 'time' => '09:00', 'figures' => 'Morning This Week'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/kalender-ekonomi/periode');

        $response->assertOk()
            ->assertJsonPath('data.today.0.figures', 'Early Today')
            ->assertJsonPath('data.today.1.figures', 'Late Today')
            ->assertJsonPath('data.this-week.0.figures', 'Early Today')
            ->assertJsonPath('data.this-week.1.figures', 'Late Today')
            ->assertJsonPath('data.this-week.2.figures', 'Morning This Week')
            ->assertJsonPath('data.this-week.3.figures', 'Bank Holiday')
            ->assertJsonPath('data.this-week.4.figures', 'Later This Week');
    }
}
