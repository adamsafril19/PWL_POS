<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class supplierModel extends Model
{
    use HasFactory;

    protected $table = 'm_supplier';
    protected $primaryKey = 'supplier_id';
    public $timestamps = true;
    protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat'];


    /**
     * Mendefinisikan relasi bahwa supplier ini terkait dengan satu pengguna.
     *
     * @return BelongsTo
     */
}
