<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BearerNewsmakerToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        $validTokens = array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('NEWSMAKER_API_TOKENS', 'NM23-8f0f24b4d56af1c3'))
        )));

        if (!in_array($bearerToken, $validTokens, true)) {
            return response()->json(['error' => 'Tidak ada akses ke API Newsmaker ini'], 401);
        }

        return $next($request);
    }
}
