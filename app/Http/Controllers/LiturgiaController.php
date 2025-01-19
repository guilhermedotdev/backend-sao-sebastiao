<?php

namespace App\Http\Controllers;

use App\Services\LiturgiaService;
use Illuminate\Http\Request;

class LiturgiaController extends Controller
{
    private $liturgiaService;

    public function __construct(LiturgiaService $liturgiaService) {
        $this->liturgiaService = $liturgiaService;
    }

    function index()
    {
        return $this->liturgiaService->getLiturgiaDiaria();    
    }
}
