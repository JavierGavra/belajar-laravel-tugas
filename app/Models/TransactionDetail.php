<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends Model
{
    // Menggunakan trait HasFactory dan SoftDeletes
    use HasFactory, SoftDeletes;

    // Mendefinisikan nama tabel secara eksplisit
    protected $table = 'transaction_details';

    // Field yang diizinkan untuk diisi (Mass Assignment)
    protected $fillable = [
        'transaction_id', 
        'product_id', 
        'jumlah', 
        'diskon', 
        'subtotal_harga'
    ];

    /* |--------------------------------------------------------------------------
     | ELOQUENT RELATIONSHIPS (Sangat Direkomendasikan di Laravel)
     |--------------------------------------------------------------------------
     | Karena tabel ini memiliki 'transaction_id' dan 'product_id', 
     | di Laravel sangat disarankan untuk membuat relasi antar tabel (belongsTo).
     | Ini akan sangat memudahkan Anda saat memanggil data dengan 'with()'.
     */

    public function transaction()
    {
        // Parameter ke-2 adalah foreign key. Jika Anda mematuhi standar penamaan Laravel, parameter ke-2 bisa dihapus.
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function product()
    {
        // Asumsi Anda memiliki ProductModel
        return $this->belongsTo(Product::class, 'product_id');
    }
}
