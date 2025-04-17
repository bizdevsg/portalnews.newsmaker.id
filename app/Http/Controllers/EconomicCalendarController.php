<?php

namespace App\Http\Controllers;

use App\Models\EconomicCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EconomicCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data kalender dengan pengurutan berdasarkan created_at dan time
        $calendars = EconomicCalendar::orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        // Mengembalikan view dengan data kalender yang sudah diambil
        return view('calendar.index', ['calendars' => $calendars]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $calendar = EconomicCalendar::findOrFail($id);

        // Ambil tanggal kalender ekonomi
        $calendarDate = Carbon::parse($calendar->date);

        // Ambil data lain yang memiliki figures yang sama dan tanggal lebih kecil dari tanggal ini
        $histories = EconomicCalendar::where('figures', $calendar->figures)
            ->where('date', '<', $calendarDate)
            ->orderBy('date', 'desc')
            ->limit(5) // atau ->take(5)
            ->get();

        return view('calendar.show', compact('calendar', 'histories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('calendar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'nullable|date',
            'time' => 'nullable|string|max:10',
            'custom_time' => 'nullable|string|max:10',
            'country' => 'required|string|max:50',
            'impact' => 'required|string|in:Low,Medium,High',
            'figures' => 'required|string|max:100',
            'previous' => 'nullable|string|max:100',
            'forecast' => 'nullable|string|max:100',
            'actual' => 'nullable|string|max:100',
            'sources' => 'required|string',
            'measures' => 'nullable|string',
            'usual_effect' => 'nullable|string',
            'frequency' => 'nullable|string|max:100',
            'next_released' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'why_trader_care' => 'nullable|string',
        ]);

        EconomicCalendar::create($validatedData);

        return redirect()->route('calendar.index')
            ->with('success', 'Economic data added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $calendar = EconomicCalendar::findOrFail($id);

        return view('calendar.edit', compact('calendar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'nullable|date',
            'time' => 'nullable|string|max:10',
            'custom_time' => 'nullable|string|max:10',
            'country' => 'required|string|max:50',
            'impact' => 'required|string|in:Low,Medium,High',
            'figures' => 'required|string|max:100',
            'previous' => 'nullable|string|max:100',
            'forecast' => 'nullable|string|max:100',
            'actual' => 'nullable|string|max:100',
            'sources' => 'required|string',
            'measures' => 'required|string',
            'usual_effect' => 'required|string',
            'frequency' => 'required|string|max:100',
            'next_released' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'why_trader_care' => 'nullable|string',
        ]);

        $calendar = EconomicCalendar::findOrFail($id);
        $calendar->update($validatedData);

        return redirect()->route('calendar.index')
            ->with('success', 'Economic data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $calendar = EconomicCalendar::findOrFail($id);
        $calendar->delete();

        return redirect()->route('calendar.index')
            ->with('success', 'Economic data deleted successfully.');
    }
}
