<?php

namespace App\Http\Controllers;

use App\Models\Local;
use App\Http\Requests\StoreLocalRequest;
use App\Http\Requests\UpdateLocalRequest;
use App\Services\LocalService;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    private $localService;

    public function __construct(LocalService $localService) {
        $this->localService = $localService;
    }

    public function index()
    {
        return $this->localService->getAll();
    }

    /**
     * Display a listing of the resource.
     */
    public function buscarGeofence(Request $request)
    {
        return $this->localService->getLocaisPorRaio($request->latitude, $request->longitude, 1000);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocalRequest $request)
    {
        return $this->localService->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Local $local)
    {
        return $this->localService->getLocalById($local->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocalRequest $request, Local $local)
    {
        return $this->localService->update($local->id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Local $local)
    {
        return $this->localService->delete($local->id);
    }
}
