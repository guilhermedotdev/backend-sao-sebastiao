<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinhaTempo extends Model
{
    use HasFactory;

    protected $table = 'tb_linha_tempo';

    protected $fillable = [
        'nome',
        'created_at',
        'updated_at',
    ];

    public function itens()
    {
        return $this->hasMany(ItemLinhaTempo::class, 'fk_id_linha_tempo');
    }
}
