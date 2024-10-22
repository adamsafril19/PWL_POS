<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    protected $table = 't_penjualan';
    protected $primaryKey = 'penjualan_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pembeli',
        'penjualan_kode',
        'penjualan_tanggal'
    ];

    public function details()
    {
        return $this->hasMany(DetailPenjualanModel::class, 'penjualan_id', 'penjualan_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}

