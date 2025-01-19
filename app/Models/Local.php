<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;

    // Nome da tabela
    protected $table = 'tb_local';

    // Chave primária
    protected $primaryKey = 'id';

    // Indica se a chave primária é auto-incrementada
    public $incrementing = true;

    // Tipo da chave primária
    protected $keyType = 'int';

    // Ativa o controle de timestamps (created_at e updated_at)
    public $timestamps = true;

    // Formato padrão dos timestamps
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Atributos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'nome',
        'url_imagem',
        'latitude',
        'longitude',
    ];

    // Atributos que devem ser tratados como datas
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function cameras()
    {
        return $this->hasMany(Camera::class, 'fk_id_local');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'fk_id_local');
    }
}