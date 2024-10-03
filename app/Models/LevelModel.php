<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level';
    protected $tableName = 'level_id';

    /**
     * Mendefinisikan relasi bahwa level ini terkait dengan satu pengguna.
     *
     * @return BelongsTo
     */
}
