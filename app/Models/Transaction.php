<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
        // Menggunakan trait HasFactory (opsional untuk seeder/testing) 
    // dan SoftDeletes (karena $useSoftDeletes = true di CI4)
    use HasFactory, SoftDeletes;

    // Menyesuaikan nama tabel (Laravel secara default mencari tabel jamak 'transaction_models' atau 'transactions')
    protected $table = 'transactions';

    // Field yang diizinkan untuk mass-assignment (setara dengan $allowedFields di CI4)
    protected $fillable = [
        'username', 
        'total_harga', 
        'alamat', 
        'ongkir', 
        'status'
    ];

    // Catatan: 
    // Laravel secara default sudah menetapkan 'id' sebagai primaryKey, 
    // tipe datanya auto-increment, dan menggunakan timestamp 'created_at' & 'updated_at'.
    // Jadi Anda tidak perlu menuliskan konfigurasinya lagi kecuali jika namanya berbeda.
}
