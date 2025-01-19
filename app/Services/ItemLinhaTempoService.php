<?php

namespace App\Services;

use App\Models\ItemLinhaTempo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ItemLinhaTempoService
{
    // Criar um novo item de linha do tempo
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $item = ItemLinhaTempo::create($data);

            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao criar item de linha do tempo: ' . $e->getMessage());
        }
    }

    // Obter todos os itens de linha do tempo
    public function getAll()
    {
        return ItemLinhaTempo::all();
    }

    // Obter um item de linha do tempo por ID
    public function getById($id)
    {
        $item = ItemLinhaTempo::find($id);

        if (!$item) {
            throw new ModelNotFoundException('Item de linha do tempo não encontrado.');
        }

        return $item;
    }

    // Atualizar um item de linha do tempo
    public function update($id, array $data)
    {
        DB::beginTransaction();

        try {
            $item = ItemLinhaTempo::find($id);

            if (!$item) {
                throw new ModelNotFoundException('Item de linha do tempo não encontrado.');
            }

            $item->update($data);

            DB::commit();
            return $item;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao atualizar item de linha do tempo: ' . $e->getMessage());
        }
    }

    // Deletar um item de linha do tempo
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $item = ItemLinhaTempo::find($id);

            if (!$item) {
                throw new ModelNotFoundException('Item de linha do tempo não encontrado.');
            }

            $item->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao deletar item de linha do tempo: ' . $e->getMessage());
        }
    }
}
