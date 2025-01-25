<?php

namespace App\Services;

use App\Models\Local;
use App\Models\Evento;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class LocalService
{
    /**
     * Busca os dados de um local pelo ID, incluindo eventos do dia e câmeras associadas.
     *
     * @param int $localId
     * @return array|null
     */
    public function getLocalById(int $localId): ?array
    {
        // Obter a data atual
        $today = now()->toDateString();

        // Buscar os dados do local
        $local = Local::with(['cameras'])
            ->find($localId);

        if (!$local) {
            return null; // Retorna null caso o local não seja encontrado
        }

        // Buscar os eventos associados ao local que estão acontecendo hoje
        $eventos = Evento::where('fk_id_local', $localId)
            ->whereDate('data_inicio', '<=', $today)
            ->whereDate('data_fim', '>=', $today)
            ->orderBy('data_inicio', 'asc')
            ->get();

        $local->eventos = $eventos;
        return [
            'local' => $local
        ];
    }

    /**
     * Busca locais dentro de um raio específico e os eventos agrupados por local.
     *
     * @param float $latitude
     * @param float $longitude
     * @param float $raio
     * @return array
     */
    public function getLocaisPorRaio(float $latitude, float $longitude, float $raio): array
    {
        // Fórmula de Haversine para calcular distância em uma esfera (Terra)
        $locais = Local::select(
            '*',
            DB::raw("(
                6371 * acos(
                    cos(radians($latitude))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians($longitude))
                    + sin(radians($latitude)) * sin(radians(latitude))
                )
            ) AS distancia")
        )
            ->having('distancia', '<=', $raio)
            ->orderBy('distancia', 'asc')
            ->whereHas('eventos') // Filtra apenas locais que possuem eventos
            ->with(['eventos' => function ($query) {
                $query->where('tipo_evento', 'presencial') // Filtra os eventos no carregamento
                    ->orderBy('data_inicio', 'asc');
            }]) // Carrega os eventos ordenados
            ->get();

        // Preparar o resultado com eventos agrupados por local
        return $locais->map(function ($local) {
            return [
                'local' => $local,
                'eventos' => $local->eventos,
            ];
        })->toArray();
    }

        /**
     * Cria um novo local.
     *
     * @param array $data
     * @return Local
     */
    public function create(array $data): Local
    {
        try {
            return Local::create($data);
        } catch (QueryException $e) {
            // Tratar exceções de consulta, se necessário
            throw new \Exception("Erro ao criar local: " . $e->getMessage());
        }
    }

    /**
     * Obtém todos os locais.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        try {
            return Local::with(['eventos' => function($query) {
                $query->whereDate('data_inicio', '<=', date('Y-m-d')) // data_inicio menor ou igual à data atual
                  ->whereDate('data_fim', '>=', date('Y-m-d')) // data_fim maior ou igual à data atual
                  ->where('tipo_evento', 'online'); // data_fim maior ou igual à data atual
            }])->get();
        } catch (QueryException $e) {
            throw new \Exception("Erro ao buscar locais: " . $e->getMessage());
        }
    }

    /**
     * Obtém um local específico pelo ID.
     *
     * @param int $id
     * @return Local
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Local
    {
        try {
            return Local::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Local com ID $id não encontrado.");
        } catch (QueryException $e) {
            throw new \Exception("Erro ao buscar local: " . $e->getMessage());
        }
    }

    /**
     * Atualiza os dados de um local.
     *
     * @param int $id
     * @param array $data
     * @return Local
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $data): Local
    {
        try {
            $local = Local::findOrFail($id);
            $local->update($data);
            return $local;
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Local com ID $id não encontrado.");
        } catch (QueryException $e) {
            throw new \Exception("Erro ao atualizar local: " . $e->getMessage());
        }
    }

    /**
     * Exclui um local.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        try {
            $local = Local::findOrFail($id);
            return $local->delete();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Local com ID $id não encontrado.");
        } catch (QueryException $e) {
            throw new \Exception("Erro ao excluir local: " . $e->getMessage());
        }
    }
}
