<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'username',
        'total_harga',
        'alamat',
        'ongkir',
        'status'
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
