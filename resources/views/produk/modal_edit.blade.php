@foreach ($products as $produk)    
<div class="modal fade" id="editModal-{{ $produk->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ url('produk/edit/' . $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama-{{ $produk->id }}" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama-{{ $produk->id }}" class="form-control" value="{{ $produk->nama }}" placeholder="Nama Barang" required>
                    </div>

                    <div class="mb-3">
                        <label for="harga-{{ $produk->id }}" class="form-label">Harga</label>
                        <input type="number" name="harga" id="harga-{{ $produk->id }}" class="form-control" value="{{ $produk->harga }}" placeholder="Harga Barang" required>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah-{{ $produk->id }}" class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah-{{ $produk->id }}" class="form-control" value="{{ $produk->jumlah }}" placeholder="Jumlah Barang" required>
                    </div>

                    <div class="mb-3">
                        @if ($produk->foto)
                            <img src="{{ asset('img/' . $produk->foto) }}" width="100" alt="Foto {{ $produk->nama }}">
                        @else
                            <span class="text-muted">Belum ada foto</span>
                        @endif
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="check" id="check-{{ $produk->id }}" value="1" class="form-check-input">
                        <label class="form-check-label" for="check-{{ $produk->id }}">
                            Ceklis jika ingin mengganti foto
                        </label>
                    </div>

                    <div class="mb-3">
                        <label for="foto-{{ $produk->id }}" class="form-label">Foto</label>
                        <input type="file" name="foto" id="foto-{{ $produk->id }}" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endforeach