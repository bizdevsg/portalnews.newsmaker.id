<?php

namespace App\Http\Controllers;

use App\Models\EconomicCalendarCategory;
use App\Services\EconomicCalendarPayloadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EconomicCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q'));
        $selectedCountry = $this->normalizeCountryFilter($request->input('country'));
        $countryOptions = $this->buildCountryOptionsFromCategories();

        $categoriesQuery = EconomicCalendarCategory::withCount('details')
            ->with('latestDetail')
            ->withMax('details', 'date')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($filter) use ($search) {
                    $like = '%'.$search.'%';

                    $filter->where('figures', 'like', $like)
                        ->orWhere('sources', 'like', $like)
                        ->orWhere('country', 'like', $like)
                        ->orWhere('impact', 'like', $like)
                        ->orWhere('measures', 'like', $like);
                });
            })
            // ->orderByDesc('details_max_date')
            // ->orderBy('country', 'asc')
            ->orderBy('figures', 'asc');

        if ($selectedCountry !== null) {
            $countryVariants = $this->countryFilterVariants($selectedCountry);

            $categoriesQuery->whereIn('country', $countryVariants);
        }

        $categories = $categoriesQuery
            ->paginate(12)
            ->withQueryString();

        return view('calendar.index', compact('categories', 'search', 'selectedCountry', 'countryOptions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EconomicCalendarCategory $calendarCategory)
    {
        $calendarCategory->load([
            'details' => fn ($query) => $query
                ->orderByDesc('date')
                ->orderByDesc('time')
                ->orderByDesc('id'),
        ]);

        return view('calendar.show', [
            'calendar' => $calendarCategory,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('calendar.create');
    }

    public function preview(Request $request, EconomicCalendarPayloadService $payloadService)
    {
        $data = $payloadService->getPreparedData();
        $search = trim((string) $request->query('q'));
        $selectedCountry = $this->normalizeCountryFilter($request->query('country'));

        $availablePeriods = $payloadService->availablePeriods();
        $groupedData = $data === null ? [] : $payloadService->groupByPeriods($data);
        $countryOptions = $this->buildCountryOptions($data ?? []);

        $defaultPeriod = 'this-week';
        if (! in_array($defaultPeriod, $availablePeriods, true) || empty($groupedData[$defaultPeriod] ?? [])) {
            foreach ($availablePeriods as $period) {
                if (! empty($groupedData[$period] ?? [])) {
                    $defaultPeriod = $period;
                    break;
                }
            }
        }

        $requestedPeriod = $payloadService->normalizePeriod($request->query('period'));
        $activePeriod = in_array($requestedPeriod, $availablePeriods, true) ? $requestedPeriod : $defaultPeriod;

        $items = array_values($groupedData[$activePeriod] ?? []);
        if ($selectedCountry !== null) {
            $items = array_values(array_filter($items, function (array $item) use ($selectedCountry): bool {
                return $this->normalizeCountryFilter($item['country'] ?? null) === $selectedCountry;
            }));
        }

        if ($search !== '') {
            $searchNeedle = Str::lower($search);

            $items = array_values(array_filter($items, function (array $item) use ($searchNeedle): bool {
                $searchableValues = [
                    $item['impact'] ?? null,
                    $item['figures'] ?? null,
                ];

                if (filled($item['date'] ?? null)) {
                    $searchableValues[] = $item['date'];

                    try {
                        $parsedDate = \Carbon\Carbon::parse((string) $item['date']);

                        $searchableValues[] = $parsedDate->format('d-m-Y');
                        $searchableValues[] = $parsedDate->format('d/m/Y');
                        $searchableValues[] = $parsedDate->format('d M Y');
                    } catch (\Throwable) {
                        // Keep the raw date when parsing fails.
                    }
                }

                foreach ($searchableValues as $value) {
                    if ($value === null || $value === '') {
                        continue;
                    }

                    if (str_contains(Str::lower((string) $value), $searchNeedle)) {
                        return true;
                    }
                }

                return false;
            }));
        }

        $perPage = 10;
        $currentPage = max(1, (int) $request->query('page', 1));
        $offset = ($currentPage - 1) * $perPage;

        $paginatedItems = new LengthAwarePaginator(
            array_slice($items, $offset, $perPage),
            count($items),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );

        $paginatedItems->appends([
            'period' => $activePeriod,
            'q' => $search,
            'country' => $selectedCountry,
        ]);

        return view('calendar.preview', [
            'groupedData' => $groupedData,
            'meta' => $payloadService->buildPeriodsMeta(),
            'availablePeriods' => $availablePeriods,
            'countryOptions' => $countryOptions,
            'cacheAvailable' => $data !== null,
            'activePeriod' => $activePeriod,
            'selectedCountry' => $selectedCountry,
            'filteredItems' => $items,
            'paginatedItems' => $paginatedItems,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->validateCategory($request);

        $calendarCategory = EconomicCalendarCategory::create($validatedData);

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Category kalender berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EconomicCalendarCategory $calendarCategory)
    {
        return view('calendar.edit', [
            'calendar' => $calendarCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EconomicCalendarCategory $calendarCategory): RedirectResponse
    {
        $validatedData = $this->validateCategory($request, $calendarCategory->id);

        $calendarCategory->update($validatedData);
        $calendarCategory->details()->update($calendarCategory->syncedDetailAttributes());

        return redirect()
            ->route('calendar.show', $calendarCategory)
            ->with('success', 'Category kalender berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EconomicCalendarCategory $calendarCategory): RedirectResponse
    {
        $calendarCategory->delete();

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Category kalender berhasil dihapus.');
    }

    private function validateCategory(Request $request, ?int $categoryId = null): array
    {
        $validatedData = $request->validate([
            'country' => ['required', 'string', 'max:50'],
            'impact' => ['required', Rule::in(['Low', 'Medium', 'High'])],
            'figures' => [
                'required',
                'string',
                'max:100',
                Rule::unique('economic_calendar_categories', 'figures')
                    ->ignore($categoryId)
                    ->where(fn ($query) => $query
                        ->where('country', $request->input('country'))
                        ->where('impact', $request->input('impact'))),
            ],
            'sources' => ['required', 'string'],
            'measures' => ['nullable', 'string'],
            'usual_effect' => ['nullable', 'string'],
            'frequency' => ['nullable', 'string', 'max:100'],
            'next_released' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'isBankHoliday' => ['sometimes', 'boolean'],
            'bankHolidayNote' => ['nullable', 'string'],
            'why_trader_care' => ['nullable', 'string'],
        ]);

        $validatedData['isBankHoliday'] = $request->boolean('isBankHoliday');

        return $validatedData;
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    private function buildCountryOptions(array $data): array
    {
        $countryLabels = $this->countryLabels();
        $countries = [];

        foreach ($data as $item) {
            if (! is_array($item)) {
                continue;
            }

            $normalizedCountry = $this->normalizeCountryFilter($item['country'] ?? null);
            if ($normalizedCountry === null) {
                continue;
            }

            $countries[$normalizedCountry] = [
                'value' => $normalizedCountry,
                'label' => $countryLabels[$normalizedCountry] ?? $normalizedCountry,
            ];
        }

        uasort($countries, static fn (array $first, array $second): int => strcmp($first['label'], $second['label']));

        return array_values($countries);
    }

    private function normalizeCountryFilter(mixed $country): ?string
    {
        $normalized = strtoupper(trim((string) $country));

        if ($normalized === '') {
            return null;
        }

        return match ($normalized) {
            'US', 'USD' => 'US',
            'EUR' => 'EU',
            'JPY', 'JPN' => 'JP',
            'GBP' => 'GB',
            'AUD' => 'AU',
            'CAD' => 'CA',
            'CHF' => 'CH',
            'CHN', 'CNH' => 'CN',
            'IDR', 'IDN' => 'ID',
            'NZD' => 'NZ',
            default => $normalized,
        };
    }

    /**
     * @return array<string, string>
     */
    private function countryLabels(): array
    {
        return [
            'US' => 'United States',
            'EU' => 'European Union',
            'JP' => 'Japan',
            'GB' => 'United Kingdom',
            'AU' => 'Australia',
            'CA' => 'Canada',
            'CH' => 'Switzerland',
            'CN' => 'China',
            'NZ' => 'New Zealand',
            'ID' => 'Indonesia',
        ];
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    private function buildCountryOptionsFromCategories(): array
    {
        $countryLabels = $this->countryLabels();
        $countries = EconomicCalendarCategory::query()
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country')
            ->all();

        $options = [];

        foreach ($countries as $country) {
            $normalizedCountry = $this->normalizeCountryFilter($country);
            if ($normalizedCountry === null) {
                continue;
            }

            $options[$normalizedCountry] = [
                'value' => $normalizedCountry,
                'label' => $countryLabels[$normalizedCountry] ?? $normalizedCountry,
            ];
        }

        uasort($options, static fn (array $first, array $second): int => strcmp($first['label'], $second['label']));

        return array_values($options);
    }

    /**
     * @return array<int, string>
     */
    private function countryFilterVariants(string $normalizedCountry): array
    {
        return match ($normalizedCountry) {
            'US' => ['US', 'USD'],
            'EU' => ['EU', 'EUR'],
            'JP' => ['JP', 'JPY', 'JPN'],
            'GB' => ['GB', 'GBP'],
            'AU' => ['AU', 'AUD'],
            'CA' => ['CA', 'CAD'],
            'CH' => ['CH', 'CHF'],
            'CN' => ['CN', 'CHN', 'CNH'],
            'ID' => ['ID', 'IDN', 'IDR'],
            'NZ' => ['NZ', 'NZD'],
            default => [$normalizedCountry],
        };
    }
}
