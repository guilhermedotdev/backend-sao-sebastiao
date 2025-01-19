<?php

namespace App\Services;

use App\Models\Artigo;
use Illuminate\Support\Facades\Validator;

class ArtigoService
{
    /**
     * Cria um novo artigo.
     *
     * @param array $data
     * @return Artigo
     * @throws \Exception
     */
    public function create(array $data): Artigo
    {
        $validator = Validator::make($data, [
            'titulo' => 'required|string|max:255',
            'url_imagem' => 'nullable|url|max:255',
            'conteudo' => 'required|string',
            'fk_id_usuario' => 'required|exists:tb_usuario,id', // Validação de chave estrangeira
            'data_liturgia' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return Artigo::create($data);
    }

    /**
     * Obtém um artigo pelo ID.
     *
     * @param int $id
     * @return Artigo
     * @throws \Exception
     */
    public function getById(int $id): Artigo
    {
        $artigo = Artigo::with('usuario')->find($id);

        if (!$artigo) {
            throw new \Exception('Artigo não encontrado.');
        }

        return $artigo;
    }

    /**
     * Atualiza um artigo existente.
     *
     * @param int $id
     * @param array $data
     * @return Artigo
     * @throws \Exception
     */
    public function update(int $id, array $data): Artigo
    {
        $artigo = $this->getById($id);

        $validator = Validator::make($data, [
            'titulo' => 'sometimes|required|string|max:255',
            'url_imagem' => 'sometimes|nullable|url|max:255',
            'conteudo' => 'sometimes|required|string',
            'fk_id_usuario' => 'sometimes|required|exists:tb_usuario,id',
            'data_liturgia' => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $artigo->update($data);

        return $artigo;
    }

    /**
     * Deleta um artigo pelo ID.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        $artigo = $this->getById($id);

        return $artigo->delete();
    }

    /**
     * Lista todos os artigos com os usuários associados.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Artigo::with('usuario')->get();
    }


    /**
     * Lista artigos de forma paginada.
     *
     * @param int $perPage
     * @param int $page
     * @param string|null $search
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPage(int $perPage = 4, int $page = 1, $select = ['*']): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Artigo::select($select)->with('usuario');

        // Retorna resultados paginados
        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
