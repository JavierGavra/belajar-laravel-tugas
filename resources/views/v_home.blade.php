@extends('layout')
@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    {{-- Lakukan loop hanya satu kali di sini --}}
    @foreach ($products as $item)         
        <div class="col-lg-6">
            <form action="{{ url('keranjang') }}" method="POST">
                @csrf
                
                {{-- Gunakan format yang konsisten (Objek -> Properti) --}}
                <input type="hidden" name="id" value="{{ $item->id }}">
                <input type="hidden" name="nama" value="{{ $item->nama }}">
                <input type="hidden" name="harga" value="{{ $item->harga }}">
                <input type="hidden" name="foto" value="{{ $item->foto }}">

                <div class="card">
                    <div class="card-body">
                        <img src="{{ asset('img/' . $item->foto) }}" alt="..." width="50%">
                        <h5 class="card-title">
                            {{ $item->nama }}<br>
                            Rp {{ number_format($item->harga, 2, ',', '.') }}
                        </h5>
                        <button type="submit" class="btn btn-info rounded-pill">Beli</button>
                    </div>
                </div>
            </form>
        </div> 
    @endforeach 
</div>

@endsection