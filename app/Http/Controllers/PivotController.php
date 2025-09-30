<?php

namespace App\Http\Controllers;

use App\Models\Pivot;
use Illuminate\Http\Request;

class PivotController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'LGD Daily');

        $pivots = Pivot::where('category', $category)
            ->orderBy('tanggal', 'desc')
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
            'open'    => 'required',
            'high'    => 'required',
            'low'     => 'required',
            'close'   => 'required',
            'category' => 'required|string|in:LGD Daily,LSI,HSI Daily,SNI Daily,AUD/USD,EUR/USD,GBP/USD,USD/CHF,USD/JPY',
        ];

        if ($request->category === 'HSI Daily') {
            $rules['chg'] = 'required';
            $rules['volume'] = 'required';
            $rules['open_interest'] = 'required';
        }

        if ($request->category === 'SNI Daily') {
            $rules['chg'] = 'required';
            $rules['volume'] = 'required';
        }

        $request->validate($rules);

        Pivot::create($request->all());

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
            'open'    => 'required',
            'high'    => 'required',
            'low'     => 'required',
            'close'   => 'required',
            'category' => 'required|string|in:LGD Daily,LSI,HSI Daily,SNI Daily,AUD/USD,EUR/USD,GBP/USD,USD/CHF,USD/JPY',
        ];

        if ($request->category === 'HSI Daily') {
            $rules['chg'] = 'required';
            $rules['volume'] = 'required';
            $rules['open_interest'] = 'required';
        }

        if ($request->category === 'SNI Daily') {
            $rules['chg'] = 'required';
            $rules['volume'] = 'required';
        }

        $request->validate($rules);

        $pivot = Pivot::findOrFail($id);
        $pivot->update($request->all());

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
