<?php

namespace App\Models;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level';
    protected $primaryKey = 'level_id';
    public $timestamps = true;
    protected $fillable = ['level_kode', 'level_nama'];

    public function users()
    {
        return $this->hasMany(UserModel::class, 'level_id', 'level_id');
    }

    /**
     * Mendefinisikan relasi bahwa level ini terkait dengan satu pengguna.
     *
     * @return BelongsTo
     */
}
