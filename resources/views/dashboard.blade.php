@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">
        Kas Warga ({{ $namaBulan }})
        <!-- <small class="text-muted">({{ $namaBulan }})</small> -->
    </h1>
@stop

@section('content')

<div class="row">

    {{-- Total Warga --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary shadow">
            <div class="inner">
                <h3>{{ $totalWarga }}</h3>
                <p>Total Warga</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    {{-- Sudah Bayar --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success shadow">
            <div class="inner">
                <h3>{{ $sudahBayar }}</h3>
                <p>Sudah Bayar IPL</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    {{-- Belum Bayar --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-danger shadow">
            <div class="inner">
                <h3>{{ $belumBayar }}</h3>
                <p>Belum Bayar IPL</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
    </div>

    {{-- Total Pemasukan --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-warning shadow">
            <div class="inner">
                <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                <p>Pemasukan Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>

</div>

{{-- PROGRESS PEMBAYARAN --}}
<div class="row">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary">
                <h3 class="card-title">Progress Pembayaran</h3>
            </div>
            <div class="card-body">

                @php
                    $persen = $totalWarga > 0 ? ($sudahBayar / $totalWarga) * 100 : 0;
                     $persen = 100;
                @endphp

                <div class="progress mb-3">
                    <div class="progress-bar bg-success" style="width: {{ $persen }}%">
                        {{ number_format($persen, 1) }}%
                    </div>
                </div>

                <p>
                    <b>{{ $sudahBayar }}</b> dari <b>{{ $totalWarga }}</b> warga sudah bayar
                </p>

            </div>
        </div>
    </div>

    {{-- INFO BULAN --}}
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-info">
                <h3 class="card-title">Informasi Bulan Ini</h3>
            </div>
            <div class="card-body">
                <h4 class="text-primary">{{ $namaBulan }}</h4>
                <ul>
                    <li>Total Warga: <b>{{ $totalWarga }}</b></li>
                    <!-- <li>Sudah Bayar: <b class="text-success">{{ $sudahBayar }}</b></li>
                    <li>Belum Bayar: <b class="text-danger">{{ $belumBayar }}</b></li>
                    <li>Pemasukan: 
                        <b class="text-warning">
                            Rp {{ number_format($totalPemasukan,0,',','.') }}
                        </b>
                    </li> -->
                </ul>
            </div>
        </div>
    </div>
</div>

@stop