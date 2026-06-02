<?php

namespace Tests\Feature;

use App\Models\EconomicCalendarCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CalendarIndexPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_index_page_filters_by_country_dropdown(): void
    {
        EconomicCalendarCategory::create([
            'country' => 'USD',
            'impact' => 'High',
            'figures' => 'US Figures',
            'sources' => 'US Source',
            'measures' => null,
            'usual_effect' => null,
            'frequency' => null,
            'next_released' => null,
            'notes' => null,
            'isBankHoliday' => false,
            'bankHolidayNote' => null,
            'why_trader_care' => null,
        ]);

        EconomicCalendarCategory::create([
            'country' => 'CHN',
            'impact' => 'Medium',
            'figures' => 'China Figures',
            'sources' => 'China Source',
            'measures' => null,
            'usual_effect' => null,
            'frequency' => null,
            'next_released' => null,
            'notes' => null,
            'isBankHoliday' => false,
            'bankHolidayNote' => null,
            'why_trader_care' => null,
        ]);

        $user = User::create([
            'username' => 'test-admin',
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'role' => 'Superadmin',
            'password' => Hash::make('password'),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('calendar.index', ['country' => 'US']));

        $response->assertOk()
            ->assertSee('US Figures')
            ->assertDontSee('China Figures')
            ->assertSee('Negara');
    }
}
