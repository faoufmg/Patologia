<?php
include_once('../../../config/db.php');

header("Content-Type: application/json");

$paciente = $_POST['paciente'] ?? null;
$response = [];

if(empty($paciente)) {
    $response['displayText'] = "Selecione o paciente.";
    $response['status'] = 'info';
    echo json_encode($response);
    exit;
}

try {
    $sql =
        "SELECT
            L.ExameNum
        FROM
            Paciente AS P
        JOIN
            Laboratorio AS L
        ON
            L.Paciente_id = P.Paciente_id
        WHERE
            P.Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":Paciente_id", $paciente, PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $response['displayText'] = $row['ExameNum'];
        $response['status'] = 'no_stock';
    } else {
        $response['displayText'] = "Paciente não encontrado.";
        $response['status'] = 'no_stock';
    }
}
catch(PDOException $e) {
    $response['displayText'] = "Erro: " . $e->getMessage();
    $response['status'] = 'error';
}

echo json_encode($response);
?>