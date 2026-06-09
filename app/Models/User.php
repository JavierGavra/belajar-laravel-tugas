<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // Menyesuaikan nama tabel karena default Laravel adalah 'users' (jamak)
    protected $table = 'users';

    // Setara dengan $primaryKey = 'id' dan $useAutoIncrement = true di CI4
    // (Bisa dihilangkan karena ini adalah default bawaan Laravel)
    protected $primaryKey = 'id';
    public $incrementing = true;

    // Setara dengan $allowedFields. 
    // Field name dan foto disertakan agar member dapat memperbarui nama dan foto profil mereka kapan saja.
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'name',
        'foto',
    ];

    // Field yang tidak akan ikut di-return saat model dipanggil (misal untuk response JSON API)
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}