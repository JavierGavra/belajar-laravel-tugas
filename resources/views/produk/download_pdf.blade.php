<h1>Data Produk</h1>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Foto</th>
    </tr>

    @foreach ($products as $produk)
        @php
            // public_path() adalah pengganti FCPATH di Laravel
            $path = public_path('img/' . $produk->foto);
            $base64 = '';
            
            // Tambahan pengecekan !empty() untuk memastikan field foto tidak kosong di database
            if (!empty($produk->foto) && file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp
        
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            
            <td>{{ $produk->nama }}</td>
            <td align="right">Rp {{ number_format($produk->harga, 2, ',', '.') }}</td>
            <td align="center">{{ $produk->jumlah }}</td>
            
            <td align="center">
                @if ($base64)
                    <img src="{{ $base64 }}" width="50" alt="Foto Produk">
                @else
                    Tidak ada gambar
                @endif
            </td>
        </tr>
    @endforeach
</table>

Downloaded on {{ now()->format('Y-m-d H:i:s') }}