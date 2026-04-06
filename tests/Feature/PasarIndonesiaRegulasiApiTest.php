<?php

namespace Tests\Feature;

use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasarIndonesiaRegulasiApiTest extends TestCase
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
            'username' => 'regulasi-admin',
            'name' => 'Regulasi Admin',
            'email' => 'regulasi@example.com',
            'role' => 'Admin',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_regulasi_institusi_api_returns_articles_with_category_filter(): void
    {
        $author = $this->createAuthor();

        $ojkCategory = PasarIndonesiaRegulasiInstitusiCategory::create([
            'name' => 'OJK',
        ]);

        $biCategory = PasarIndonesiaRegulasiInstitusiCategory::create([
            'name' => 'Bank Indonesia',
        ]);

        $article = PasarIndonesiaRegulasiInstitusiArticle::create([
            'author_id' => $author->id,
            'category' => $ojkCategory->slug,
            'image' => 'uploads/pasar-indonesia/regulasi-institusi/ojk.jpg',
            'title_id' => 'Aturan OJK Baru',
            'title_en' => 'New OJK Rule',
            'content_id' => 'Isi regulasi OJK.',
            'content_en' => 'OJK regulation content.',
            'source' => 'OJK',
        ]);

        PasarIndonesiaRegulasiInstitusiArticle::create([
            'author_id' => $author->id,
            'category' => $biCategory->slug,
            'image' => 'uploads/pasar-indonesia/regulasi-institusi/bi.jpg',
            'title_id' => 'Kebijakan BI Terbaru',
            'title_en' => 'Latest BI Policy',
            'content_id' => 'Isi kebijakan BI.',
            'content_en' => 'BI policy content.',
            'source' => 'Bank Indonesia',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/pasar-indonesia/regulasi-institusi?category='.$ojkCategory->slug);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('type', 'regulasi-institusi')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $article->id)
            ->assertJsonPath('data.0.title_id', 'Aturan OJK Baru')
            ->assertJsonPath('data.0.category', $ojkCategory->slug)
            ->assertJsonPath('data.0.category_label', 'OJK')
            ->assertJsonPath('data.0.author.name', 'Regulasi Admin')
            ->assertJsonPath('meta.filters.category', $ojkCategory->slug)
            ->assertJsonCount(3, 'meta.available_categories')
            ->assertJsonFragment([
                'value' => $ojkCategory->slug,
                'label' => 'OJK',
            ]);
    }

    public function test_regulasi_institusi_detail_api_returns_single_article(): void
    {
        $author = $this->createAuthor();

        $category = PasarIndonesiaRegulasiInstitusiCategory::create([
            'name' => 'Kementerian Keuangan',
        ]);

        $article = PasarIndonesiaRegulasiInstitusiArticle::create([
            'author_id' => $author->id,
            'category' => $category->slug,
            'image' => 'uploads/pasar-indonesia/regulasi-institusi/kemenkeu.jpg',
            'title_id' => 'Peraturan Fiskal Baru',
            'title_en' => 'New Fiscal Regulation',
            'content_id' => 'Isi peraturan fiskal.',
            'content_en' => 'Fiscal regulation content.',
            'source' => 'Kemenkeu',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/pasar-indonesia/regulasi-institusi/'.$article->slug);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('type', 'regulasi-institusi')
            ->assertJsonPath('data.id', $article->id)
            ->assertJsonPath('data.slug', $article->slug)
            ->assertJsonPath('data.category_label', 'Kementerian Keuangan')
            ->assertJsonPath('data.author.email', 'regulasi@example.com');
    }
}
