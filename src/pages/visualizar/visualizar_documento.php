<?php
include_once('../../../config/db.php');

// Verifica se o ID do paciente foi enviado
if (!isset($_POST['Paciente_id'])) {
    die("ID do paciente não fornecido.");
}

$paciente_id = $_POST['Paciente_id'];

try {
    $query = "SELECT * FROM Documentos WHERE Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $file_path = $result['CaminhoDocumento'];

        if (file_exists($file_path)) {
            // Define os cabeçalhos para exibir o arquivo no navegador
            header('Content-Type: ' . $result['TipoDocumento']);
            header('Content-Length: ' . $result['TamanhoDocumento']);
            
            // Remove o cabeçalho Content-Disposition ou define como 'inline' para exibir no navegador
            header('Content-Disposition: inline; filename="' . basename($file_path) . '"');

            // Envia o conteúdo do arquivo para o navegador
            readfile($file_path);
            exit;
        } else {
            echo "Arquivo não encontrado.";
        }
    } else {
        echo "Documento não encontrado.";
    }
} catch (PDOException $e) {
    die("Erro ao buscar documento: " . $e->getMessage());
}