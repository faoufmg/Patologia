<?php
declare (strict_types = 1);

header('Content-Type: application/json; charset=utf-8');

function repositoryViaCEP(string $cep): array | null
{

    $cep = limparCEP($cep);

    // URL da API VIACEP
    $url = "https://viacep.com.br/ws/{$cep}/json/";

    // Inicializar cURL
    $ch = curl_init();

    // Configurar as opções do cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Executar a requisição e obter a resposta
    $conteudo = curl_exec($ch);

    // Verificar se a requisição foi bem-sucedida
    if ($conteudo === false) {
        // Fechar a sessão cURL em caso de erro
        curl_close($ch);
        return null;
    }

    // Decodificar o JSON retornado pela API
    $dados = json_decode($conteudo, true);

    // Fechar a sessão cURL
    curl_close($ch);

    // Verificar se o CEP é válido
    if (isset($dados['erro'])) {
        return null;
    }

    return $dados;
}

function limparCEP(string $cep): string
{
    $cep = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep) === 8) {
        return $cep;
    }
    return "00000000";
}

// 1. Lê o corpo da requisição JSON enviada pelo JavaScript
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// 2. Processa o CEP apenas se ele foi enviado
$result = null;
if (isset($data['cep'])) {
    $result = repositoryViaCEP($data['cep']);
}

// 3. Imprime a resposta JSON uma única vez
echo json_encode($result);