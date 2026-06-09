@extends('layout')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ url('keranjang/edit') }}" method="POST">
    @csrf
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">Nama</th>
                <th scope="col">Foto</th>
                <th scope="col">Harga</th> 
                <th scope="col">Jumlah</th> 
                <th scope="col">Subtotal</th> 
                <th scope="col">Aksi</th> 
            </tr>
        </thead>
        <tbody>
            @if (!empty($items))
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        {{-- Akses atribut dari package darryldecode/cart menggunakan tanda panah --}}
                        <td>
                            <img src="{{ asset('img/' . $item->attributes->foto) }}" width="100px" alt="{{ $item->name }}">
                        </td>
                        <td>Rp {{ number_format($item->price, 2, ',', '.') }}</td> 
                        <td>
                            <input type="number" 
                                min="1" 
                                name="qty[{{ $item->id }}]" 
                                class="form-control" 
                                value="{{ $item->quantity }}">
                        </td>

                        {{-- Subtotal (Harga * Kuantitas) --}}
                        <td>Rp {{ number_format($item->getPriceSum(), 2, ',', '.') }}</td>

                        {{-- Aksi --}}
                        <td>
                            <a href="{{ url('keranjang/delete/' . $item->id) }}" class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table> 
    <div class="alert alert-info">
        Total = Rp {{ number_format($total, 2, ',', '.') }}
    </div>

    <a href="{{ url('keranjang/clear') }}" class="btn btn-warning">
        Kosongkan Keranjang
    </a>
    <button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
</form>

@endsection