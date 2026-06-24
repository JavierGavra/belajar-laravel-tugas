@extends('layout')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <form action="/buy" method="POST" class="row g-3">
            {{-- Token CSRF wajib untuk form POST di Laravel --}}
            @csrf

            <input type="hidden" name="username" value="{{ session('username') }}">
            <input type="hidden" name="total_harga" id="total_harga" value="">

            <div class="col-12">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ session('username') }}" readonly>
            </div>
            
            <div class="col-12">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" name="alamat" id="alamat" class="form-control">
            </div> 
            
            <div class="col-12"> 
                <label for="kelurahan" class="form-label">Kelurahan</label>
                <select name="kelurahan" id="kelurahan" class="form-control">
                    <option value="">-- Pilih Kelurahan --</option>
                </select>
            </div>
            
            <div class="col-12"> 
                <label for="layanan" class="form-label">Layanan</label> 
                <select name="layanan" id="layanan" class="form-control">
                    <option value="">-- Pilih Layanan --</option>
                </select>
            </div>
            
            <div class="col-12">
                <label for="ongkir" class="form-label">Ongkir</label>
                <input type="text" name="ongkir" id="ongkir" class="form-control" readonly>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Buat Pesanan</button>
            </div>
        </form>
    </div>
    <div class="col-lg-6">
        <table class="table">
        <thead>
            <tr>
                <th scope="col">Nama</th>
                <th scope="col">Harga</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($items))
                @foreach ($items as $index => $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        {{-- Catatan: Menggunakan 'quantity' menyesuaikan fungsi cart_add Anda sebelumnya --}}
                        <td>{{ $item['quantity'] }}</td>
                        <td>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="2"></td>
                <td>Subtotal</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Total</td>
                <td><span id="total">Rp {{ number_format($total, 0, ',', '.') }}</span></td>
            </tr>
        </tbody>
    </table>
    </div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function() {
    let ongkir = 0;
    let subtotal = {{ $total }};
    hitungTotal();

    function hitungTotal() {
        let total = subtotal + ongkir;

        $("#ongkir").val(ongkir);
        $("#total").text(`IDR ${total.toLocaleString('id-ID')}`);
        $("#total_harga").val(total);
    }

    $('#kelurahan').select2({
        placeholder: 'Cari daerah tujuan',
        minimumInputLength: 3, 
        ajax: {
            url: '{{ url("ajax/destinations") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
    });

    $("#kelurahan").on('change', function() {
        let id_kelurahan = $(this).val();

        // Perbaikan 1: Kembalikan opsi default setelah mengosongkan select
        $("#layanan").empty().append('<option value="">-- Pilih Layanan --</option>');
        
        ongkir = 0;
        hitungTotal();

        // Hanya jalankan AJAX jika id_kelurahan tidak kosong
        if (id_kelurahan) {
            // (Opsional) Tambahkan teks loading agar user tahu proses sedang berjalan
            $("#layanan").append('<option value="" disabled>Memuat ongkir...</option>');

            $.ajax({
                url: "{{ url('ajax/costs') }}", 
                dataType: "json",
                data: {
                    destination: id_kelurahan
                },
                success: function (data) { 
                    // Hapus teks loading
                    $("#layanan").find('option:disabled').remove();

                    // Perbaikan 2: Cek apakah array data tidak kosong
                    if (data && data.length > 0) {
                        data.forEach(function (item) {
                            // Perbaikan 3: Menggunakan template literal HTML standar agar lebih presisi
                            $("#layanan").append(`<option value="${item.cost}">${item.description} (${item.service}) : estimasi ${item.etd} hari</option>`);
                        });
                    } else {
                        $("#layanan").append('<option value="">Layanan tidak tersedia ke rute ini</option>');
                    }
                },
                error: function () {
                    // Penanganan jika request gagal (misal: timeout atau error 500)
                    $("#layanan").find('option:disabled').remove();
                    alert("Gagal mengambil data ongkos kirim dari server.");
                }
            });
        }
    });

    $("#layanan").on('change', function() {
        // Menambahkan || 0 agar jika hasil parseInt adalah NaN, otomatis diubah menjadi 0
        ongkir = parseInt($(this).val()) || 0;
        hitungTotal();
    });
});
</script>
@endsection