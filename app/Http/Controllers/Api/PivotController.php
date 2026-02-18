<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pivot;
use Illuminate\Http\Request;

class PivotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pivots = Pivot::all(); // baca langsung dari database

        return response()->json([
            'Code' => 200,
            'status' => 'success',
            'data' => $pivots
        ], 200);
    }
}
