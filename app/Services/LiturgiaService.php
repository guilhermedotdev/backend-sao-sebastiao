<?php

namespace App\Services;

use App\Models\Artigo;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LiturgiaService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Obtém a liturgia diária e retorna com o artigo correspondente à data.
     *
     * @return array
     */
    public function getLiturgiaDiaria(): array
    {
        // Obtém a data atual no formato dia, mês, ano
        $today = Carbon::now();
        $dia = $today->format('d');
        $mes = $today->format('m');
        $ano = $today->format('Y');

        try {
            // Consome a API externa para liturgia diária
            $response = $this->client->get("https://liturgia.up.railway.app", [
                'query' => [
                    'dia' => $dia,
                    'mes' => $mes,
                    'ano' => $ano
                ]
            ]);

            $liturgiaData = json_decode($response->getBody()->getContents(), true);

            // Busca o artigo com data_liturgia igual à data de hoje
            $artigo = Artigo::with('usuario')->whereDate('data_liturgia', $today->toDateString())->first();

            return [
                'liturgia' => $liturgiaData,
                'artigo' => $artigo ? $artigo : null
            ];

        } catch (\Exception $e) {
            Log::error("Erro ao consumir a API Liturgia: " . $e->getMessage());
            return [
                'liturgia' => null,
                'artigo' => null
            ];
        }
    }
}
