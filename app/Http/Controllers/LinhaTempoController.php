<?php

namespace App\Http\Controllers;

use App\Models\LinhaTempo;
use App\Http\Requests\StoreLinhaTempoRequest;
use App\Http\Requests\UpdateLinhaTempoRequest;
use App\Services\LinhaTempoService;

class LinhaTempoController extends Controller
{
    private $linhaTempoService;

    public function __construct(LinhaTempoService $linhaTempoService) {
        $this->linhaTempoService = $linhaTempoService;
    }

    /**
     * Display the specified resource.
     */
    public function show(LinhaTempo $linhaTempo)
    {
        return $this->linhaTempoService->getLinhaTempoById($linhaTempo->id);
    }
}
