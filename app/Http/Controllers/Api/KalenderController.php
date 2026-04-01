<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EconomicCalendarPayloadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KalenderController extends Controller
{
    private const PER_PAGE = 20;

    public function __construct(
        private readonly EconomicCalendarPayloadService $payloadService
    ) {
    }

    /**
     * Tampilkan daftar kalender ekonomi dalam format JSON.
     */
    public function index(Request $request, ?string $period = null): JsonResponse
    {
        $data = $this->payloadService->getPreparedData();
        if ($data === null) {
            return response()->json(
                ['status' => 'error', 'message' => 'Cache kalender belum tersedia.'],
                503
            );
        }

        $period = $this->payloadService->normalizePeriod($period);

        if ($period !== null && !in_array($period, $this->payloadService->availablePeriods(), true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Period tidak valid.',
                'available_periods' => $this->payloadService->availablePeriods(),
            ], 404);
        }

        if ($period !== null) {
            $data = $this->payloadService->filterByPeriod($data, $period);
        }

        $data = $this->payloadService->sortForApiByDateAndTime($data);
        $paginator = $this->paginateData($data, $request);

        return response()->json(
            [
                'status' => 'success',
                'data' => $paginator->items(),
                'meta' => array_merge(
                    $this->payloadService->buildMeta($period),
                    [
                        'pagination' => $this->buildPaginationMeta($paginator),
                    ]
                ),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function periods(): JsonResponse
    {
        $data = $this->payloadService->getPreparedData();
        if ($data === null) {
            return response()->json(
                ['status' => 'error', 'message' => 'Cache kalender belum tersedia.'],
                503
            );
        }

        $groupedData = array_map(
            fn (array $items): array => $this->payloadService->sortForApiByDateAndTime($items),
            $this->payloadService->groupByPeriods($data)
        );

        return response()->json(
            [
                'status' => 'success',
                'data' => $groupedData,
                'meta' => $this->payloadService->buildPeriodsMeta(),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function paginateData(array $items, Request $request): LengthAwarePaginator
    {
        $page = max((int) $request->query('page', 1), 1);
        $items = array_values($items);

        return new LengthAwarePaginator(
            array_values(array_slice($items, ($page - 1) * self::PER_PAGE, self::PER_PAGE)),
            count($items),
            self::PER_PAGE,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    private function buildPaginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_more_pages' => $paginator->hasMorePages(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'next_page_url' => $paginator->nextPageUrl(),
        ];
    }
}
