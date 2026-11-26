<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EconomicCalendarPayloadService;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class KalenderController extends Controller
{
    private const PER_PAGE = 20;

    private const MAX_PER_PAGE = 100;

    private const IMPACTS = ['Low', 'Medium', 'High'];

    public function __construct(
        private readonly EconomicCalendarPayloadService $payloadService
    ) {
    }

    /**
     * Tampilkan daftar kalender ekonomi dalam format JSON.
     */
    public function index(Request $request, ?string $period = null): JsonResponse
    {
        $period = $this->payloadService->normalizePeriod($period);

        if ($period !== null && !in_array($period, $this->payloadService->availablePeriods(), true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Period tidak valid.',
                'available_periods' => $this->payloadService->availablePeriods(),
            ], 404);
        }

        $validator = $this->makeFilterValidator($request);

        if ($validator->fails()) {
            return $this->invalidFilterResponse($validator);
        }

        $filters = $this->buildFilters($validator->validated());
        $perPage = (int) ($request->query('per_page') ?: self::PER_PAGE);

        $data = $this->payloadService->fetchItems($period, $filters);
        $data = $this->payloadService->sortForApiByDateAndTime($data);
        $paginator = $this->paginateData($data, $request, $perPage);
        $items = $this->payloadService->attachHistoryForItems($paginator->items());

        return response()->json(
            [
                'status' => 'success',
                'data' => $items,
                'meta' => array_merge(
                    $this->payloadService->buildMeta($period),
                    [
                        'filters' => $filters,
                        'pagination' => $this->buildPaginationMeta($paginator),
                    ]
                ),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Semua periode sekaligus. Filter berlaku untuk tiap periode, tanpa pagination.
     */
    public function periods(Request $request): JsonResponse
    {
        $validator = $this->makeFilterValidator($request, withPerPage: false);

        if ($validator->fails()) {
            return $this->invalidFilterResponse($validator);
        }

        $filters = $this->buildFilters($validator->validated());
        $groupedData = [];

        foreach ($this->payloadService->availablePeriods() as $period) {
            $items = $this->payloadService->fetchItems($period, $filters);
            $items = $this->payloadService->sortForApiByDateAndTime($items);
            $groupedData[$period] = $this->payloadService->attachHistoryForItems($items);
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => $groupedData,
                'meta' => array_merge(
                    $this->payloadService->buildPeriodsMeta(),
                    ['filters' => $filters]
                ),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function invalidFilterResponse(ValidatorContract $validator): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Parameter filter tidak valid.',
            'errors' => $validator->errors(),
        ], 422);
    }

    private function makeFilterValidator(Request $request, bool $withPerPage = true): ValidatorContract
    {
        $perPageRules = $withPerPage
            ? ['per_page' => ['nullable', 'integer', 'between:1,'.self::MAX_PER_PAGE]]
            : [];

        $dateToRules = ['nullable', 'date_format:Y-m-d'];

        if ($request->filled('date_from')) {
            $dateToRules[] = 'after_or_equal:date_from';
        }

        return Validator::make($request->query(), [
            'impact' => ['nullable', 'bail', 'string', function (string $attribute, mixed $value, \Closure $fail): void {
                $unknown = array_diff($this->parseImpacts($value), self::IMPACTS);

                if ($unknown !== []) {
                    $fail('Impact hanya boleh: '.implode(', ', self::IMPACTS).'.');
                }
            }],
            'country' => ['nullable', 'string', 'max:200'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => $dateToRules,
            ...$perPageRules,
        ], [
            'string' => 'Parameter :attribute harus berupa teks. Pisahkan beberapa nilai dengan koma.',
            'max' => 'Parameter :attribute maksimal :max karakter.',
            'date_format' => 'Parameter :attribute harus berformat YYYY-MM-DD.',
            'after_or_equal' => 'Parameter date_to tidak boleh sebelum date_from.',
            'integer' => 'Parameter :attribute harus berupa angka bulat.',
            'between' => 'Parameter :attribute harus antara :min dan :max.',
        ]);
    }

    private function buildFilters(array $input): array
    {
        $impacts = $this->parseImpacts($input['impact'] ?? '');
        $countries = array_values(array_unique(array_filter(array_map(
            EconomicCalendarPayloadService::apiCountryCode(...),
            explode(',', (string) ($input['country'] ?? ''))
        ))));

        return [
            'impact' => $impacts ?: null,
            'country' => $countries ?: null,
            'date_from' => ($input['date_from'] ?? null) ?: null,
            'date_to' => ($input['date_to'] ?? null) ?: null,
        ];
    }

    private function parseImpacts(?string $value): array
    {
        $impacts = array_map(
            fn (string $impact) => ucfirst(strtolower(trim($impact))),
            explode(',', (string) $value)
        );

        return array_values(array_unique(array_filter($impacts)));
    }

    private function paginateData(array $items, Request $request, int $perPage): LengthAwarePaginator
    {
        $page = max((int) $request->query('page', 1), 1);
        $items = array_values($items);

        return new LengthAwarePaginator(
            array_values(array_slice($items, ($page - 1) * $perPage, $perPage)),
            count($items),
            $perPage,
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
