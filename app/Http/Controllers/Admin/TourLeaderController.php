<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourleader;
use App\Models\Kloter; // ⬅️ tambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TourLeaderController extends Controller
{
    public function index()
    {
        $tourleaders = Tourleader::with('kloter')->get(); // biar bisa ambil nama kloter
        return view('admin.tourleaders.index', compact('tourleaders'));
    }

    public function create()
    {
        $kloters = Kloter::all(); // ambil semua kloter
        return view('admin.tourleaders.create', compact('kloters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:tour_leaders',
            'password'  => 'required|string|min:6',
            'fcm_token' => 'nullable|string|max:255',
            'kloter_id' => 'required|exists:kloters,id', // validasi foreign key
        ]);

        Tourleader::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'fcm_token' => $request->fcm_token,
            'kloter_id' => $request->kloter_id,
        ]);

        return redirect()->route('tourleaders.index')->with('success', 'Tour Leader berhasil ditambahkan');
    }

    public function edit(Tourleader $tourleader)
    {
        $kloters = Kloter::all();
        return view('admin.tourleaders.edit', compact('tourleader', 'kloters'));
    }

    public function update(Request $request, Tourleader $tourleader)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:tour_leaders,email,' . $tourleader->id,
            'fcm_token' => 'nullable|string|max:255',
            'kloter_id' => 'required|exists:kloters,id',
        ]);

        $tourleader->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $request->password ? Hash::make($request->password) : $tourleader->password,
            'fcm_token' => $request->fcm_token,
            'kloter_id' => $request->kloter_id,
        ]);

        return redirect()->route('tourleaders.index')->with('success', 'Tour Leader berhasil diupdate');
    }

    public function destroy(Tourleader $tourleader)
    {
        $tourleader->delete();
        return redirect()->route('tourleaders.index')->with('success', 'Tour Leader berhasil dihapus');
    }
}
