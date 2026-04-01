<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Warga;
use App\Models\JenisPembayaran;
use App\Models\Pemasukan;
use App\Models\PemasukanDetail;
use Carbon\Carbon;

class PemasukanController extends Controller
{

    public function index(Request $request)
    {
        $query = Pemasukan::with(['warga:id,nama,alamat', 'jenispembayaran:id,jenisbayar']);
 
        // 🔥 default bulan berjalan
        if (!$request->tanggal1 && !$request->tanggal2) {
            $query->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year);
        }

        // 🔍 search nama / no rumah
        if ($request->search) {
            $query->whereHas('warga', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('no_rumah', 'like', '%' . $request->search . '%');
            });
        }

        // 📅 filter tanggal
        if ($request->tanggal1 && $request->tanggal2) {
            $query->whereBetween('tanggal', [
                $request->tanggal1,
                $request->tanggal2
            ]);
        }

        // 💳 filter cara bayar
        if ($request->carabayar) {
            $query->where('carabayar', $request->carabayar);
        }

        $data = $query->latest()->paginate(10);

        $totalCash = Pemasukan::whereMonth('tanggal', now()->month)
            ->where('carabayar', 'cash')
            ->sum('totalbayar');

        $totalTransfer = Pemasukan::whereMonth('tanggal', now()->month)
            ->where('carabayar', 'transfer')
            ->sum('totalbayar');

        $totalSemua = $totalCash + $totalTransfer;

        return view('pemasukan.index', compact('data', 'totalSemua', 'totalCash', 'totalTransfer'));
    }

    public function create()
    {
        $wargas = Warga::all();
        $jenis = JenisPembayaran::all();

        $bulanList = [];

        $start = Carbon::now()->copy()->startOfMonth()->subMonths(3);
        $end   = Carbon::now()->copy()->startOfMonth()->addMonths(15);

        $current = $start->copy();

        while ($current <= $end) {

            $bulanList[] = [
                'value' => $current->format('Y-m'),
                'label' => $current->format('M-y'),
            ];

            $current->addMonth();
        }
        $bulanList = collect($bulanList)
            ->unique('value')
            ->values()
            ->toArray();

        return view('pemasukan.create', compact('wargas', 'jenis', 'bulanList'));
    }

    public function store(Request $request)
    {
        // 🔥 VALIDASI
        $request->validate([
            'warga_id'   => 'required',
            'tanggal'    => 'required|date',
            'jenisbayar' => 'required',
            'carabayar'  => 'required',
            'bulan'      => 'required|array|min:1',
            'totalbayar' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {

            // ===============================
            // 1. SIMPAN KE PEMASUKAN (HEADER)
            // ===============================
            $pemasukan = Pemasukan::create([
                'tanggal'       => $request->tanggal,
                'warga_id'      => $request->warga_id,
                'jenisbayar_id' => $request->jenisbayar,
                'totalbayar'    => $request->totalbayar,
                'keterangan'    => $request->keterangan,
                'carabayar'     => $request->carabayar,
                'aktifyn'       => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // ===============================
            // 2. SIMPAN DETAIL PER BULAN
            // ===============================
            foreach ($request->bulan as $periode) {

                PemasukanDetail::create([
                    'terima_id'    => $pemasukan->id,
                    'periode_bayar' => $periode,
                    'aktifyn'      => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pemasukan.index')
                ->with('success', '✅ Data pemasukan berhasil disimpan');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with('error', '❌ Gagal simpan data')
                ->with('debug', $e->getMessage()) // opsional (buat dev)
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = Pemasukan::find($id);
        $wargas = Warga::all();
        return view('pemasukan.edit', compact('data', 'wargas'));
    }

    public function updates(Request $request, $id)
    {
        $data = Pemasukan::findOrFail($id);

        $data->update([
            'tanggal' => $request->tanggal,
            'carabayar' => $request->carabayar,
            'totalbayar' => $request->totalbayar,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data berhasil diupdate');
    }


    public function update(Request $request, $id)
    {
        $data = Pemasukan::find($id);

        $data->update([
            'tanggal' => $request->tanggal,
            'carabayar' => $request->carabayar,
            'totalbayar' => $request->totalbayar,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = Pemasukan::findOrFail($id);

        $data->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    public function delete($id)
    {
        Pemasukan::find($id)->delete();
        return back();
    }

    public function searchWarga(Request $request)
    {
        $keyword = $request->q;

        $data = \App\Models\Warga::where('nama', 'like', "%$keyword%")
            ->orWhere('alamat', 'like', "%$keyword%")
            ->limit(10)
            ->get();

        return response()->json($data);
    }
}
