<?php

include_once('../../config/db.php');
session_start();

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function sanitizeInt($data)
{
    return filter_var($data, FILTER_VALIDATE_INT);
}

$cargo = $_SESSION['cargo'];
$rev_lamina = isset($_POST['rev_lamina']) ? sanitizeInput($_POST['rev_lamina']) : NULL;

$paciente_id = isset($_POST['paciente']) ? sanitizeInt($_POST['paciente']) : NULL;
$fragmentos = isset($_POST['fragmentos']) ? sanitizeInput($_POST['fragmentos']) : NULL;
$tecido = isset($_POST['tecido']) ? sanitizeInput($_POST['tecido']) : NULL;
$frag_inclusao = !empty($_POST['frag_inclusao']) ? sanitizeInput($_POST['frag_inclusao']) : '0';
$frag_descalcificacao = !empty($_POST['frag_descalcificacao']) ? sanitizeInput($_POST['frag_descalcificacao']) : '0';
$data = isset($_POST['data']) ? sanitizeInput($_POST['data']) : NULL;
$responsavel = isset($_POST['responsavel']) ? sanitizeInput($_POST['responsavel']) : NULL;
$observacoes = isset($_POST['observacoes']) ? sanitizeInput($_POST['observacoes']) : NULL;

if($data === '') {
    $data = '0001-01-01';
}

$formato = NULL;
if (isset($_POST['formato'])) {
    if ($_POST['formato'] === 'Outro') {
        $formato = isset($_POST['formato_outro']) ? sanitizeInput($_POST['formato_outro']) : NULL;
    } elseif (is_array($_POST['formato'])) {
        $sanitized_array = array_map('sanitizeInput', $_POST['formato']);
        $formato = implode(', ', $sanitized_array);
    } else {
        $formato = NULL;
    }
}

$superficie = NULL;
if (isset($_POST['superficie'])) {
    if ($_POST['superficie'] === 'Outro') {
        $superficie = isset($_POST['superficie_outro']) ? sanitizeInput($_POST['superficie_outro']) : NULL;
    } elseif (is_array($_POST['superficie'])) {
        $sanitized_array = array_map('sanitizeInput', $_POST['superficie']);
        $superficie = implode(', ', $sanitized_array);
    } else {
        $superficie = NULL;
    }
}

$coloracao = NULL;
if (isset($_POST['coloracao'])) {
    if ($_POST['coloracao'] === 'Outro') {
        $coloracao = isset($_POST['coloracao_outro']) ? sanitizeInput($_POST['coloracao_outro']) : NULL;
    } elseif (is_array($_POST['coloracao'])) {
        $sanitized_array = array_map('sanitizeInput', $_POST['coloracao']);
        $coloracao = implode(', ', $sanitized_array);
    } else {
        $coloracao = NULL;
    }
}

$consistencia = NULL;
if (isset($_POST['consistencia'])) {
    if ($_POST['consistencia'] === 'Outro') {
        $consistencia = isset($_POST['consistencia_outro']) ? sanitizeInput($_POST['consistencia_outro']) : NULL;
    } elseif (is_array($_POST['consistencia'])) {
        $sanitized_array = array_map('sanitizeInput', $_POST['consistencia']);
        $consistencia = implode(', ', $sanitized_array);
    } else {
        $consistencia = NULL;
    }
}

$tam_macro = isset($_POST['tam_macro']) ? sanitizeInput($_POST['tam_macro']) : NULL;
if(!str_contains($tam_macro, 'mm')) {
    $tam_macro = $tam_macro . 'mm';
}

try {

    if($rev_lamina === 'Sim') {
        $observacao = "Revisão de Lâmina";
        $query = 
                "INSERT INTO
                    Macroscopia(Paciente_id, Observacao)
                VALUES
                    (:Paciente_id, :Observacao)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
        $stmt->bindParam(':Observacao', $observacao, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        $query =
                "INSERT INTO
                    Macroscopia(Fragmentos, TipoFragmento, Formato, Superficie, ColoracaoMacro, Consistencia, FragInclusao, FragDescalcificacao, Data, Responsaveis, Paciente_id, TamanhoMacro, Observacao)
                VALUES
                    (:Fragmentos, :TipoFragmento, :Formato, :Superficie, :ColoracaoMacro, :Consistencia, :FragInclusao, :FragDescalcificacao, :Data, :Responsaveis, :Paciente_id, :TamanhoMacro, :Observacao)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Fragmentos', $fragmentos, PDO::PARAM_STR);
        $stmt->bindParam(':TipoFragmento', $tecido, PDO::PARAM_STR);
        $stmt->bindParam(':Formato', $formato, PDO::PARAM_STR);
        $stmt->bindParam(':Superficie', $superficie, PDO::PARAM_STR);
        $stmt->bindParam(':ColoracaoMacro', $coloracao, PDO::PARAM_STR);
        $stmt->bindParam(':Consistencia', $consistencia, PDO::PARAM_STR);
        $stmt->bindParam(':FragInclusao', $frag_inclusao, PDO::PARAM_STR);
        $stmt->bindParam(':FragDescalcificacao', $frag_descalcificacao, PDO::PARAM_STR);
        $stmt->bindParam(':Data', $data, PDO::PARAM_STR);
        $stmt->bindParam(':Responsaveis', $responsavel, PDO::PARAM_STR);
        $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
        $stmt->bindParam(':TamanhoMacro', $tam_macro, PDO::PARAM_STR);
        $stmt->bindParam(':Observacao', $observacoes, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    if($cargo === 'funcionário' || $cargo === 'funcionário_dev') {
        echo
            "<script>
                alert('Macroscopia cadastrada com sucesso.');
                window.location.href = '../pages/index/index_funcionario.php';
            </script>";
    } elseif($cargo === 'dentista') {
        echo
            "<script>
                alert('Macroscopia cadastrada com sucesso.');
                window.location.href = '../pages/index/index_dentista.php';
            </script>";
    } elseif($cargo === 'alunopos' || $cargo === 'alunopos_dev') {
        echo
            "<script>
                alert('Macroscopia cadastrada com sucesso.');
                window.location.href = '../pages/index/index_alunopos.php';
            </script>";
    }
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
        </script>";
    exit();
}