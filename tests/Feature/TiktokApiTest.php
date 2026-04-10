<?php

namespace Tests\Feature;

use App\Models\Tiktok;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TiktokApiTest extends TestCase
{
    use RefreshDatabase;

    private function newsmakerToken(): string
    {
        return collect(explode(',', (string) env('NEWSMAKER_API_TOKENS', 'NM23-8f0f24b4d56af1c3')))
            ->map(fn (string $token) => trim($token))
            ->filter()
            ->first() ?? 'NM23-8f0f24b4d56af1c3';
    }

    public function test_tiktok_api_returns_cached_items(): void
    {
        $item = Tiktok::query()->create([
            'title' => 'Daily Market Update',
            'embed_code' => '<blockquote>TikTok Embed</blockquote>',
            'backup_video_url' => 'https://example.com/tiktok.mp4',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/tiktok');

        $response->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('message', 'Data TikTok berhasil diambil')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $item->id)
            ->assertJsonPath('data.0.title', 'Daily Market Update')
            ->assertJsonPath('data.0.backup_video_url', 'https://example.com/tiktok.mp4');
    }
}
