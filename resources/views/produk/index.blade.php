@extends('layout')

@section('content') 
@if (session('success'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('failed') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah Data
</button>

<a class="btn btn-success" target="_blank" href="{{ url('produk/download') }}">
    Download Data
</a>

<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Foto</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $produk)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                
                <td>{{ $produk->nama }}</td>
                <td>{{ $produk->harga }}</td>
                <td>{{ $produk->jumlah }}</td>
                
                <td>
                    @if ($produk->foto && file_exists(public_path('img/' . $produk->foto)))
                        <img src="{{ asset('img/' . $produk->foto) }}" width="100" alt="Foto {{ $produk->nama }}">
                    @endif
                </td>
                
                <td>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal-{{ $produk->id }}">
                        Ubah
                    </button>

                    <a href="{{ url('produk/delete/' . $produk->id) }}" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini ?')">
                        Hapus
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('produk.modal_add')
@include('produk.modal_edit')

@endsection