<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtigoRequest;
use App\Http\Requests\UpdateArtigoRequest;
use App\Services\ArtigoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArtigoController extends Controller
{
    protected $artigoService;

    public function __construct(ArtigoService $artigoService)
    {
        // Injeta o ArtigoService
        $this->artigoService = $artigoService;
    }

    /**
     * Cria um novo artigo.
     *
     * @param Request $request
     * @return Response
     */
    public function store(StoreArtigoRequest $request)
    {
        try {
            $data = $request->all();
            $data['fk_id_usuario'] = JWTAuth::user()->id;
            $artigo = $this->artigoService->create($data);

            return response()->json($artigo, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Exibe um artigo pelo ID.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        try {
            $artigo = $this->artigoService->getById($id);

            return response()->json($artigo, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Atualiza um artigo existente.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateArtigoRequest $request, int $id)
    {
        try {
            $data = $request->all();
            $artigo = $this->artigoService->update($id, $data);

            return response()->json($artigo, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove um artigo pelo ID.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        try {
            $this->artigoService->delete($id);

            return response()->json(['message' => 'Artigo deletado com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Lista todos os artigos.
     *
     * @return Response
     */
    public function index()
    {
        $artigos = $this->artigoService->getAll();

        return response()->json($artigos, 200);
    }

    /**
     * Lista artigos de forma paginada.
     *
     * @param Request $request
     * @return Response
     */
    public function paginate(Request $request)
    {
        $perPage = $request->input('per_page', 4); // Valor padrão de 4 artigos por página
        $page = $request->input('page', 1); // Página inicial

        $artigos = $this->artigoService->getPage($perPage, $page);

        return response()->json($artigos, 200);
    }
}
