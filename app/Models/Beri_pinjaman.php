<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beri_pinjaman extends Model
{
    protected $table = 'beri_pinjaman';
    protected $fillable =
    [
        'user_id',
        'wallet_id',
        'waktu',
        'nama',
        'notes',
        'nominal',
        'status',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
