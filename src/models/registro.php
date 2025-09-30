<?php
include_once('../../config/db.php');

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function sanitizeInt($data)
{
    return filter_var($data, FILTER_VALIDATE_INT);
}

$paciente_id = isset($_POST['paciente']) ? sanitizeInt($_POST['paciente']) : NULL;
$numero_exame = isset($_POST['numero_exame']) ? sanitizeInput($_POST['numero_exame']) : NULL;
$data_entrada = isset($_POST['data_entrada']) ? sanitizeInput($_POST['data_entrada']) : NULL;
$status = 'Em Andamento';

if ($paciente_id === false || $numero_exame === NULL || $data_entrada === NULL) {
    echo "<script>
            alert('Dados inválidos.');
            window.location.href = '../pages/funcionario/registro.php';
          </script>";
    exit;
}

try {
    $query = "SELECT Solicitante FROM Paciente WHERE Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultados === false) {
        echo "<script>
                alert('Paciente não encontrado.');
                window.location.href = '../pages/funcionario/registro.php';
              </script>";
        exit;
    }

    $remetente = $resultados['Solicitante'];

    // $query = "SELECT COUNT(*) FROM Laboratorio WHERE ExameNum = :ExameNum";
    // $stmt = $pdo->prepare($query);
    // $stmt->bindParam(':ExameNum', $numero_exame, PDO::PARAM_STR);
    // $stmt->execute();
    // $count = $stmt->fetchColumn();

    // if ($count > 0) {
    //     echo "<script>
    //             alert('Número de exame já cadastrado.');
    //             window.location.href = '../pages/funcionario/registro.php';
    //           </script>";
    //     exit;
    // }

    $query = "INSERT INTO Laboratorio(ExameNum, DataEntradaMaterial, Paciente_id, Status, Remetente)
              VALUES (:ExameNum, :DataEntradaMaterial, :Paciente_id, :Status, :Remetente)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ExameNum', $numero_exame, PDO::PARAM_STR);
    $stmt->bindParam(':DataEntradaMaterial', $data_entrada, PDO::PARAM_STR);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->bindParam(':Status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':Remetente', $remetente, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Registro realizado com sucesso.');
                window.location.href = '../pages/funcionario/registro.php';
              </script>";
        exit;
    }
} catch (PDOException $e) {
    echo "<script>
            alert('Erro ao conectar ao banco de dados.');
            window.location.href = '../pages/funcionario/registro.php';
          </script>";
    exit;
}