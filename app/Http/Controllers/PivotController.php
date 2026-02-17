<?php

namespace App\Http\Controllers;

use App\Models\Pivot;
use Illuminate\Http\Request;

class PivotController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'LGD Daily');

        $pivots = Pivot::where('symbol', $category)
            ->orderBy('date', 'desc')
            ->get();

        return view('pivot.index', compact('pivots', 'category'));
    }

    public function create()
    {
        return view('pivot.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'open' => 'nullable',
            'high' => 'nullable',
            'low' => 'nullable',
            'close' => 'nullable',
            'category' => 'required|string|in:LGD Daily,BCO Daily,HSI Daily,SNI Daily,AUD/USD,EUR/USD,GBP/USD,USD/CHF,USD/JPY',
            'isBankHoliday' => 'sometimes|boolean',
            'description' => 'nullable|string',
            // 'chg' => 'required',
            // 'volume' => 'required',
            // 'open_interest' => 'required',
        ];

        $data = $request->validate($rules);
        $data['isBankHoliday'] = $request->boolean('isBankHoliday');

        $mapped = [
            'date' => $data['tanggal'],
            'symbol' => $data['category'],
            'open' => $data['open'] ?? null,
            'high' => $data['high'] ?? null,
            'low' => $data['low'] ?? null,
            'close' => $data['close'] ?? null,
            'event' => $data['isBankHoliday'] ? ($data['description'] ?? 'Bank Holiday') : null,
        ];

        if ($data['isBankHoliday']) {
            $mapped['open'] = null;
            $mapped['high'] = null;
            $mapped['low'] = null;
            $mapped['close'] = null;
        }

        Pivot::create($mapped);

        return redirect()->route('pivot.index', ['category' => $request->category])->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pivot = Pivot::findOrFail($id);

        return view('pivot.edit', compact('pivot'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'tanggal' => 'required|date',
            'open' => 'nullable',
            'high' => 'nullable',
            'low' => 'nullable',
            'close' => 'nullable',
            'category' => 'required|string|in:LGD Daily,BCO Daily,HSI Daily,SNI Daily,AUD/USD,EUR/USD,GBP/USD,USD/CHF,USD/JPY',
            'isBankHoliday' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ];

        // if ($request->category === 'HSI Daily') {
        //     $rules['chg'] = 'required';
        //     $rules['volume'] = 'required';
        //     $rules['open_interest'] = 'required';
        // }

        // if ($request->category === 'SNI Daily') {
        //     $rules['chg'] = 'required';
        //     $rules['volume'] = 'required';
        // }

        $data = $request->validate($rules);
        $data['isBankHoliday'] = $request->boolean('isBankHoliday');

        $pivot = Pivot::findOrFail($id);
        $mapped = [
            'date' => $data['tanggal'],
            'symbol' => $data['category'],
            'open' => $data['open'] ?? null,
            'high' => $data['high'] ?? null,
            'low' => $data['low'] ?? null,
            'close' => $data['close'] ?? null,
            'event' => $data['isBankHoliday'] ? ($data['description'] ?? 'Bank Holiday') : null,
        ];

        if ($data['isBankHoliday']) {
            $mapped['open'] = null;
            $mapped['high'] = null;
            $mapped['low'] = null;
            $mapped['close'] = null;
        }

        $pivot->update($mapped);

        return redirect()->route('pivot.index', ['category' => $request->category])->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pivot = Pivot::findOrFail($id);
        $category = $pivot->category;
        $pivot->delete();

        return redirect()->route('pivot.index', ['category' => $category])->with('success', 'Data berhasil dihapus.');
    }
}
