<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->get();

        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'price' => str_replace('.', '', $request->price)
        ]);
        $request->validate([
            'code' => 'required|string|max:50|unique:units,code',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'is_active' => 'nullable|boolean'
        ]);

        Unit::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('units.index')
            ->with('success', 'Unit berhasil ditambahkan');
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->merge([
            'price' => str_replace('.', '', $request->price)
        ]);
        $request->validate([
            'code' => 'required|string|max:50|unique:units,code,' . $unit->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'is_active' => 'nullable|boolean'
        ]);

        $unit->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('units.index')
            ->with('success', 'Unit berhasil diupdate');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->donations()->exists()) {
            return back()->withErrors('Unit sudah digunakan dalam donation.');
        }

        $unit->delete();

        return back()->with('success', 'Unit berhasil dihapus');
    }
}
