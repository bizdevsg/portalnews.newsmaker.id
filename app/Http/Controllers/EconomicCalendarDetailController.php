<?php

namespace App\Http\Controllers;

use App\Models\EconomicCalendar;
use App\Models\EconomicCalendarCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EconomicCalendarDetailController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(EconomicCalendarCategory $calendarCategory)
    {
        return view('calendar.detail-create', [
            'category' => $calendarCategory,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, EconomicCalendarCategory $calendarCategory): RedirectResponse
    {
        $validatedData = $this->validateDetail($request);

        EconomicCalendar::create(array_merge(
            $calendarCategory->syncedDetailAttributes(),
            $validatedData,
            ['economic_calendar_category_id' => $calendarCategory->id],
        ));

        return redirect()
            ->route('calendar.show', $calendarCategory)
            ->with('success', 'Data detail kalender berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EconomicCalendar $detail)
    {
        $detail->load('category');

        return view('calendar.detail-edit', compact('detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EconomicCalendar $detail): RedirectResponse
    {
        $validatedData = $this->validateDetail($request);
        $detail->load('category');
        $syncedAttributes = $detail->category?->syncedDetailAttributes() ?? [];

        $detail->update(array_merge(
            $syncedAttributes,
            $validatedData,
        ));

        return redirect()
            ->route('calendar.show', $detail->economic_calendar_category_id)
            ->with('success', 'Data detail kalender berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EconomicCalendar $detail): RedirectResponse
    {
        $categoryId = $detail->economic_calendar_category_id;
        $detail->delete();

        return redirect()
            ->route('calendar.show', $categoryId)
            ->with('success', 'Data detail kalender berhasil dihapus.');
    }

    private function validateDetail(Request $request): array
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'time' => 'nullable|string|max:10',
            'previous' => 'nullable|string|max:100',
            'forecast' => 'nullable|string|max:100',
            'actual' => 'nullable|string|max:100',
        ]);

        $validatedData['time'] = $validatedData['time'] ?? null;

        return $validatedData;
    }
}
