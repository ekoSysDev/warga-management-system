<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Warga;
use App\Models\Pemasukan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWarga = 0;
        $sudahBayar = 0;
        $belumBayar = 0;
        $totalPemasukan = 0;
        $now = now();

        $totalWarga = Warga::count();

        $sudahBayar = Pemasukan::whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->distinct('warga_id')
            ->count('warga_id');

        $belumBayar = $totalWarga - $sudahBayar;

        $totalPemasukan = Pemasukan::whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('totalbayar');

        $namaBulan = $now->translatedFormat('F Y'); // ✅

        return view('dashboard', compact(
            'totalWarga',
            'sudahBayar',
            'belumBayar',
            'totalPemasukan',
            'namaBulan'
        ));
    }
}
