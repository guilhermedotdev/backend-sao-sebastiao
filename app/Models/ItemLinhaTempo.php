<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLinhaTempo extends Model
{
    use HasFactory;

    protected $table = 'tb_item_linha_tempo';

    protected $fillable = [
        'fk_id_linha_tempo',
        'data',
        'titulo',
        'conteudo',
        'created_at',
        'updated_at',
    ];

    public function linhaTempo()
    {
        return $this->belongsTo(LinhaTempo::class, 'fk_id_linha_tempo');
    }
}
