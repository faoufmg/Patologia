<?php
include_once('../../config/db.php');

// Define a data de retirada
date_default_timezone_set('America/Sao_Paulo');
$DataRetirada = date('Y-m-d H:i:s');
$Laboratorio_id = $_POST['id'];

if($Laboratorio_id) {
    
    try {
        $query =
                "INSERT INTO
                    Retirada(Laboratorio_id, DataRetirada)
                VALUES
                    (:Laboratorio_id, :DataRetirada)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Laboratorio_id', $Laboratorio_id, PDO::PARAM_INT);
        $stmt->bindParam(':DataRetirada', $DataRetirada, PDO::PARAM_STR);
        $stmt->execute();

    } catch(Exception $e) {
        echo
            "<script>
                alert('Erro ao acessar o banco de dados: " . $e->getMessage() ."');
                window.location.href = '../pages/funcionario/visualizar_exame.php';
            </script>";
        exit();
    }
}
else {
    http_response_code(409); // Bad Request
    echo json_encode(['error' => 'ID não fornecido.']);
}