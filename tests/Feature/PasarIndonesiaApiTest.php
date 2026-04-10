<?php

namespace Tests\Feature;

use App\Models\PasarIndonesiaArticle;
use App\Models\PasarIndonesiaCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasarIndonesiaApiTest extends TestCase
{
    use RefreshDatabase;

    private function newsmakerToken(): string
    {
        return collect(explode(',', (string) env('NEWSMAKER_API_TOKENS', 'NM23-8f0f24b4d56af1c3')))
            ->map(fn (string $token) => trim($token))
            ->filter()
            ->first() ?? 'NM23-8f0f24b4d56af1c3';
    }

    private function createAuthor(): User
    {
        return User::query()->create([
            'username' => 'pasar-indonesia-admin',
            'name' => 'Pasar Indonesia Admin',
            'email' => 'pasar-indonesia@example.com',
            'role' => 'Admin',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_pasar_indonesia_kategori_api_returns_categories_with_article_counts(): void
    {
        $author = $this->createAuthor();

        $macroCategory = PasarIndonesiaCategory::query()
            ->where('slug', 'makro-ekonomi')
            ->firstOrFail();

        $sahamCategory = PasarIndonesiaCategory::query()
            ->where('slug', 'saham')
            ->firstOrFail();

        PasarIndonesiaArticle::create([
            'type' => 'berita',
            'category' => $macroCategory->slug,
            'image' => 'uploads/pasar-indonesia/berita/makro.jpg',
            'title_id' => 'Inflasi Stabil',
            'title_en' => 'Inflation Stable',
            'content_id' => 'Isi makro.',
            'content_en' => 'Macro content.',
            'author_id' => $author->id,
            'source' => 'Desk Makro',
        ]);

        PasarIndonesiaArticle::create([
            'type' => 'berita',
            'category' => $macroCategory->slug,
            'image' => 'uploads/pasar-indonesia/berita/makro-2.jpg',
            'title_id' => 'Rupiah Menguat',
            'title_en' => 'Rupiah Strengthens',
            'content_id' => 'Isi rupiah.',
            'content_en' => 'Rupiah content.',
            'author_id' => $author->id,
            'source' => 'Desk Makro',
        ]);

        PasarIndonesiaArticle::create([
            'type' => 'berita',
            'category' => $sahamCategory->slug,
            'image' => 'uploads/pasar-indonesia/berita/saham.jpg',
            'title_id' => 'IHSG Ditutup Naik',
            'title_en' => 'JCI Closes Higher',
            'content_id' => 'Isi saham.',
            'content_en' => 'Equity content.',
            'author_id' => $author->id,
            'source' => 'Desk Market',
        ]);

        PasarIndonesiaArticle::create([
            'type' => 'analisis',
            'category' => null,
            'image' => 'uploads/pasar-indonesia/analisis/analisis.jpg',
            'title_id' => 'Analisis Harian',
            'title_en' => 'Daily Analysis',
            'content_id' => 'Isi analisis.',
            'content_en' => 'Analysis content.',
            'author_id' => $author->id,
            'source' => 'Desk Analisis',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/pasar-indonesia/kategori');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('type', 'berita')
            ->assertJsonCount(8, 'data')
            ->assertJsonFragment([
                'name' => 'Makro Ekonomi',
                'slug' => 'makro-ekonomi',
                'articles_count' => 2,
            ])
            ->assertJsonFragment([
                'name' => 'Pasar Saham',
                'slug' => 'saham',
                'articles_count' => 1,
            ]);
    }
}
