<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\pivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PivotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cachedJson = $this->readCachedJson();
        if ($cachedJson !== null) {
            return response($cachedJson, 200)->header('Content-Type', 'application/json');
        }

        $pivots = pivot::all(); // Gantilah dengan query yang sesuai kebutuhan

        return response()->json([
            'Code' => 200,
            'status' => 'success',
            'data' => $pivots
        ], 200);
    }

    private function readCachedJson(): ?string
    {
        $path = 'cache/pivot.json';
        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        $json = Storage::disk('local')->get($path);
        return $json !== '' ? $json : null;
    }
}
