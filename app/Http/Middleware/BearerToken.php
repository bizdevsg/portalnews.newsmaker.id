<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mendapatkan Bearer Token dari header Authorization
        $bearerToken = $request->bearerToken(); // Tidak menggunakan strtolower() untuk case-sensitive

        // Daftar token yang valid (case-sensitive)
        $validTokens = [
            'SGB-c7b0604664fd48d9', // PT. Solid Gold Berjanka
            'RFB-115886a7f25067f3', // PT. Rifan Financindo Berjangka
            'KPF-ae8aad2d2303b00f', // PT. Kontak Perkasa Futures
            'EWF-06433b884f930161', // PT. Equity World Futures
            'BPF-91e516ac4fe2e8ae'  // PT. Best Profit Futures
        ];

        // Memeriksa apakah token yang diterima valid (case-sensitive)
        if (!in_array($bearerToken, $validTokens)) {
            return response()->json(['error' => 'Tidak ada akses ke API ini'], 401);
        }

        // Melanjutkan request jika token valid
        return $next($request);
    }
}
