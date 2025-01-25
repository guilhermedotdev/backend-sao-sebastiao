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
    public function getLiturgiaDiaria($data = null): array
    {
        $data = $data ? Carbon::parse($data) : Carbon::now();

        // Obtém o dia, mês e ano da data fornecida
        $dia = $data->format('d');
        $mes = $data->format('m');
        $ano = $data->format('Y');

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

            if ($data->toDateString() == Carbon::now()->toDateString()) {
                $response = $this->client->get("https://api-liturgia-diaria.vercel.app/cn");

                $liturgiaAdicionalData = json_decode($response->getBody()->getContents(), true);

                $liturgiaData['evangelho']['texto'] = $liturgiaAdicionalData['today']['readings']['gospel']['all_html'] ?? $liturgiaData['evangelho']['texto'];

                $liturgiaData['salmo']['texto'] = $liturgiaAdicionalData['today']['readings']['psalm']['all_html'] ?? $liturgiaData['salmo']['texto'];

                $liturgiaData['primeiraLeitura']['texto'] = $liturgiaAdicionalData['today']['readings']['first_reading']['all_html'] ?? $liturgiaData['primeiraLeitura']['texto'];
            }

            // Busca o artigo com data_liturgia igual à data de hoje
            $artigo = Artigo::with('usuario')->whereDate('data_liturgia', $data->toDateString())->first();

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
