<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    use HasFactory;

    protected $table = 'tb_camera';

    protected $fillable = [
        'fk_id_local',
        'link_live',
        'ativa',
        'nome',
        'created_at',
        'updated_at',
    ];

    public function local()
    {
        return $this->belongsTo(Local::class, 'fk_id_local');
    }
}
