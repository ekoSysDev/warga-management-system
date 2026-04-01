@extends('adminlte::page')

@section('title', 'Pemasukan')

@section('content_header')
<div class="d-flex justify-content-between">
    <h1>Daftar Pembayaran</h1>
    <a href="{{ route('pemasukan.create') }}" class="btn btn-success">New</a>
</div>
@stop


@section('content')

<style>
    .close:hover {
        transform: scale(1.2);
    }

    .toast {
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.5s ease;
    }

    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }
</style>

<div class="card">
    <div class="card-body">
        <!-- @if(session('success'))
        <div id="alert-success" class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button"
                class="close text-white"
                data-dismiss="alert"
                style="opacity:1; transition:0.2s;">

                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif -->
        <form method="GET" class="mb-3">
            <div class="row">

                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / rumah">
                </div>

                <div class="col-md-2">
                    <input type="date" name="tanggal1" class="form-control">
                </div>

                <div class="col-md-2">
                    <input type="date" name="tanggal2" class="form-control">
                </div>

                <div class="col-md-2">
                    <select name="carabayar" class="form-control">
                        <option value="">-- Cara Bayar --</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary">Filter</button>
                </div>

            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Rumah</th>
                    <th>Nama</th>
                    <th>Tgl Bayar</th>
                    <th>Cara Bayar</th>
                    <th>Jenis Bayar</th>
                    <th class="text-center">Total</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                <tr>
                    <td>{{ $d->warga->alamat }}</td>
                    <td>{{ $d->warga->nama }}</td>
                    <td>{{ $d->tanggal }}</td>
                    <td>{{ ucfirst($d->carabayar) }}</td>
                    <td>{{ $d->jenispembayaran->jenisbayar ?? '-' }}</td>
                    <td class="text-right">{{ number_format($d->totalbayar) }}</td>
                    <td>{{ $d->keterangan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('pemasukan.edit', $d->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('pemasukan.delete', $d->id) }}" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @if ($data->currentPage() == $data->lastPage())
                <tr>
                    <td colspan="5" class="text-right text-success">
                        Total Cash
                    </td>
                    <td class="text-right text-success">
                        {{ number_format($totalCash) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="text-right text-primary">
                        Total Transfer
                    </td>
                    <td class="text-right text-primary">
                        {{ number_format($totalTransfer) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="text-right text-danger">
                        TOTAL KESELURUHAN
                    </td>
                    <td class="text-right text-danger">
                        {{ number_format($totalSemua) }}
                    </td>
                </tr>
                @endif
            </tfoot>
        </table>

        {{-- 🔥 WRAPPER DI SINI --}}
        <div class="d-flex justify-content-between align-items-center mt-2">

            {{-- INFO --}}
            <div>
                Showing {{ $data->firstItem() }}
                to {{ $data->lastItem() }}
                of {{ $data->total() }} results
            </div>

            {{-- PAGINATION --}}
            <div>
                {{ $data->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

{{-- 🔥 TOAST CONTAINER --}}
<div aria-live="polite" aria-atomic="true"
    style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">

    {{-- ✅ SUCCESS --}}
    @if(session('success'))
    <div class="toast bg-success text-white" id="toast-success" role="alert">
        <div class="toast-header bg-success text-white">
            <strong class="mr-auto">Sukses</strong>
            <button type="button" class="close text-white" data-dismiss="toast">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
    @endif

    {{-- ❌ ERROR --}}
    @if(session('error'))
    <div class="toast bg-danger text-white" id="toast-error" role="alert">
        <div class="toast-header bg-danger text-white">
            <strong class="mr-auto">Error</strong>
            <small>Now</small>
            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
    @endif

</div>
@stop
@section('js')
<script>
    $(document).ready(function() {

        // 🔥 SHOW TOAST
        $('.toast').toast({
            delay: 1500 // auto close 3 detik
        });

        $('.toast').toast('show');

        setTimeout(function() {
            $('#alert-success').fadeTo(400, 0).slideUp(400, function() {
                $(this).remove();
            });
        }, 4000);

    });
</script>
@stop