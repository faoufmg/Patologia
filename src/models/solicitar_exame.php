<?php
session_start(); // Inicia a sessão
include_once('../../config/db.php');

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function sanitizeInt($data)
{
    return filter_var($data, FILTER_VALIDATE_INT);
}

// Obtem o cargo
$cargo = $_SESSION['cargo'];

// Recebe dados do formulário
$data_solicitacao = isset($_POST['data_solicitacao']) ? sanitizeInput($_POST['data_solicitacao']) : NULL;
$codigo_solicitacao = isset($_POST['codigo_solicitacao']) ? sanitizeInput($_POST['codigo_solicitacao']) : NULL;

// Define o status do exame
$status = 'Em Andamento';

// Recebe o nome do solicitante
if (!isset($_SESSION['nome_cadastro'])) {
    throw new Exception("Sessão não iniciada ou nome do solicitante não definido.");
}
$solicitante = $_SESSION['nome_cadastro'];

try {

    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($data_solicitacao) || empty($codigo_solicitacao)) {
        throw new Exception("Todos os campos devem ser preenchidos.");
    }

    $query =
        "INSERT INTO
                SolicitacaoExame (DataSolicitacao, CodigoSolicitacao, StatusSolicitacao, SolicitanteExame)
            VALUES
                (:DataSolicitacao, :CodigoSolicitacao, :StatusSolicitacao, :SolicitanteExame)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':DataSolicitacao', $data_solicitacao, PDO::PARAM_STR);
    $stmt->bindParam(':CodigoSolicitacao', $codigo_solicitacao, PDO::PARAM_STR);
    $stmt->bindParam(':StatusSolicitacao', $status, PDO::PARAM_STR);
    $stmt->bindParam(':SolicitanteExame', $solicitante, PDO::PARAM_STR);
    $funcionou = $stmt->execute();

    if ($funcionou && $cargo === 'dentista') {
        header('Location: ../pages/dentista/dados_paciente.php?cod=' . urlencode($codigo_solicitacao));
    } elseif($funcionou && $cargo === 'funcionário') {
        header('Location: ../pages/funcionario/dados_paciente.php?cod=' . urlencode($codigo_solicitacao));
    } else {
        echo
        "<script>
            alert('Erro na solicitação do exame. Favor Entrar em contato.');
            window.location.href = '../pages/dentista/solicitar_exame.php';
        </script>";
    }
} catch (Exception $e) {
    echo
    "<script>
        alert('" . $e->getMessage() . "');
        window.location.href = '../pages/dentista/solicitar_exame.php'; 
    </script>";
}
