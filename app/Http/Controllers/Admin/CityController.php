<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('name')->paginate(10);
        return view('admin.itinerary.kota.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.itinerary.kota.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:cities,name']);

        City::create(['name' => $request->name]);

        return redirect()->route('admin.itinerary.kota.index')->with('ok', 'Kota berhasil ditambahkan');
    }

   public function edit($id)
{
    $city = City::findOrFail($id);
    return view('admin.itinerary.kota.edit', compact('city'));
}

public function update(Request $request, $id)
{
    $city = City::findOrFail($id);
    $request->validate([
        'name' => 'required|string|max:100|unique:cities,name,' . $id
    ]);
    $city->update(['name' => $request->name]);

    return redirect()->route('admin.itinerary.kota.index')->with('ok', 'Kota berhasil diperbarui');
}

public function destroy($id)
{
    $city = City::findOrFail($id);
    $city->delete();

    return redirect()->route('admin.itinerary.kota.index')->with('ok', 'Kota berhasil dihapus');
}

}
