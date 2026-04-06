<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PasarIndonesiaSidebarActiveStateTest extends TestCase
{
    public function test_pasar_indonesia_sidebar_link_is_active_on_regulasi_institusi_routes(): void
    {
        Route::view('/__tests/sidebar/regulasi-institusi', 'components.app.sidebar', ['variant' => 'v1'])
            ->name('regulasi-institusi.sidebar-test');

        $response = $this
            ->actingAs(User::factory()->make())
            ->get('/__tests/sidebar/regulasi-institusi');

        $response->assertOk();

        $this->assertMatchesRegularExpression(
            sprintf(
                '/<a href="%s"[^>]*%s/s',
                preg_quote(route('pasar-indonesia.index'), '/'),
                preg_quote('border border-slate-900 bg-slate-900 text-white shadow-sm', '/')
            ),
            $response->getContent()
        );
    }
}
