<?php

namespace App\Services;

use App\Models\Evento;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventoService
{
    /**
     * Obtém eventos que estão acontecendo agora.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEventosAgora()
    {
        try {
            // Obtém eventos cuja data de início seja no passado ou presente
            // e data de fim seja no futuro ou presente
            return Evento::where([
                    ['data_inicio', '<=', now()],
                    ['data_fim', '>=', now()],
                    ['tipo_evento', 'presencial']
                ])
                ->get();
        } catch (\Exception $e) {
            Log::error('Erro ao buscar eventos agora: ' . $e->getMessage());
            return collect(); // Retorna uma coleção vazia em caso de erro
        }
    }

    /**
     * Obtém eventos próximos a uma localização dada (latitude e longitude).
     * A distância máxima pode ser definida em metros (exemplo: 10 km).
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $raio Em metros (exemplo: 10000 para 10 km)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEventosProximos($latitude, $longitude, $raio = 10000)
    {
        try {
            // Cálculo de distância baseado na fórmula de Haversine.
            // A função ST_Distance_Sphere calcula a distância entre dois pontos geográficos.

            return Evento::select(DB::raw("(
                    6371 * acos(
                        cos(radians($latitude))
                        * cos(radians(latitude))
                        * cos(radians(longitude) - radians($longitude))
                        + sin(radians($latitude)) * sin(radians(latitude))
                    )
                ) AS distancia"), 'tb_evento.*'
            )
            ->join('tb_local', 'fk_id_local', 'tb_local.id')
            ->having('distancia', '<=', $raio)
            ->orderBy('distancia', 'asc')
            ->get();
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Erro ao buscar eventos próximos: ' . $e->getMessage());
            return collect(); // Retorna uma coleção vazia em caso de erro
        }
    }

    /**
     * Obtém eventos ao vivo (live).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEventosLive()
    {
        try {
            // Buscar eventos que estão marcados como "ao vivo" e com data em andamento
            return Evento::where('tipo_evento', 'online') // Verifica se é uma live
                ->where('data_inicio', '<=', now()) // Verifica se já começou
                ->where('data_fim', '>=', now()) // Verifica se ainda não terminou
                ->get();
        } catch (\Exception $e) {
            Log::error('Erro ao buscar eventos live: ' . $e->getMessage());
            return collect(); // Retorna uma coleção vazia em caso de erro
        }
    }

     /**
     * Cria um novo evento.
     *
     * @param array $data
     * @return \App\Models\Evento
     */
    public function createEvento(array $data)
    {
        try {
            // Certifique-se de que os dados estejam corretos para cada campo
            $evento = Evento::create([
                'fk_id_local' => $data['fk_id_local'],
                'nome' => $data['nome'],
                'tipo_evento' => $data['tipo_evento'],
                'descricao' => $data['descricao'],
                'url_imagem' => $data['url_imagem'],
                'data_inicio' => $data['data_inicio'],
                'data_fim' => $data['data_fim'],
            ]);
            return $evento;
        } catch (\Exception $e) {
            Log::error('Erro ao criar evento: ' . $e->getMessage());
            throw new \Exception('Erro ao criar evento.');
        }
    }

    /**
     * Atualiza um evento existente.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Evento
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateEvento($id, array $data)
    {
        try {
            $evento = Evento::findOrFail($id);
            $evento->update([
                'fk_id_local' => $data['fk_id_local'] ?? $evento->fk_id_local,
                'nome' => $data['nome'] ?? $evento->nome,
                'tipo_evento' => $data['tipo_evento'] ?? $evento->tipo_evento,
                'descricao' => $data['descricao'] ?? $evento->descricao,
                'url_imagem' => $data['url_imagem'] ?? $evento->url_imagem,
                'data_inicio' => $data['data_inicio'] ?? $evento->data_inicio,
                'data_fim' => $data['data_fim'] ?? $evento->data_fim,
            ]);
            return $evento;
        } catch (ModelNotFoundException $e) {
            Log::error('Evento não encontrado: ' . $e->getMessage());
            throw $e; // Repassa a exceção se o evento não for encontrado
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar evento: ' . $e->getMessage());
            throw new \Exception('Erro ao atualizar evento.');
        }

    }

    /**
     * Exclui um evento.
     *
     * @param int $id
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteEvento($id)
    {
        try {
            $evento = Evento::findOrFail($id);
            return $evento->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('Evento não encontrado: ' . $e->getMessage());
            throw $e; // Repassa a exceção se o evento não for encontrado
        } catch (\Exception $e) {
            Log::error('Erro ao excluir evento: ' . $e->getMessage());
            throw new \Exception('Erro ao excluir evento.');
        }

    }
}
