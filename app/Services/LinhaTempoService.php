<?php

namespace App\Services;

use App\Models\LinhaTempo;
use App\Models\Evento;
use App\Models\ItemLinhaTempo;
use Illuminate\Database\Eloquent\Collection;

class LinhaTempoService
{
    /**
     * Retorna a linha do tempo com os eventos associados, com base no ID fornecido.
     *
     * @param int $linhaTempoId
     */
    public function getLinhaTempoById(int $linhaTempoId, $trazerEventos = true)
    {
        // Recupera a linha do tempo pelo ID
        $linhaTempo = LinhaTempo::find($linhaTempoId);

        if (!$linhaTempo) {
            throw new \Exception('Linha do tempo não encontrada.');
        }

        if($trazerEventos) {
            $linhaTempo->eventos = ItemLinhaTempo::where('fk_id_linha_tempo', $linhaTempoId)
                ->orderBy('data', 'asc')
                ->get();
        }

        return $linhaTempo;
    }
}
