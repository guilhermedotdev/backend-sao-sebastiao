<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doacao extends Model
{
    use HasFactory;

    protected $table = 'tb_doacao';

    protected $fillable = [
        'fk_id_usuario',
        'nome',
        'cpf_cnpj',
        'email',
        'codigo_pix',
        'ip',
        'created_at',
        'updated_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'fk_id_usuario');
    }
}
