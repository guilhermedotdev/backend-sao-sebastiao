<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artigo extends Model
{
    use HasFactory;

    protected $table = 'tb_artigo';

    protected $fillable = [
        'titulo',
        'url_imagem',
        'conteudo',
        'fk_id_usuario',
        'data_liturgia',
        'created_at',
        'updated_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'fk_id_usuario');
    }
}
