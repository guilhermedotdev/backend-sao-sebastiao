<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Services\EventoService;

class EventoController extends Controller
{
    protected $eventoService;

    public function __construct(EventoService $eventoService)
    {
        $this->eventoService = $eventoService;
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventoRequest $request)
    {
        try {
            $evento = $this->eventoService->createEvento($request->all());
            return response()->json($evento, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar evento.'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventoRequest $request, Evento $evento)
    {
        try {
            $evento = $this->eventoService->updateEvento($evento->id, $request->all());
            return response()->json($evento, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar evento.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento)
    {
        try {
            $deleted = $this->eventoService->deleteEvento($evento->id);
            if ($deleted) {
                return response()->json(['message' => 'Evento excluído com sucesso.'], 200);
            }
            return response()->json(['error' => 'Erro ao excluir evento.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir evento.'], 500);
        }

    }
}
