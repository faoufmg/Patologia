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
$micro = isset($_POST['micro']) ? sanitizeInput($_POST['micro']) : NULL;
$nota = isset($_POST['nota']) ? sanitizeInput($_POST['nota']) : NULL;
$patologista = isset($_POST['patologista']) ? sanitizeInput($_POST['patologista']) : NULL;

// Tratamento para os campos de diagnótico
$diagnostico = isset($_POST['diagnostico']) ? sanitizeInput($_POST['diagnostico']) : NULL;
$diagnostico_texto = isset($_POST['diagnostico_texto']) ? sanitizeInput($_POST['diagnostico_texto']) : NULL;
$area_amostra = isset($_POST['area_amostra']) ? sanitizeInput($_POST['area_amostra']) : NULL;
$num_focos = isset($_POST['num_focos']) ? sanitizeInput($_POST['num_focos']) : NULL;
$focus_score = isset($_POST['focus_score']) ? sanitizeInput($_POST['focus_score']) : NULL;
$grau_inflamacao = isset($_POST['grau_inflamacao']) ? sanitizeInput($_POST['grau_inflamacao']) : NULL;
$centro_germinativo = isset($_POST['centro_germinativo']) ? sanitizeInput($_POST['centro_germinativo']) : NULL;
$atrofia_acinar = isset($_POST['atrofia_acinar']) ? sanitizeInput($_POST['atrofia_acinar']) : NULL;
$dilatacao_acinar = isset($_POST['dilatacao_acinar']) ? sanitizeInput($_POST['dilatacao_acinar']) : NULL;
$lesao_linfoepitelial = isset($_POST['lesao_linfoepitelial']) ? sanitizeInput($_POST['lesao_linfoepitelial']) : NULL;
$dilatacao_ductal = isset($_POST['dilatacao_ductal']) ? sanitizeInput($_POST['dilatacao_ductal']) : NULL;
$fibrose = isset($_POST['fibrose']) ? sanitizeInput($_POST['fibrose']) : NULL;
$infiltracao_adiposa = isset($_POST['infiltracao_adiposa']) ? sanitizeInput($_POST['infiltracao_adiposa']) : NULL;

try {

    if($diagnostico === 'Outro') {
        $query =
                "INSERT INTO
                    Microscopia(Microscopia, Diagnostico, Nota, Patologista, Paciente_id)
                VALUES
                    (:Microscopia, :Diagnostico, :Nota, :Patologista, :Paciente_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Microscopia', $micro, PDO::PARAM_STR);
        $stmt->bindParam(':Diagnostico', $diagnostico_texto, PDO::PARAM_STR);
        $stmt->bindParam(':Nota', $nota, PDO::PARAM_STR);
        $stmt->bindParam(':Patologista', $patologista, PDO::PARAM_STR);
        $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
        
        if($stmt->execute()) {
            echo
                "<script>
                    alert('Microscopia cadastrada com sucesso.');
                    window.location.href = '../pages/exames/micro.php';
                </script>";
            exit();
        }
    }
    else {
        $query =
                "INSERT INTO
                    Microscopia(Microscopia, Diagnostico, Nota, Patologista, Paciente_id)
                VALUES
                    (:Microscopia, :Diagnostico, :Nota, :Patologista, :Paciente_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Microscopia', $micro, PDO::PARAM_STR);
        $stmt->bindParam(':Diagnostico', $diagnostico, PDO::PARAM_STR);
        $stmt->bindParam(':Nota', $nota, PDO::PARAM_STR);
        $stmt->bindParam(':Patologista', $patologista, PDO::PARAM_STR);
        $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
        $stmt->execute();

        $microscopia_id = $pdo->lastInsertId();

        $query =
                "INSERT INTO
                    Sjogren(AreaAmostra, Focos, FocusScore, GrauInflamacao, CentrosGerminativos, AtrofiaAcinar, DilatacaoAcinar, LesaoLinfoepitelial, DilatacaoDuctal, Fibrose, InfiltracaoAdiposa, Microscopia_id)
                VALUES
                    (:AreaAmostra, :Focos, :FocusScore, :GrauInflamacao, :CentrosGerminativos, :AtrofiaAcinar, :DilatacaoAcinar, :LesaoLinfoepitelial, :DilatacaoDuctal, :Fibrose, :InfiltracaoAdiposa, :Microscopia_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam('AreaAmostra', $area_amostra, PDO::PARAM_STR);
        $stmt->bindParam('Focos', $num_focos, PDO::PARAM_STR);
        $stmt->bindParam('FocusScore', $focus_score, PDO::PARAM_STR);
        $stmt->bindParam('GrauInflamacao', $grau_inflamacao, PDO::PARAM_STR);
        $stmt->bindParam('CentrosGerminativos', $centro_germinativo, PDO::PARAM_STR);
        $stmt->bindParam('AtrofiaAcinar', $atrofia_acinar, PDO::PARAM_STR);
        $stmt->bindParam('DilatacaoAcinar', $dilatacao_acinar, PDO::PARAM_STR);
        $stmt->bindParam('LesaoLinfoepitelial', $lesao_linfoepitelial, PDO::PARAM_STR);
        $stmt->bindParam('DilatacaoDuctal', $dilatacao_ductal, PDO::PARAM_STR);
        $stmt->bindParam('Fibrose', $fibrose, PDO::PARAM_STR);
        $stmt->bindParam('InfiltracaoAdiposa', $infiltracao_adiposa, PDO::PARAM_STR);
        $stmt->bindParam('Microscopia_id', $microscopia_id, PDO::PARAM_INT);
        
        if($stmt->execute()) {
            echo
                "<script>
                    alert('Microscopia cadastrada com sucesso.');
                    window.location.href = '../pages/exames/micro.php';
                </script>";
            exit();
        }
    }

} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
            window.location.href = '../pages/exames/micro.php';
        </script>";
    exit();
}

?>