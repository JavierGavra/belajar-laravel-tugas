<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index() {
        return view('produk.index', [
            'products' => Product::all()
        ]);
    }

    public function create(Request $request)
    {
        // 1. Validasi Input (Opsional tapi sangat disarankan)
        $request->validate([
            'nama'   => 'required|string',
            'harga'  => 'required|numeric',
            'jumlah' => 'required|numeric',
            'foto'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // max: 2MB
        ]);

        // 2. Ambil data teks
        $dataForm = [
            'nama'   => $request->input('nama'),
            'harga'  => $request->input('harga'),
            'jumlah' => $request->input('jumlah')
        ];

        // 3. Proses Upload Foto (jika ada file yang diunggah)
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            
            // Menghasilkan nama file acak yang unik (setara dengan getRandomName() di CI4)
            $fileName = $file->hashName(); 
            
            // Memindahkan file ke folder public/img agar perilakunya sama persis dengan CI4
            $file->move(public_path('img'), $fileName);
            
            // Memasukkan nama file ke dalam array untuk disimpan ke database
            $dataForm['foto'] = $fileName;
        }

        // 4. Simpan ke database menggunakan Eloquent ORM
        Product::create($dataForm);

        // 5. Redirect kembali ke halaman produk dengan flash message
        return redirect('/produk')->with('success', 'Data Berhasil Ditambah');
    }
    public function edit(Request $request, int $id)
    {
        // Mengambil data berdasarkan ID (otomatis me-return 404 jika tidak ditemukan)
        $product = Product::findOrFail($id);

        // Mengambil data dari form
        $dataForm = [
            'nama'   => $request->input('nama'),
            'harga'  => $request->input('harga'),
            'jumlah' => $request->input('jumlah')
        ];

        // Jika checkbox "Ceklis jika ingin mengganti foto" dicentang
        if ($request->input('check') == 1) {
            
            // Hapus foto lama dari folder public/img jika file-nya benar-benar ada
            if ($product->foto != '' && file_exists(public_path('img/' . $product->foto))) {
                unlink(public_path('img/' . $product->foto));
            }

            // Proses upload foto baru (sama seperti fungsi create)
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $file = $request->file('foto');
                
                $fileName = $file->hashName(); // Menghasilkan nama acak
                $file->move(public_path('img'), $fileName); // Pindah file ke public/img
                
                $dataForm['foto'] = $fileName;
            }
        }

        // Melakukan proses update ke database
        $product->update($dataForm);

        return redirect('/produk')->with('success', 'Data Berhasil Diubah');
    }

    public function delete(int $id)
    {
        // Cari data produk berdasarkan ID
        $product = Product::findOrFail($id);
        
        /* |--------------------------------------------------------------------------
        | Catatan Opsional:
        | Pada kode CI4 kamu, saat data dihapus, file gambar di server tidak ikut dihapus.
        | Jika kamu ingin membersihkan memori server dengan ikut menghapus gambarnya, 
        | kamu bisa mengaktifkan kode (uncomment) di bawah ini:
        |--------------------------------------------------------------------------
        */
        // if ($product->foto != '' && file_exists(public_path('img/' . $product->foto))) {
        //     unlink(public_path('img/' . $product->foto));
        // }

        // Hapus data dari database
        $product->delete();

        return redirect('/produk')->with('success', 'Data Berhasil Dihapus');
    }

    public function download()
    {
        // Ambil data produk dari database menggunakan Eloquent
        $products = Product::all();

        // Nama file PDF (bisa pakai date bawaan PHP atau helper now() milik Laravel)
        $filename = now()->format('Y-m-d-H-i-s') . '-produk.pdf';

        // Inisialisasi, Load View (HTML), dan Set Ukuran Kertas dalam satu perintah berantai (chaining)
        $pdf = Pdf::loadView('produk.download_pdf', compact('products'))
                ->setPaper('A4', 'portrait');

        // Download PDF (opsi Attachment => true di CI4 setara dengan fungsi download() di Laravel)
        return $pdf->download($filename);
    }
}
