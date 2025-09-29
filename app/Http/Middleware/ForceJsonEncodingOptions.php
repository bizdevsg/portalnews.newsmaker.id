<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as LaravelJsonResponse;

class ForceJsonEncodingOptions
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Hanya proses JSON response
        if ($response instanceof LaravelJsonResponse) {
            // Ambil data sebagai array (true = array, false = object)
            $data    = $response->getData(true);
            $status  = $response->getStatusCode();
            $headers = $response->headers->all();

            // Bungkus ulang dengan opsi encoding non-escape
            $new = response()->json(
                $data,
                $status,
                $headers,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

            // Laravel menyalin cookies/header lain? Pastikan pindahkan cookies jika perlu
            foreach ($response->headers->getCookies() as $cookie) {
                $new->headers->setCookie($cookie);
            }

            return $new;
        }

        return $response;
    }
}
