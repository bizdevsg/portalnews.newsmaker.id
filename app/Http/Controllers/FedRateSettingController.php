<?php

namespace App\Http\Controllers;

use App\Models\FedRateSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Pengaturan Fed Rate Monitor bersifat singleton, sama seperti Tiktok:
 * cuma ada 1 baris aktif, setiap submit = edit baris yang sama.
 */
class FedRateSettingController extends Controller
{
    public function edit()
    {
        $setting = FedRateSetting::current();

        return view('fed-rate-monitor.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_target_lower' => 'required|numeric|min:0|max:100',
            'current_target_upper' => 'required|numeric|min:0|max:100|gt:current_target_lower',
            'source_updated_at' => 'nullable|date',
        ]);

        $sourceUpdatedAt = null;
        if (filled($validated['source_updated_at'] ?? null)) {
            // Eloquent membaca kolom datetime sebagai jam lokal app.timezone
            // (lihat created_at/updated_at di seluruh model lain), jadi harus
            // dikonversi ke app.timezone dulu sebelum disimpan - bukan UTC -
            // supaya round-trip baca-tulisnya tetap merepresentasikan instant
            // yang benar.
            $sourceUpdatedAt = Carbon::parse($validated['source_updated_at'], 'America/New_York')
                ->setTimezone(config('app.timezone'));
        }

        $payload = [
            'current_target_lower' => $validated['current_target_lower'],
            'current_target_upper' => $validated['current_target_upper'],
            'source_updated_at' => $sourceUpdatedAt,
        ];

        $setting = FedRateSetting::current();
        if ($setting) {
            $setting->update($payload);
        } else {
            FedRateSetting::create($payload);
        }

        // Cache API dibersihkan otomatis lewat FedRateMonitorCacheObserver.

        return redirect()
            ->route('fed-rate-monitor.settings.edit')
            ->with('success', 'Pengaturan Fed Rate Monitor berhasil disimpan.');
    }
}
