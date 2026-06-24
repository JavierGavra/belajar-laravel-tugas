<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    protected RajaOngkirService $rajaOngkir;

    // Inject RajaOngkirService melalui constructor
    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {
        $cart = app('cart');

        $data = [
            'items' => $cart->getContent(),
            'total' => $cart->getTotal()
        ];

        return view('v_keranjang', $data);
    }

    public function cart_add(Request $request)
    {
        // Menambahkan item menggunakan app('cart')
        app('cart')->add([
            'id'         => $request->input('id'),
            'name'       => $request->input('nama'),
            'price'      => $request->input('harga'),
            'quantity'   => 1,
            'attributes' => [
                'foto' => $request->input('foto')
            ]
        ]);

        $message = 'Produk berhasil ditambahkan ke keranjang. ' .
            '<a href="' . url('keranjang') . '">Lihat</a>';

        return redirect()->back()->with('success', $message);
    }

    public function cart_edit(Request $request)
    {
        // Kita melakukan loop terhadap input array 'qty' dari form
        foreach ($request->input('qty') as $id => $quantity) {

            // Update kuantitas barang di keranjang
            // 'relative' => false berarti mengganti nilai lama dengan nilai baru
            app('cart')->update($id, [
                'quantity' => [
                    'relative' => false,
                    'value'    => $quantity
                ]
            ]);
        }

        // Redirect kembali dengan flash message
        return redirect('keranjang')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function cart_delete(int $id)
    {
        // Menghapus item berdasarkan ID
        app('cart')->remove($id);

        return redirect()->back()->with('success', 'Item berhasil dihapus');
    }

    public function cart_clear()
    {
        // Mengosongkan keranjang
        app('cart')->clear();

        return redirect()->back()->with('success', 'Keranjang telah dikosongkan');
    }

    public function checkout()
    {
        $response = $this->rajaOngkir->getDestination('semarang');
        $response2 = $this->rajaOngkir->getCost('64999', '65042', 1000, 'jne');

        // Menggunakan app('cart') agar konsisten dengan method lainnya
        $data = [
            'items' => app('cart')->getContent(),
            'total' => app('cart')->getTotal(),
            'response' => $response,
            'response2' => $response2
        ];

        return view('v_checkout', $data);
    }

    public function destinations(Request $request)
    {
        $search = $request->query('q');
        $response = $this->rajaOngkir->getDestination($search);

        $results = [];

        // Perbaikan: Dukungan untuk struktur RajaOngkir standar maupun wrapper API
        $data = $response['rajaongkir']['results'] ?? $response['data'] ?? [];

        foreach ($data as $item) {
            // Menyesuaikan key dengan format RajaOngkir atau format custom Anda
            $id = $item['id'] ?? $item['city_id'] ?? $item['subdistrict_id'] ?? '';
            $text = $item['label'] ?? $item['city_name'] ?? $item['subdistrict_name'] ?? '';

            if ($id && $text) {
                $results[] = [
                    'id'   => $id,
                    'text' => $text
                ];
            }
        }

        return response()->json(['results' => $results]);
    }

    public function costs(Request $request)
    {
        $origin = '64999';
        $destination = $request->query('destination');
        $weight = 1000;
        $courier = 'jne';

        $response = $this->rajaOngkir->getCost($origin, $destination, $weight, $courier);

        $results = [];

        // Perbaikan: Mengakses key 'costs' di dalam array kurir (RajaOngkir Standard)
        // Fallback ke $response['data'] jika Anda memakai API Proxy Custom
        $data = $response['rajaongkir']['results'][0]['costs'] ?? $response['data'][0]['costs'] ?? $response['data'] ?? [];

        foreach ($data as $item) {
            // Perbaikan Krusial: Ekstrak nilai value dan etd dari dalam nested array 'cost'
            $costValue = isset($item['cost'][0]['value']) ? $item['cost'][0]['value'] : ($item['cost'] ?? 0);
            $etdValue  = isset($item['cost'][0]['etd']) ? $item['cost'][0]['etd'] : ($item['etd'] ?? '-');

            $results[] = [
                'service'     => $item['service'],
                'description' => $item['description'],
                'cost'        => $costValue, // Sekarang pasti berupa angka/integer
                'etd'         => $etdValue
            ];
        }

        return response()->json($results);
    }

    public function buy(Request $request)
    {
        // Menggunakan app('cart') sesuai dengan implementasi sebelumnya
        $cartItems = app('cart')->getContent();

        // Di Laravel/Darryldecode, getContent() mengembalikan Collection
        if ($cartItems->isEmpty()) {
            return redirect()->back();
        }

        // Memulai transaksi Database Laravel
        DB::beginTransaction();

        try {
            $subtotal = 0;
            foreach ($cartItems as $item) {
                // Catatan: Pada package cart ini, key jumlah bernama 'quantity', bukan 'qty'
                $subtotal += $item['quantity'] * $item['price'];
            }

            // Alternatif yang lebih cepat di Laravel (menggunakan fitur bawaan cart):
            // $subtotal = app('cart')->getSubTotal();

            $ongkir = (int) $request->input('ongkir');

            // 1. Insert ke tabel transaction menggunakan Eloquent
            // Eloquent create() otomatis mengembalikan instance objek yang baru dibuat
            $transaction = Transaction::create([
                'username'    => $request->input('username'),
                'alamat'      => $request->input('alamat'),
                'ongkir'      => $ongkir,
                'total_harga' => $subtotal + $ongkir,
                'status'      => 0,
            ]);

            // 2. Insert transaction detail
            foreach ($cartItems as $item) {
                TransactionDetail::create([
                    // Kita bisa langsung memanggil ID dari objek $transaction
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'jumlah'         => $item['quantity'],
                    'diskon'         => 0,
                    'subtotal_harga' => $item['quantity'] * $item['price']
                ]);
            }

            // Jika semua query berhasil, simpan permanen ke database
            DB::commit();

            // Kosongkan keranjang belanja
            app('cart')->clear();

            // Redirect ke halaman utama (home)
            return redirect('/')->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            // Jika ada query yang gagal atau error, batalkan semua perubahan di database
            DB::rollBack();

            // Redirect kembali dengan pesan error (opsional: tampilkan pesan $e->getMessage() untuk debugging)
            return redirect()->back()->with('error', 'Gagal membuat transaksi. Silakan coba lagi.');
        }
    }

    public function history()
    {
        $username = session('username');

        // Mengambil transaksi beserta relasi detail dan produknya
        $transactions = Transaction::with('details.product')
            ->where('username', $username)
            ->get();

        return view('v_history', [
            'username'     => $username,
            'transactions' => $transactions
        ]);
    }
}
