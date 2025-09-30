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
        'alunopos' => 'aluno'
    ];
    
    if (!isset($paths[$cargo])) {
        return false;
    }
    
    return '../../pages/' . $paths[$cargo] . '/visualizar_completo.php?Paciente_id=' . $paciente_id;
}

function reArrayFiles(&$file_post)
{
    // Retorna um array vazio se nenhum arquivo foi enviado
    if(empty($file_post['name'][0])) {
        return [];
    }

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

$paciente = isset($_POST['paciente']) ? sanitizeInput($_POST['paciente']) : NULL;
$tempo = isset($_POST['tempo_doenca']) ? sanitizeInput($_POST['tempo_doenca']) : NULL;
$numero = isset($_POST['numero_lesao']) ? sanitizeInput($_POST['numero_lesao']) : NULL;
$envolvimento_osseo = isset($_POST['envolvimento_osseo']) ? sanitizeInput($_POST['envolvimento_osseo']) : NULL;
$observacoes = isset($_POST['observacoes']) ? sanitizeInput($_POST['observacoes']) : NULL;

$coloracao = NULL;
if (isset($_POST['coloracao']) && is_array($_POST['coloracao'])) {
    $sanitized_array = array_map('sanitizeInput', $_POST['coloracao']);
    $coloracao = implode(', ', $sanitized_array);
}

$tipo_lesao = NULL;
if(isset($_POST['tipo_lesao']) && is_array($_POST['tipo_lesao'])) {
    $sanitized_array = array_map('sanitizeInput', $_POST['tipo_lesao']);
    $tipo_lesao = implode(', ', $sanitized_array);
}

if($_POST['sintomatologia'] === 'Sintomática') {
    $sintomatologia = isset($_POST['sintomatologia']) ? sanitizeInput($_POST['sintomatologia']) : NULL;
    $sintoma = isset($_POST['sintomas']) ? sanitizeInput($_POST['sintomas']) : '';
} else {
    $sintomatologia = isset($_POST['sintomatologia']) ? sanitizeInput($_POST['sintomatologia']) : NULL;
    $sintoma = '';
}

$tamanho = isset($_POST['tamanho']) ? sanitizeInput($_POST['tamanho']) : NULL;

if($_POST['modo_coleta'] === 'Outros') {
    $modo_coleta = isset($_POST['modo_coleta_outro']) ? sanitizeInput($_POST['modo_coleta_outro']) : NULL;
} else {
    $modo_coleta = isset($_POST['modo_coleta']) ? sanitizeInput($_POST['modo_coleta']) : NULL;
}

$manifestacao = isset($_POST['manifestacao']) ? sanitizeInput($_POST['manifestacao']) : NULL;
$data_coleta = isset($_POST['data_coleta']) ? sanitizeInput($_POST['data_coleta']) : NULL;
$localizacao = isset($_POST['localizacao']) ? sanitizeInput($_POST['localizacao']) : NULL;

if($_POST['exame_imagem'] === 'Sim')  {
    $exame_imagem = "Sim, " . (isset($_POST['achados_exame_imagem']) ? sanitizeInput($_POST['achados_exame_imagem']) : NULL);
} else {
    $exame_imagem = isset($_POST['exame_imagem']) ? sanitizeInput($_POST['exame_imagem']) : NULL;
}

$diagnostico_clinico = isset($_POST['diagnostico_clinico']) ? sanitizeInput($_POST['diagnostico_clinico']) : NULL;
$lesao_id = isset($_POST['lesao_id']) ? sanitizeInt($_POST['lesao_id']) : NULL;
$cargo = $_SESSION['cargo'] ?? NULL;

try {
    $query =
            "UPDATE
                DadosLesao
            SET
                Tempo = :Tempo,
                Tipo = :Tipo,
                Numero = :Numero,
                EnvolvimentoOsseo = :EnvolvimentoOsseo,
                Coloracao = :Coloracao,
                Sintomatologia = :Sintomatologia,
                Sintoma = :Sintoma,
                Tamanho = :Tamanho,
                ModoColeta = :ModoColeta,
                Manifestacao = :Manifestacao,
                DataColeta = :DataColeta,
                ExameImagem = :ExameImagem,
                Localizacao = :Localizacao,
                DiagnosticoClinico = :DiagnosticoClinico,
                ObservacaoLesao = :ObservacaoLesao
            WHERE
                DadosLesao_id = :DadosLesao_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Tempo', $tempo, PDO::PARAM_STR);
    $stmt->bindParam(':Tipo', $tipo_lesao, PDO::PARAM_STR);
    $stmt->bindParam(':Numero', $numero, PDO::PARAM_STR);
    $stmt->bindParam(':EnvolvimentoOsseo', $envolvimento_osseo, PDO::PARAM_STR);
    $stmt->bindParam(':Coloracao', $coloracao, PDO::PARAM_STR);
    $stmt->bindParam(':Sintomatologia', $sintomatologia, PDO::PARAM_STR);
    $stmt->bindParam(':Sintoma', $sintoma, PDO::PARAM_STR);
    $stmt->bindParam(':Tamanho', $tamanho, PDO::PARAM_STR);
    $stmt->bindParam(':ModoColeta', $modo_coleta, PDO::PARAM_STR);
    $stmt->bindParam(':Manifestacao', $manifestacao, PDO::PARAM_STR);
    $stmt->bindParam(':DataColeta', $data_coleta, PDO::PARAM_STR);
    $stmt->bindParam(':ExameImagem', $exame_imagem, PDO::PARAM_STR);
    $stmt->bindParam(':Localizacao', $localizacao, PDO::PARAM_STR);
    $stmt->bindParam(':DiagnosticoClinico', $diagnostico_clinico, PDO::PARAM_STR);
    $stmt->bindParam(':ObservacaoLesao', $observacoes, PDO::PARAM_STR);
    $stmt->bindParam(':DadosLesao_id', $lesao_id, PDO::PARAM_INT);

    if($stmt->execute()) {
    
        $query =
            "SELECT
                Paciente_id
            FROM
                DadosLesao
            WHERE
                DadosLesao_id = :DadosLesao_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':DadosLesao_id', $lesao_id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $paciente_id = $resultado['Paciente_id'];

        if ($envolvimento_osseo === 'Lesão intra-óssea' && isset($_FILES['envolvimento_osseo_img'])) {
            $imagensOsseo = reArrayFiles($_FILES['envolvimento_osseo_img']);

            foreach ($imagensOsseo as $imagem) {
                if ($imagem['error'] == UPLOAD_ERR_OK) {
                    $nome = basename($imagem['name']);

                    // Sanitização do nome do arquivo
                    $nome = preg_replace('/[^a-zA-Z-9_.-]/', '_', $nome);
                    $nome_imagem = uniqid() . "_" . $nome;

                    $tipo_imagem = $imagem['type'];
                    $tamanho_imagem = $imagem['size'];

                    // Validação do tipo de arquivo
                    $formatos_img = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                    if (!in_array($tipo_imagem, $formatos_img)) {
                        // Apenas ignora este arquivo e continua para o próximo
                        continue;
                    }

                    // Validação do tamanho do arquivo (50MB)
                    $tamanho_max = 50 * 1024 * 1024;
                    if ($tamanho_imagem > $tamanho_max) {
                        // Apenas ignora este arquivo e continua para o próximo
                        continue;
                    }

                    // Diretório para armazenar
                    $diretorio_upload = '/var/www/BDHC/uploads/images/';

                    // Move o arquivo para o diretório
                    $caminho_arquivo = $diretorio_upload . $nome_imagem;

                    if (move_uploaded_file($imagem['tmp_name'], $caminho_arquivo)) {
                        $query =
                            "INSERT INTO
                                ImagemLesao(NomeImagem, TipoImagem, TamanhoImagem, CaminhoImagem, DadosLesao_id)
                            VALUES
                                (:NomeImagem, :TipoImagem, :TamanhoImagem, :CaminhoImagem, :DadosLesao_id)";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':NomeImagem', $nome_imagem, PDO::PARAM_STR);
                        $stmt->bindParam(':TipoImagem', $tipo_imagem, PDO::PARAM_STR);
                        $stmt->bindParam(':TamanhoImagem', $tamanho_imagem, PDO::PARAM_INT);
                        $stmt->bindParam(':CaminhoImagem', $caminho_arquivo, PDO::PARAM_STR);
                        $stmt->bindParam(':DadosLesao_id', $lesao_id, PDO::PARAM_INT);
                        $stmt->execute();
                    } else {
                        error_log("Erro ao mover o arquivo de envolvimento ósseo: " . $nome_imagem . " para " . $caminho_arquivo);
                    }
                }
            }
        }
        
        $redirectUrl = getRedirectUrl($cargo, $paciente_id);
        
        if ($redirectUrl) {
            echo 
                "<script>
                    alert('Dados da Lesão editados com sucesso!');
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