<?php

namespace Tests\Feature;

use App\Models\PopupBanner;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PopupBannerApiTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function newsmakerToken(): string
    {
        return collect(explode(',', (string) env('NEWSMAKER_API_TOKENS', 'NM23-8f0f24b4d56af1c3')))
            ->map(fn (string $token) => trim($token))
            ->filter()
            ->first() ?? 'NM23-8f0f24b4d56af1c3';
    }

    public function test_popup_banner_api_requires_newsmaker_bearer_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->getJson('/api/v1/newsmaker/popup-banner');

        $response->assertUnauthorized()
            ->assertJson([
                'error' => 'Tidak ada akses ke API Newsmaker ini',
            ]);
    }

    public function test_popup_banner_api_returns_only_active_and_scheduled_banners(): void
    {
        Carbon::setTestNow('2026-03-31 10:00:00');

        $visibleHtmlBanner = PopupBanner::create([
            'title' => 'Install App',
            'description' => 'Banner custom HTML',
            'cta_label' => 'Download',
            'cta_url' => 'https://example.com/app',
            'modal_html' => '<div class="modal"><h2>Install App</h2></div>',
            'is_active' => true,
            'sort_order' => 1,
            'start_at' => '2026-03-31 08:00:00',
            'end_at' => '2026-03-31 23:00:00',
        ]);

        $visibleStandardBanner = PopupBanner::create([
            'title' => 'Promo Event',
            'description' => 'Banner standar',
            'image' => 'uploads/popup-banners/promo-event.jpg',
            'cta_label' => 'Lihat Event',
            'cta_url' => 'https://example.com/event',
            'is_active' => true,
            'sort_order' => 2,
            'start_at' => null,
            'end_at' => null,
        ]);

        PopupBanner::create([
            'title' => 'Inactive Banner',
            'is_active' => false,
            'sort_order' => 0,
        ]);

        PopupBanner::create([
            'title' => 'Future Banner',
            'is_active' => true,
            'sort_order' => 3,
            'start_at' => '2026-04-01 08:00:00',
        ]);

        PopupBanner::create([
            'title' => 'Expired Banner',
            'is_active' => true,
            'sort_order' => 4,
            'end_at' => '2026-03-31 09:00:00',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/popup-banner');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $visibleHtmlBanner->id)
            ->assertJsonPath('data.0.title', 'Install App')
            ->assertJsonPath('data.0.design_mode', 'html')
            ->assertJsonPath('data.0.has_custom_html', true)
            ->assertJsonPath('data.0.modal_html', '<div class="modal"><h2>Install App</h2></div>')
            ->assertJsonPath('data.1.id', $visibleStandardBanner->id)
            ->assertJsonPath('data.1.title', 'Promo Event')
            ->assertJsonPath('data.1.design_mode', 'standard')
            ->assertJsonPath('data.1.has_custom_html', false)
            ->assertJsonPath('data.1.image_url', asset('uploads/popup-banners/promo-event.jpg'));
    }
}
