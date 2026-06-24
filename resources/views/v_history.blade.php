@extends('layout')

@section('content')
History Transaksi Pembelian <strong>{{ $username }}</strong>
<hr>
<div class="table-responsive">
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Pembelian</th>
                <th scope="col">Waktu Pembelian</th>
                <th scope="col">Total Bayar</th>
                <th scope="col">Alamat</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($transactions && $transactions->count() > 0)
            @foreach ($transactions as $item)
            <tr>
                {{-- Laravel menyediakan variabel $loop->iteration untuk nomor urut otomatis (mulai dari 1) --}}
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $item->id }}</td>
                <td>{{ $item->created_at }}</td>
                {{-- Menggunakan number_format bawaan PHP --}}
                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td>{{ $item->alamat }}</td>
                <td>
                    {{-- Karena tag HTML tidak boleh di-escape, lebih aman menggunakan @if Blade --}}
                    @if ($item->status == 1)
                    <span class="badge bg-success">Sudah Selesai</span>
                    @else
                    <span class="badge bg-warning">Belum Selesai</span>
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $item->id }}">
                        Detail
                    </button>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>

@if ($transactions && $transactions->count() > 0)
@foreach ($transactions as $item)
<div class="modal fade" id="detailModal-{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi #{{ $item->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Mengakses relasi 'details' yang sudah kita buat di Transaction Model --}}
                @if ($item->details && $item->details->count() > 0)
                @foreach ($item->details as $detail)
                {{ $loop->iteration }})

                @php
                // Ambil foto dari relasi 'product' di dalam 'detail'
                $foto = $detail->product->foto ?? null;
                // FCPATH diubah menjadi public_path() di Laravel
                $imagePath = public_path('img/' . $foto);
                @endphp

                @if (!empty($foto) && file_exists($imagePath))
                <div class="my-2">
                    {{-- base_url() diganti dengan asset() --}}
                    <img src="{{ asset('img/' . $foto) }}" width="100" class="img-thumbnail">
                </div>
                @endif

                <strong>{{ $detail->product->nama ?? 'Produk Dihapus' }}</strong>
                Rp {{ number_format($detail->product->harga ?? 0, 0, ',', '.') }}
                <br>
                ({{ $detail->jumlah }} pcs)<br>
                Rp {{ number_format($detail->subtotal_harga, 0, ',', '.') }}
                <hr>
                @endforeach
                @endif
                Ongkir: Rp {{ number_format($item->ongkir, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection