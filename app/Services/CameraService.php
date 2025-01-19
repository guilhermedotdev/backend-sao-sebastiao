<?php

namespace App\Services;

use App\Models\Camera;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CameraService
{
    /**
     * Retorna todas as câmeras.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Camera::all();
    }

    /**
     * Retorna uma câmera pelo ID.
     *
     * @param int $id
     * @return Camera
     * @throws ModelNotFoundException
     */
    public function getById(int $id)
    {
        return Camera::findOrFail($id);
    }

    /**
     * Cria uma nova câmera.
     *
     * @param array $data
     * @return Camera
     */
    public function create(array $data)
    {
        return Camera::create($data);
    }

    /**
     * Atualiza uma câmera existente.
     *
     * @param int $id
     * @param array $data
     * @return Camera
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $data)
    {
        $camera = Camera::findOrFail($id);
        $camera->update($data);
        return $camera;
    }

    /**
     * Exclui uma câmera pelo ID.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     */
    public function delete(int $id)
    {
        $camera = Camera::findOrFail($id);
        return $camera->delete();
    }
}
