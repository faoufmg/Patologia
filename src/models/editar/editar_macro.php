<?php
include_once('../../../config/db.php');
session_start();

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function sanitizeInt($data)
{
    return filter_var($data, FILTER_VALIDATE_INT);
}

function getRedirectUrl($cargo, $paciente_id) {
    $paths = [
        'professor' => 'professor',
        'professor_dev' => 'professor',
        'funcionário' => 'funcionario',
        'funcionário_dev' => 'funcionario',
        'dentista' => 'dentista',
        'alunopos' => 'alunopos'
    ];
    
    if (!isset($paths[$cargo])) {
        return false;
    }
    
    return '../../pages/' . $paths[$cargo] . '/visualizar_completo.php?Paciente_id=' . $paciente_id;
}

$paciente = isset($_POST['paciente']) ? sanitizeInput($_POST['paciente']) : NULL;
$fragmentos = isset($_POST['fragmentos']) ? sanitizeInput($_POST['fragmentos']) : NULL;
$tecido = isset($_POST['tecido']) ? sanitizeInput($_POST['tecido']) : NULL;

$formato = NULL;
if ($_POST['formato'] === 'Outro') {
    $formato = isset($_POST['formato_outro']) ? sanitizeInput($_POST['formato_outro']) : NULL;
} elseif (isset($_POST['formato']) && is_array($_POST['formato'])) {
    $sanitized_array = array_map('sanitizeInput', $_POST['formato']);
    $formato = implode(', ', $sanitized_array);
}

$superficie = NULL;
if ($_POST['superficie'] === 'Outro') {
    $superficie = isset($_POST['superficie_outro']) ? sanitizeInput($_POST['superficie_outro']) : NULL;
} elseif (isset($_POST['superficie']) && is_array($_POST['superficie'])) {
    $sanitized_array = array_map('sanitizeInput', $_POST['superficie']);
    $superficie = implode(', ', $sanitized_array);
}

$coloracao = NULL;
if ($_POST['coloracao'] === 'Outro') {
    $coloracao = isset($_POST['coloracao_outro']) ? sanitizeInput($_POST['coloracao_outro']) : NULL;
} elseif (isset($_POST['coloracao']) && is_array($_POST['coloracao'])) {
    $sanitized_array = array_map('sanitizeInput', $_POST['coloracao']);
    $coloracao = implode(', ', $sanitized_array);
}

$consistencia = NULL; 
if (isset($_POST['consistencia'])) {
    
    if ($_POST['consistencia'] === 'Outro') {
        $consistencia = isset($_POST['consistencia_outro']) ? sanitizeInput($_POST['consistencia_outro']) : NULL;
    } else {
        $sanitized_array = array_map('sanitizeInput', $_POST['consistencia']);
        $consistencia = implode(', ', $sanitized_array);
    }
}

$tam_macro = isset($_POST['tam_macro']) ? sanitizeInput($_POST['tam_macro']) : NULL;
if(!str_contains($tam_macro, 'mm')) {
    $tam_macro = $tam_macro . 'mm';
}

$frag_inclusao = isset($_POST['frag_inclusao']) ? sanitizeInput($_POST['frag_inclusao']) : NULL;
$frag_descalcificacao = isset($_POST['frag_descalcificacao']) ? sanitizeInput($_POST['frag_descalcificacao']) : NULL;
$data = isset($_POST['data']) ? sanitizeInput($_POST['data']) : NULL;
$responsavel = isset($_POST['responsavel']) ? sanitizeInput($_POST['responsavel']) : NULL;
$observacao = isset($_POST['observacao']) ? sanitizeInput($_POST['observacao']) : NULL;
$macroscopia_id = isset($_POST['macroscopia_id']) ? sanitizeInt($_POST['macroscopia_id']) : NULL;
$cargo = $_SESSION['cargo'] ?? NULL;


try {
    $query =
            "UPDATE
                Macroscopia
            SET
                Fragmentos = :Fragmentos,
                TipoFragmento = :TipoFragmento,
                Formato = :Formato,
                Superficie = :Superficie,
                ColoracaoMacro = :ColoracaoMacro,
                Consistencia = :Consistencia,
                FragInclusao = :FragInclusao,
                FragDescalcificacao = :FragDescalcificacao,
                TamanhoMacro = :TamanhoMacro,
                Data = :Data,
                Responsaveis = :Responsaveis,
                Observacao = :Observacao
            WHERE
                Macroscopia_id = :Macroscopia_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Fragmentos', $fragmentos, PDO::PARAM_STR);
    $stmt->bindParam(':TipoFragmento', $tecido, PDO::PARAM_STR);
    $stmt->bindParam(':Formato', $formato, PDO::PARAM_STR);
    $stmt->bindParam(':Superficie', $superficie, PDO::PARAM_STR);
    $stmt->bindParam(':ColoracaoMacro', $coloracao, PDO::PARAM_STR);
    $stmt->bindParam(':Consistencia', $consistencia, PDO::PARAM_STR);
    $stmt->bindParam(':FragInclusao', $frag_inclusao, PDO::PARAM_STR);
    $stmt->bindParam(':FragDescalcificacao', $frag_descalcificacao, PDO::PARAM_STR);
    $stmt->bindParam(':TamanhoMacro', $tam_macro, PDO::PARAM_STR);
    $stmt->bindParam(':Data', $data, PDO::PARAM_STR);
    $stmt->bindParam(':Responsaveis', $responsavel, PDO::PARAM_STR);
    $stmt->bindParam(':Observacao', $observacao, PDO::PARAM_STR);
    $stmt->bindParam(':Macroscopia_id', $macroscopia_id, PDO::PARAM_INT);

    if($stmt->execute()) {

        $query =
            "SELECT
                Paciente_id
            FROM
                Macroscopia
            WHERE
                Macroscopia_id = :Macroscopia_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Macroscopia_id', $macroscopia_id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $paciente_id = $resultado['Paciente_id'];
        
        $redirectUrl = getRedirectUrl($cargo, $paciente_id);
        
        if ($redirectUrl) {
            echo 
                "<script>
                    alert('Macroscopia editada com sucesso!');
                    window.location.href = '$redirectUrl';
                </script>";
        } else {
            echo "<script>alert('Erro: Cargo não reconhecido!');</script>";
        }
    }
} catch (Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
        </script>";
}
?>