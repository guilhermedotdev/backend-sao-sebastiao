<?php

namespace App\Http\Controllers;

use App\Models\ItemLinhaTempo;
use App\Http\Requests\StoreItemLinhaTempoRequest;
use App\Http\Requests\UpdateItemLinhaTempoRequest;
use App\Services\ItemLinhaTempoService;
use Illuminate\Http\Response;

class ItemLinhaTempoController extends Controller
{
    protected $itemLinhaTempoService;

    public function __construct(ItemLinhaTempoService $itemLinhaTempoService)
    {
        $this->itemLinhaTempoService = $itemLinhaTempoService;
    }
   
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemLinhaTempoRequest $request)
    {
        try {
            $item = $this->itemLinhaTempoService->create($request->all());
            return response()->json($item, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemLinhaTempo $itemLinhaTempo)
    {
        try {
            $item = $this->itemLinhaTempoService->getById($itemLinhaTempo->id);
            return response()->json($item, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemLinhaTempoRequest $request, ItemLinhaTempo $itemLinhaTempo)
    {
        try {
            $item = $this->itemLinhaTempoService->update($itemLinhaTempo->id, $request->all());
            return response()->json($item, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemLinhaTempo $itemLinhaTempo)
    {
        try {
            $this->itemLinhaTempoService->delete($itemLinhaTempo->id);
            return response()->json(null, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
