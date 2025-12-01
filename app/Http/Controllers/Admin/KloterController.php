<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kloter;
use Illuminate\Http\Request;

class KloterController extends Controller
{
    public function index()
    {
        $kloter = Kloter::latest()->get();
        return view('admin.kloter.index', compact('kloter'));
    }

    public function create()
    {
        return view('admin.kloter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|string|max:255',
        ]);

        Kloter::create($request->all());
        return redirect()->route('kloter.index')->with('success', 'Kloter berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kloter = Kloter::findOrFail($id);
        return view('admin.kloter.edit', compact('kloter'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|string|max:255',
        ]);

        $kloter = Kloter::findOrFail($id);
        $kloter->update($request->all());

        return redirect()->route('kloter.index')->with('success', 'Kloter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kloter = Kloter::findOrFail($id);
        $kloter->delete();

        return redirect()->route('kloter.index')->with('success', 'Kloter berhasil dihapus.');
    }
}
