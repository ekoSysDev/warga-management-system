@extends('adminlte::page')

@section('title', 'Input Pemasukan')

@section('content_header')

<h1>Input Pembayaran IPL</h1>
@stop

@section('content')
@php
use Carbon\Carbon;

// mulai dari Januari tahun ini, mundur 3 bulan
$start = Carbon::create(now()->year, 1, 1)->subMonths(3);

// sampai 15 bulan ke depan dari sekarang
$end = now()->copy()->addMonths(15);

$periode = $start->copy();
@endphp

<style>
    #list_bulan.btn {
        display: inline-block;
        margin-bottom: 5px;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 13px;
    }

    #list_bulan .bulan-item {
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 13px;
    }

    #btnSimpan:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<div class="card">
    <div class="card-body">

        {{-- ✅ ALERT SUCCESS --}}
        @if(session('success'))
        <div id="alert-success" class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button>
        </div>
        @endif

        {{-- ALERT ERROR --}}
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- VALIDATION --}}
        @if ($errors->any())
        <div class="alert alert-warning">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('pemasukan.store') }}">
            @csrf

            <div class="row">


                {{-- ================= KIRI ================= --}}
                <div class="col-md-6">
                    <div class="card card-outline card-success">
                        <div class="card-body">
                            {{-- 🔍 WARGA AUTOCOMPLETE --}}
                            <div class="form-group">
                                <label>Warga</label>
                                <input type="text" id="search_warga" class="form-control" placeholder="Cari nama / no rumah">
                                <input type="hidden" name="warga_id" id="warga_id">
                                <div id="result_warga" class="list-group"></div>
                            </div>

                            {{-- 📅 TANGGAL --}}
                            <div class="form-group">
                                <label>Tanggal Bayar</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>

                            {{-- 💳 JENIS BAYAR --}}
                            <div class="form-group">
                                <label>Jenis Pembayaran</label>
                                <select name="jenisbayar" id="jenisbayar" class="form-control">
                                    <option value="">-- pilih --</option>
                                    @foreach($jenis as $j)
                                    <option
                                        value="{{ $j->id }}"
                                        data-nama="{{ $j->jenisbayar }}"
                                        data-rp="{{ number_format($j->defaultbayar,0,',','.') }}"
                                        data-tarif="{{ $j->defaultbayar }}">
                                        {{ $j->jenisbayar }}
                                    </option>
                                    @endforeach
                                </select>

                            </div>

                            {{-- 💳 CARA BAYAR --}}
                            <div class="form-group">
                                <label>Cara Bayar</label>
                                <select name="carabayar" id="carabayar" class="form-control">
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= KANAN ================= --}}

                <div class="col-md-6">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Pilih Bulan</label>
                                <select id="bulan_select" class="form-control">
                                    <option value="">-- pilih bulan --</option>
                                    @foreach($bulanList as $b)
                                    <option value="{{ $b['value'] }}">{{ $b['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Bulan Dibayar</label>
                                <div id="list_bulan"></div>
                            </div>

                            <div class="form-group">
                                <label>Total Bayar</label>
                                <input type="text" id="totalbayar_view" class="form-control">
                                <input type="hidden" name="totalbayar" id="totalbayar">
                            </div>

                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control"></textarea>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <!-- <button class="btn btn-primary mt-3">Simpan</button> -->
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">
                    ← Batal
                </a>

                <button id="btnSimpan" class="btn btn-primary" disabled>
                    Simpan
                </button>

            </div>
        </form>
    </div>
</div>

@stop

{{-- ================= JAVASCRIPT ================= --}}
@section('js')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        function cekFormValid() {
            let warga = $('#warga_id').val();
            let jenis = $('#jenisbayar').val();
            let carabayar = $('#carabayar').val();
            let total = $('#totalbayar').val();
            let jumlahBulan = bulanDipilih.length;

            if (warga && jenis && carabayar && total > 0 && jumlahBulan > 0) {
                $('#btnSimpan').prop('disabled', false);
            } else {
                $('#btnSimpan').prop('disabled', true);
            }
            console.log({
                warga,
                jenis,
                carabayar,
                total,
                jumlahBulan
            });

        }
        // ================= AUTOCOMPLETE WARGA =================
        $('#search_warga').keyup(function() {
            let q = $(this).val();

            if (q.length < 2) return;

            $.get('/warga/search?q=' + q, function(data) {
                let html = '';
                data.forEach(item => {
                    html += `<a href="#" class="list-group-item pilih-warga"
                        data-id="${item.id}"
                        data-nama="${item.nama}">
                        ${item.alamat} - ${item.nama}
                     </a>`;
                });
                $('#result_warga').html(html);
            });
        });

        // pilih warga
        $(document).on('click', '.pilih-warga', function(e) {
            e.preventDefault();
            $('#search_warga').val($(this).data('nama'));
            $('#warga_id').val($(this).data('id'));
            $('#result_warga').html('');
            cekFormValid(); // 🔥 tambah ini
        });
        //-------- jenis bayar (IPL | Donasi ....) ---------------

        // CEK kalau select2 belum ke-load
        if (typeof $.fn.select2 === 'undefined') {
            console.error('Select2 belum load!');
            return;
        }

        $('#jenisbayar').select2({
            width: '100%',
            templateResult: formatJenis,
            templateSelection: formatSelection
        });

        function formatJenis(data) {
            if (!data.id) return data.text;

            let el = $(data.element);

            let nama = el.data('nama');
            let rp = el.data('rp');

            return $(`
            <div style="display:flex; justify-content:space-between; width:100%;">
                <span>${nama}</span>
                <span><b>Rp ${rp}</b></span>
            </div>
        `);
        }

        function formatSelection(data) {
            if (!data.id) return data.text;

            let el = $(data.element);
            return el.data('nama') + ' - Rp ' + el.data('rp');
        }

        // ================= AUTO HITUNG =================
        let bulanDipilih = [];
        let tarif = 0;

        let now = new Date();
        let bulan = now.getMonth() + 1;
        let tahun = now.getFullYear();

        let val = tahun + '-' + (bulan < 10 ? '0' + bulan : bulan);

        // cari option yang sesuai
        let option = $('#bulan_select option[value="' + val + '"]');

        if (option.length) {

            let text = option.text();

            bulanDipilih.push(val);

            $('#list_bulan').append(`
                <span class="btn btn-sm btn-primary mr-2 mb-2 d-inline-flex align-items-center shadow-sm bulan-item">
                    
                    <span class="mr-2">${text}</span>

                    <button type="button"
                        class="btn btn-xs btn-light remove-bulan"
                        data-val="${val}"
                        style="padding:2px 6px;">
                        <i class="fas fa-times"></i>
                    </button>

                    <input type="hidden" name="bulan[]" value="${val}">
                </span>
            `);
 

            hitungTotal();
            cekFormValid();
        }

        // ambil tarif

        $('#jenisbayar').change(function() {
            tarif = parseInt($(this).find(':selected').data('tarif')) || 0;

            // default isi = 1 bulan
            if (bulanDipilih.length === 0) {
                setTotal(tarif);
            } else {
                hitungTotal();
            }
            cekFormValid(); // 🔥 tambah ini
        });


        // tambah bulan
        $('#bulan_select').change(function() {

            let val = $(this).val().toString();
            let text = $("#bulan_select option:selected").text();

            if (val && !bulanDipilih.includes(val)) {
                if (bulanDipilih.includes(val)) {
                    return;
                }
                bulanDipilih.push(val);

                $('#list_bulan').append(`
                <span class="btn btn-sm btn-primary mr-2 mb-2 d-inline-flex align-items-center shadow-sm bulan-item">
                    
                        <span class="mr-2">${text}</span>

                        <button type="button"
                            class="btn btn-xs btn-light remove-bulan"
                            data-val="${val}"
                            style="padding:2px 6px;">
                            <i class="fas fa-times"></i>
                        </button>

                        <input type="hidden" name="bulan[]" value="${val}">
                    </span>
                `);

                hitungTotal();
                cekFormValid();
            }
            // reset dropdown
            $(this).val('');
        });

        $(document).on('click', '.remove-bulan', function(e) {
            e.preventDefault();
            let val = $(this).data('val');

            bulanDipilih = bulanDipilih.filter(b => b !== val);
            $(this).parent().remove();

            hitungTotal();
            cekFormValid();
        });


        // ================= HITUNG TOTAL =================
        function hitungTotal() {
            let total = bulanDipilih.length * tarif;

            setTotal(total);
        }

        // ================= SET TOTAL =================
        function setTotal(total) {
            $('#totalbayar').val(total);
            $('#totalbayar_view').val(formatRupiah(total));
        }

        // 🔥 FORMAT RUPIAH
        function formatRupiah(angka) {
            if (!angka) return '';
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // 🔥 HANYA ANGKA (USER INPUT)
        $('#totalbayar_view').on('input', function() {

            // ambil angka saja
            let angka = $(this).val().replace(/[^0-9]/g, '');

            // simpan ke hidden
            $('#totalbayar').val(angka);

            // tampilkan format
            $(this).val(formatRupiah(angka));
            cekFormValid(); // 🔥
        });
        cekFormValid();
    });

            setTimeout(function() {
                $('#alert-success').fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 3000);
         

</script>
@stop