<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCameraRequest;
use App\Http\Requests\UpdateCameraRequest;
use App\Services\CameraService;

class CameraController extends Controller
{
    protected $cameraService;

    public function __construct(CameraService $cameraService)
    {
        $this->cameraService = $cameraService;
    }

    public function index()
    {
        return response()->json($this->cameraService->getAll());
    }

    public function show($id)
    {
        return response()->json($this->cameraService->getById($id));
    }

    public function store(StoreCameraRequest $request)
    {
        return response()->json($this->cameraService->create($request->all()), 201);
    }

    public function update(UpdateCameraRequest $request, $id)
    {
        return response()->json($this->cameraService->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->cameraService->delete($id);
        return response()->json(['message' => 'Camera deleted successfully.'], 204);
    }
}
