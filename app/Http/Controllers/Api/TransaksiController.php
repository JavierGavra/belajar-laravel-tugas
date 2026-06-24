<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction; 

class TransaksiController extends Controller
{
    private $token;

    public function __construct()
    {
        // Mengambil token dari .env (Sangat disarankan menggunakan config() untuk production)
        $this->token = env('MY_API_KEY');
    }

    private function authenticate(Request $request)
    {
        // Laravel otomatis mengekstrak token dari header 'Authorization: Bearer <token>'
        $token = $request->bearerToken();

        if (empty($token)) {
            return false;
        }

        return $token === $this->token;
    }

    private function unauthorized()
    {
        return response()->json([
            'status'  => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    public function index(Request $request)
    {
        // 1. Pengecekan Auth Manual
        if (!$this->authenticate($request)) {
            return $this->unauthorized();
        }

        // 2. Menangkap parameter dari URL (query string)
        $start   = $request->query('start');
        $end     = $request->query('end'); 
        $perPage = (int) $request->query('per_page', 10);

        // 3. Membangun query menggunakan Eloquent (Eager Loading relasi)
        $query = Transaction::with('details.product')
                            ->orderBy('created_at', 'desc');

        // 4. Menambahkan filter tanggal
        if ($start && $end) {
            $query->whereDate('created_at', '>=', $start)
                  ->whereDate('created_at', '<=', $end);
        }
        
        // 5. Mengeksekusi query dengan pagination
        $paginator = $query->paginate($perPage);

        // 6. Mengembalikan JSON
        return response()->json([
            'filter' => [
                'start' => $start,
                'end'   => $end,
            ],
            'data' => $paginator->items(),
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
}