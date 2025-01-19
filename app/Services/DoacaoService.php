<?php

namespace App\Services;

class DoacaoService
{
    /**
     * Realiza uma doação e retorna o código copia e cola PIX.
     *
     * @param string $nome
     * @param string $cpf
     * @param string $email
     * @param float $valor
     * @param string $chavePix
     * @return string
     */
    public function doDoacao(string $nome, string $cpf, string $email, float $valor, string $chavePix): string
    {
        // Validação básica dos parâmetros
        if (empty($nome) || empty($cpf) || empty($email) || empty($valor) || empty($chavePix)) {
            throw new \InvalidArgumentException('Todos os campos são obrigatórios.');
        }

        if ($valor <= 0) {
            throw new \InvalidArgumentException('O valor da doação deve ser maior que zero.');
        }

        // Formatar o valor para o padrão PIX (2 casas decimais)
        $valorFormatado = number_format($valor, 2, '.', '');

        // Construir o código copia e cola PIX (baseado no padrão BR Code 2.0)
        $codigoPix = $this->gerarCodigoPix($chavePix, $valorFormatado, $nome);

        // Registrar a doação no banco de dados ou em logs (opcional)
        // Exemplo: Log::info('Doação realizada', compact('nome', 'cpf', 'email', 'valor', 'chavePix'));

        return $codigoPix;
    }

    /**
     * Gera o código copia e cola PIX baseado nos dados fornecidos.
     *
     * @param string $chavePix
     * @param string $valor
     * @param string $nome
     * @return string
     */
    private function gerarCodigoPix(string $chavePix, string $valor, string $nome): string
    {
        // Segmentos fixos do padrão BR Code
        $pixPayload = [
            '00' => '01', // Identificação do formato
            '26' => [ // Dados do Merchant Account Information
                '00' => 'BR.GOV.BCB.PIX', // Domínio do PIX
                '01' => $chavePix, // Chave PIX
            ],
            '52' => '0000', // Merchant Category Code (0000: Default)
            '53' => '986', // Moeda (986: Real Brasileiro)
            '54' => $valor, // Valor da transação
            '58' => 'BR', // País
            '59' => $nome, // Nome do recebedor
            '60' => '-', // Cidade do recebedor
            '62' => [ // Informações adicionais
                '05' => 'Doação', // Descrição opcional
            ],
        ];

        // Gerar o código copia e cola PIX
        $codigoPix = $this->montarPixPayload($pixPayload);

        // Adicionar o CRC16 ao final do código
        $codigoPix .= $this->calcularCRC16($codigoPix);

        return $codigoPix;
    }

    /**
     * Monta o payload PIX a partir dos segmentos.
     *
     * @param array $payload
     * @return string
     */
    private function montarPixPayload(array $payload): string
    {
        $result = '';

        foreach ($payload as $id => $value) {
            if (is_array($value)) {
                $subPayload = $this->montarPixPayload($value);
                $value = str_pad(strlen($subPayload), 2, '0', STR_PAD_LEFT) . $subPayload;
            } else {
                $value = str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;
            }
            $result .= $id . $value;
        }

        return $result;
    }

    /**
     * Calcula o CRC16 para o código PIX.
     *
     * @param string $pix
     * @return string
     */
    private function calcularCRC16(string $pix): string
    {
        $polinomio = 0x1021;
        $resultado = 0xFFFF;

        for ($offset = 0; $offset < strlen($pix); $offset++) {
            $resultado ^= (ord($pix[$offset]) << 8);
            for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                if (($resultado & 0x8000) !== 0) {
                    $resultado = ($resultado << 1) ^ $polinomio;
                } else {
                    $resultado = $resultado << 1;
                }
            }
        }

        return strtoupper(dechex($resultado & 0xFFFF));
    }
}
