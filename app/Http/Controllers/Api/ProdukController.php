<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    protected $model;
    private $token;

    // Laravel otomatis memasukkan (inject) instance Product ke dalam $model
    public function __construct(Product $model)
    {
        $this->model = $model;

        // Mengambil nilai dari file .env
        $this->token = env('MY_API_KEY');
    }

    private function authenticate(Request $request)
    {
        // $request->bearerToken() otomatis mencari header 'Authorization: Bearer <token>'
        $token = $request->bearerToken();

        if (empty($token)) {
            return false;
        }

        // $this->token berasal dari construct yang kita buat sebelumnya
        return $token === $this->token;
    }

    private function unauthorized()
    {
        // Mengembalikan response JSON dengan status code 401
        return response()->json([
            'status'  => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Pengecekan Auth (Jika Anda menggunakan pengecekan manual di Controller)
        // Jika Anda sudah menggunakan Middleware (seperti yang disarankan sebelumnya), hapus 3 baris ini:
        if (!$this->authenticate($request)) {
            return $this->unauthorized();
        }

        // 2. Mengambil parameter per_page (default 10 jika tidak ada)
        $perPage = (int) $request->query('per_page', 10);

        // 3. Menjalankan Pagination dengan Eloquent
        // Laravel otomatis membaca query string '?page=' dari URL
        $paginator = Product::query()->paginate($perPage);

        // 4. Mengembalikan respons JSON sesuai dengan struktur (format) asli Anda
        return response()->json([
            'data'       => $paginator->items(), // Mengambil array datanya saja
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'last_page'    => $paginator->lastPage(),
                'total_data'   => $paginator->total(),
                'has_next'     => $paginator->hasMorePages(),
                'has_prev'     => $paginator->currentPage() > 1,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek token manual
        if (!$this->authenticate($request)) {
            return $this->unauthorized();
        }

        $data = $request->all();

        Product::create($data);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // Cek token manual
        if (!$this->authenticate($request)) {
            return $this->unauthorized();
        }

        $product = Product::findOrFail($id);

        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cek token manual
        if (!$this->authenticate($request)) {
            return $this->unauthorized();
        }

        $product = Product::findOrFail($id);

        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            'message' => 'Produk berhasil diperbarui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // 1. Cari data berdasarkan ID
        $product = Product::findOrFail($id);

        // 2. Jika tidak ada, kembalikan error 404
        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        // 3. Eksekusi hapus data
        $product->delete();

        // 4. Kembalikan response JSON (Secara bawaan menggunakan HTTP status 200 OK)
        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}
