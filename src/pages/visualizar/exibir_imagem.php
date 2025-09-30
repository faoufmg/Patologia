<?php
include_once('../../../config/db.php');

// Verifica se o ID DA IMAGEM (não da lesão) foi enviado via GET
if (!isset($_GET['id'])) {
    die("ID da imagem não fornecido.");
}

$imagem_id = $_GET['id'];

try {
    $query = "SELECT * FROM ImagemLesao WHERE ImagemLesao_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $imagem_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $file_path = $result['CaminhoImagem'];

        if (file_exists($file_path)) {
            // Define os cabeçalhos para exibir o arquivo no navegador
            header('Content-Type: ' . $result['TipoImagem']);
            header('Content-Length: ' . filesize($file_path));
            header('Content-Disposition: inline; filename="' . basename($file_path) . '"');

            // Envia o conteúdo do arquivo para o navegador
            readfile($file_path);
            exit;
        } else {
            // Pode ser útil ter uma imagem "placeholder" para casos de erro
            http_response_code(404);
            echo "Arquivo não encontrado no servidor.";
        }
    } else {
        http_response_code(404);
        echo "Imagem não encontrada no banco de dados.";
    }
} catch (PDOException $e) {
    http_response_code(500);
    die("Erro ao buscar imagem: " . $e->getMessage());
}