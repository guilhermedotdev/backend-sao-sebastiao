<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_usuario';

    protected $fillable = [
        'nome',
        'email',
        'password',
        'created_at',
        'updated_at',
        'verified_at',
    ];

    public function artigos()
    {
        return $this->hasMany(Artigo::class, 'fk_id_usuario');
    }

    public function doacoes()
    {
        return $this->hasMany(Doacao::class, 'fk_id_usuario');
    }
}
