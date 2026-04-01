<?php

namespace Tests\Feature;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Models\NewsmakerSubCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsmakerApiTest extends TestCase
{
    use RefreshDatabase;

    private function newsmakerToken(): string
    {
        return collect(explode(',', (string) env('NEWSMAKER_API_TOKENS', 'NM23-8f0f24b4d56af1c3')))
            ->map(fn (string $token) => trim($token))
            ->filter()
            ->first() ?? 'NM23-8f0f24b4d56af1c3';
    }

    public function test_newsmaker_api_requires_its_own_bearer_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer SGB-c7b0604664fd48d9',
        ])->getJson('/api/v1/newsmaker/tiktok');

        $response->assertUnauthorized()
            ->assertJson([
                'error' => 'Tidak ada akses ke API Newsmaker ini',
            ]);
    }

    public function test_newsmaker_berita_api_returns_articles(): void
    {
        $mainCategory = NewsmakerMainCategory::create([
            'name' => 'Makro',
        ]);

        $subCategory = NewsmakerSubCategory::create([
            'main_category_id' => $mainCategory->id,
            'name' => 'Makro Harian',
        ]);

        $article = NewsmakerArticle::create([
            'main_category_id' => $mainCategory->id,
            'sub_category_id' => $subCategory->id,
            'image' => 'uploads/newsmaker23/sample.jpg',
            'title_id' => 'Judul Indonesia',
            'title_en' => 'English Title',
            'content_id' => 'Konten Indonesia',
            'content_en' => 'English Content',
            'author' => 'Admin',
            'source' => 'Newsmaker23',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/berita');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $article->id)
            ->assertJsonPath('data.0.title_id', 'Judul Indonesia')
            ->assertJsonPath('data.0.main_category.name', 'Makro')
            ->assertJsonPath('data.0.sub_category.name', 'Makro Harian');
    }

    public function test_newsmaker_kategori_api_returns_categories(): void
    {
        $mainCategory = NewsmakerMainCategory::create([
            'name' => 'Makro',
        ]);

        $subCategory = NewsmakerSubCategory::create([
            'main_category_id' => $mainCategory->id,
            'name' => 'Makro Harian',
        ]);

        NewsmakerArticle::create([
            'main_category_id' => $mainCategory->id,
            'sub_category_id' => $subCategory->id,
            'image' => 'uploads/newsmaker23/sample.jpg',
            'title_id' => 'Judul Indonesia',
            'title_en' => 'English Title',
            'content_id' => 'Konten Indonesia',
            'content_en' => 'English Content',
            'author' => 'Admin',
            'source' => 'Newsmaker23',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/kategori');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Makro')
            ->assertJsonPath('data.0.slug', 'makro')
            ->assertJsonPath('data.0.articles_count', 1);
    }

    public function test_newsmaker_berita_api_can_filter_by_category(): void
    {
        $macroCategory = NewsmakerMainCategory::create([
            'name' => 'Makro',
        ]);

        $goldCategory = NewsmakerMainCategory::create([
            'name' => 'Komoditas',
        ]);

        $macroSubCategory = NewsmakerSubCategory::create([
            'main_category_id' => $macroCategory->id,
            'name' => 'Makro Harian',
        ]);

        $goldSubCategory = NewsmakerSubCategory::create([
            'main_category_id' => $goldCategory->id,
            'name' => 'Emas',
        ]);

        NewsmakerArticle::create([
            'main_category_id' => $macroCategory->id,
            'sub_category_id' => $macroSubCategory->id,
            'image' => 'uploads/newsmaker23/makro.jpg',
            'title_id' => 'Makro Pagi',
            'title_en' => 'Macro Morning',
            'content_id' => 'Isi makro.',
            'content_en' => 'Macro body.',
            'author' => 'Admin',
            'source' => 'Desk Macro',
        ]);

        NewsmakerArticle::create([
            'main_category_id' => $goldCategory->id,
            'sub_category_id' => $goldSubCategory->id,
            'image' => 'uploads/newsmaker23/emas.jpg',
            'title_id' => 'Harga Emas Bergerak',
            'title_en' => 'Gold Price Moves',
            'content_id' => 'Isi emas.',
            'content_en' => 'Gold body.',
            'author' => 'Reporter',
            'source' => 'Desk Market',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/kategori/makro/berita');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('category.name', 'Makro')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title_id', 'Makro Pagi')
            ->assertJsonPath('data.0.main_category.slug', 'makro');
    }

    public function test_newsmaker_berita_detail_api_returns_single_article(): void
    {
        $mainCategory = NewsmakerMainCategory::create([
            'name' => 'Komoditas',
        ]);

        $subCategory = NewsmakerSubCategory::create([
            'main_category_id' => $mainCategory->id,
            'name' => 'Emas',
        ]);

        $article = NewsmakerArticle::create([
            'main_category_id' => $mainCategory->id,
            'sub_category_id' => $subCategory->id,
            'image' => 'uploads/newsmaker23/emas.jpg',
            'title_id' => 'Harga Emas Bergerak',
            'title_en' => 'Gold Price Moves',
            'content_id' => 'Isi berita emas.',
            'content_en' => 'Gold article body.',
            'author' => 'Reporter',
            'source' => 'Desk Market',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->newsmakerToken(),
        ])->getJson('/api/v1/newsmaker/berita/'.$article->id);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.id', $article->id)
            ->assertJsonPath('data.title_en', 'Gold Price Moves')
            ->assertJsonPath('data.main_category.slug', 'komoditas');
    }
}
