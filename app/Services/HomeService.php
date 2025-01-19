<?php

namespace App\Services;

use App\Services\LinhaTempoService;
use App\Services\ArtigoService;
use App\Services\EventoService; // Serviço para buscar eventos
use Illuminate\Support\Facades\Log;

class HomeService
{
    protected $linhaTempoService;
    protected $artigoService;
    protected $eventoService;

    public function __construct(
        LinhaTempoService $linhaTempoService,
        ArtigoService $artigoService,
        EventoService $eventoService
    )
    {
        $this->linhaTempoService = $linhaTempoService;
        $this->artigoService = $artigoService;
        $this->eventoService = $eventoService;
    }

    /**
     * Obtém a home com a primeira linha do tempo, uma página de artigos e filtros de eventos.
     *
     * @param float|null $latitude
     * @param float|null $longitude
     * @param int $paginaArtigos
     * @return array
     */
    public function getHome($latitude = null, $longitude = null): array
    {
        try {
            // Obtém a primeira linha do tempo
            $linhaTempo = $this->linhaTempoService->getLinhaTempoById(1, false);

            // Obtém a primeira página de artigos
            $artigos = $this->artigoService->getPage(2, 1, [
                'titulo', 'url_imagem'
            ]);

            // Verifica se há eventos acontecendo agora
            $eventosAgora = $this->eventoService->getEventosAgora();

            // Filtra eventos próximos se a latitude e longitude forem passadas
            $eventosProximos = [];
            if ($latitude && $longitude) {
                $eventosProximos = $this->eventoService->getEventosProximos($latitude, $longitude);
            }

            // Filtra live
            $live = $this->eventoService->getEventosLive();

            return [
                'linha_temporal' => $linhaTempo,
                'artigos' => $artigos,
                'eventos_agora' => $eventosAgora,
                'eventos_proximos' => $eventosProximos,
                'live' => $live
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao obter dados da home: " . $e->getMessage());
            return [
                'linha_temporal' => null,
                'artigos' => null,
                'eventos_agora' => null,
                'eventos_proximos' => null,
                'live' => null
            ];
        }
    }
}
