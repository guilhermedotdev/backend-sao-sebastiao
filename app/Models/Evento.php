<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'tb_evento';

    protected $fillable = [
        'fk_id_local',
        'nome',
        'tipo_evento',
        'descricao',
        'url_imagem',
        'data_inicio',
        'data_fim',
        'created_at',
        'updated_at',
    ];

    public function local()
    {
        return $this->belongsTo(Local::class, 'fk_id_local');
    }
}
