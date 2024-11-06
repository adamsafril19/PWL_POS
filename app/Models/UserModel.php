<?php

namespace App\Models;

use App\Models\LevelModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function getJWTIdentifier(){
        return $this->getKey();

        }

    public function getJWTCustomClaims(){
        return [];
    }

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

    protected $fillable = ['level_id', 'username', 'nama', 'password', 'avatar'];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    // Mendapatkan nama role
    public function getRoleName(): string{
        return $this->level->level_nama;
    }

    // Cek apakah user memiliki role tertentu
    public function hasRole($role): bool{
        return $this->level->level_kode == $role;
    }

    // Mendapatkan kode role
    public function getRole(){
        return $this->level->level_kode;
    }
}
