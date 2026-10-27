<?php

namespace App\Http\Controllers;

use App\Models\FedRateMeeting;
use Illuminate\Http\Request;

class FedRateMeetingController extends Controller
{
    protected function rules(): array
    {
        return [
            'meeting_date' => 'required|date',
            'meeting_time' => 'required|date_format:H:i',
            'future_price' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'rows' => 'required|array|min:1',
            'rows.*.target_lower' => 'required|numeric|min:0|max:100',
            'rows.*.target_upper' => 'required|numeric|min:0|max:100|gt:rows.*.target_lower',
            'rows.*.current_probability' => 'required|numeric|min:0|max:100',
            'rows.*.previous_day_probability' => 'nullable|numeric|min:0|max:100',
            'rows.*.previous_week_probability' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function index()
    {
        $meetings = FedRateMeeting::query()
            ->withCount('rows')
            ->orderBy('meeting_date')
            ->get();

        return view('fed-rate-monitor.index', compact('meetings'));
    }

    public function create()
    {
        return view('fed-rate-monitor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $meeting = FedRateMeeting::create($this->payloadFromRequest($validated));
        $this->syncRows($meeting, $validated['rows']);

        return redirect()
            ->route('fed-rate-monitor.index')
            ->with('success', 'Meeting FOMC berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $meeting = FedRateMeeting::with('rows')->findOrFail($id);

        return view('fed-rate-monitor.edit', compact('meeting'));
    }

    public function update(Request $request, int $id)
    {
        $meeting = FedRateMeeting::findOrFail($id);
        $validated = $request->validate($this->rules());

        $meeting->update($this->payloadFromRequest($validated));
        $this->syncRows($meeting, $validated['rows']);

        return redirect()
            ->route('fed-rate-monitor.index')
            ->with('success', 'Meeting FOMC berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $meeting = FedRateMeeting::findOrFail($id);
        $meeting->delete();

        return redirect()
            ->route('fed-rate-monitor.index')
            ->with('success', 'Meeting FOMC berhasil dihapus.');
    }

    private function payloadFromRequest(array $validated): array
    {
        return [
            'meeting_date' => $validated['meeting_date'],
            'meeting_time' => $validated['meeting_time'],
            'future_price' => $validated['future_price'] ?? null,
            'is_active' => array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];
    }

    /**
     * Baris probabilitas selalu diganti total (hapus semua, buat ulang)
     * supaya tidak perlu melacak id baris mana yang dihapus/diubah di repeater.
     */
    private function syncRows(FedRateMeeting $meeting, array $rows): void
    {
        $meeting->rows()->delete();

        foreach ($rows as $row) {
            $meeting->rows()->create([
                'target_lower' => $row['target_lower'],
                'target_upper' => $row['target_upper'],
                'current_probability' => $row['current_probability'],
                'previous_day_probability' => $row['previous_day_probability'] ?? null,
                'previous_week_probability' => $row['previous_week_probability'] ?? null,
            ]);
        }
    }
}
