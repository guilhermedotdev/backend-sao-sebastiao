<?php

namespace App\Http\Controllers;

use App\Models\Doacao;
use App\Http\Requests\StoreDoacaoRequest;
use App\Http\Requests\UpdateDoacaoRequest;
use App\Services\DoacaoService;

class DoacaoController extends Controller
{
    private $doacaoService;

    public function __construct(DoacaoService $doacaoService) {
        $this->doacaoService = $doacaoService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoacaoRequest $request)
    {
        $codigo = $this->doacaoService->doDoacao($request->nome, $request->cpf, $request->email, $request->valor, 'XXX');
        return [
            'codigo' => $codigo
        ];
    }

}
