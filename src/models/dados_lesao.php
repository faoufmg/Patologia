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

// Solicitante
$solicitante = $_SESSION['nome_cadastro'];
$cargo = $_SESSION['cargo'];

// Campos comuns
$paciente_id = isset($_POST['paciente']) ? sanitizeInt($_POST['paciente']) : NULL;
$codigo_solicitacao = isset($_POST['codigo_solicitacao']) ? sanitizeInput($_POST['codigo_solicitacao']) : NULL;
$nome_paciente = isset($_POST['nome_paciente']) ? sanitizeInput($_POST['nome_paciente']) : NULL;
$data_nascimento = isset($_POST['data_nascimento']) ? sanitizeInput($_POST['data_nascimento']) : NULL;
$tempo_doenca = isset($_POST['tempo_doenca']) ? sanitizeInput($_POST['tempo_doenca']) : NULL;
$numero_lesao = isset($_POST['numero_lesao']) ? sanitizeInput($_POST['numero_lesao']) : NULL;
$envolvimento_osseo = isset($_POST['envolvimento_osseo']) ? sanitizeInput($_POST['envolvimento_osseo']) : NULL;
$foto_clinica = isset($_POST['foto_clinica']) ? sanitizeInput($_POST['foto_clinica']) : NULL;
$sintomatologia = isset($_POST['sintomatologia']) ? sanitizeInput($_POST['sintomatologia']) : NULL;
$tamanho = isset($_POST['tamanho']) ? sanitizeInput($_POST['tamanho']) : NULL;
$modo_coleta = isset($_POST['modo_coleta']) ? sanitizeInput($_POST['modo_coleta']) : NULL;
$manifestacao = isset($_POST['manifestacao']) ? sanitizeInput($_POST['manifestacao']) : NULL;
$data_coleta = !empty($_POST['data_coleta']) ? sanitizeInput($_POST['data_coleta']) : NULL;
$localizacao = isset($_POST['localizacao']) ? sanitizeInput($_POST['localizacao']) : NULL;
$exame_imagem = isset($_POST['exame_imagem']) ? sanitizeInput($_POST['exame_imagem']) : NULL;
$diagnostico_clinico = isset($_POST['diagnostico_clinico']) ? sanitizeInput($_POST['diagnostico_clinico']) : NULL;
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

// Campos adicionais
$sintomas = isset($_POST['sintomas']) ? sanitizeInput($_POST['sintomas']) : NULL;
$modo_coleta_outro = isset($_POST['modo_coleta_outro']) ? sanitizeInput($_POST['modo_coleta_outro']) : NULL;
$achados_exame_imagem = isset($_POST['achados_exame_imagem']) ? sanitizeInput($_POST['achados_exame_imagem']) : NULL;

if($achados_exame_imagem !== NULL && $exame_imagem == 'Sim') {
    $exame_imagem .= ", " . $achados_exame_imagem;
}

// echo $tempo_doenca . "<br>";
// echo $tipo_lesao . "<br>";
// echo $numero_lesao . "<br>";
// echo $envolvimento_osseo . "<br>";
// echo $coloracao . "<br>";
// echo $sintomatologia . "<br>";
// echo $sintomas . "<br>";
// echo $tamanho . "<br>";
// echo $modo_coleta . "<br>";
// echo $manifestacao . "<br>";
// echo $data_coleta . "<br>";
// echo $exame_imagem . "<br>";
// echo $localizacao . "<br>";
// echo $paciente_id . "<br>";
// echo $solicitante . "<br>";
// echo $diagnostico_clinico . "<br>";

try {

    $query =
            "INSERT INTO
                DadosLesao(Tempo, Tipo, Numero, EnvolvimentoOsseo, Coloracao, Sintomatologia, Sintoma, Tamanho, ModoColeta, Manifestacao, DataColeta, ExameImagem, Localizacao, Paciente_id, DiagnosticoClinico, ObservacaoLesao)
            VALUES
                (:Tempo, :Tipo, :Numero, :EnvolvimentoOsseo, :Coloracao, :Sintomatologia, :Sintoma, :Tamanho, :ModoColeta, :Manifestacao, :DataColeta, :ExameImagem, :Localizacao, :Paciente_id, :DiagnosticoClinico, :ObservacaoLesao)";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':Tempo', $tempo_doenca, PDO::PARAM_STR);
    $stmt->bindValue(':Tipo', $tipo_lesao, PDO::PARAM_STR);
    $stmt->bindValue(':Numero', $numero_lesao, PDO::PARAM_STR);
    $stmt->bindValue(':EnvolvimentoOsseo', $envolvimento_osseo, PDO::PARAM_STR);
    $stmt->bindValue(':Coloracao', $coloracao, PDO::PARAM_STR);
    $stmt->bindValue(':Sintomatologia', $sintomatologia, PDO::PARAM_STR);
    $stmt->bindValue(':Sintoma', $sintomas, PDO::PARAM_STR);
    $stmt->bindValue(':Tamanho', $tamanho, PDO::PARAM_STR);
    $stmt->bindValue(':ModoColeta', $modo_coleta, PDO::PARAM_STR);
    $stmt->bindValue(':Manifestacao', $manifestacao, PDO::PARAM_STR);
    $stmt->bindValue(':DataColeta', $data_coleta, PDO::PARAM_STR);
    $stmt->bindValue(':ExameImagem', $exame_imagem, PDO::PARAM_STR);
    $stmt->bindValue(':Localizacao', $localizacao, PDO::PARAM_STR);
    $stmt->bindValue(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->bindValue(':DiagnosticoClinico', $diagnostico_clinico, PDO::PARAM_STR);
    $stmt->bindValue(':ObservacaoLesao', $observacoes, PDO::PARAM_STR);
    $resultado = $stmt->execute();

    $dadoslesao_id = $pdo->lastInsertId();

    if($resultado) {

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
                        echo 
                            "<script>
                                alert('Tipo de arquivo inválido: " . $imagem['name'] . "');
                            </script>";
                        continue;
                    }

                    // Validação do tamanho do arquivo (50MB)
                    $tamanho_max = 50 * 1024 * 1024;
                    if ($tamanho_imagem > $tamanho_max) {
                        echo 
                            "<script>
                                alert('Tamanho do arquivo inválido: " . $imagem['name'] . ". O tamanho máximo permitido é 50MB.');
                            </script>";
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
                        $stmt->bindParam(':DadosLesao_id', $dadoslesao_id, PDO::PARAM_INT);
                        $stmt->execute();
                    } else {
                        error_log("Erro ao mover o arquivo de envolvimento ósseo: " . $nome_imagem . " para " . $caminho_arquivo);
                    }
                }
            }
        }

        if ($foto_clinica === "Sim" && isset($_FILES['foto_clinica_img'])) {
            $fotosClinicas = reArrayFiles($_FILES['foto_clinica_img']);
            
            foreach ($fotosClinicas as $foto) {
                if ($foto['error'] == UPLOAD_ERR_OK) {
                    $nome = basename($foto['name']);

                    // Sanitização do nome do arquivo
                    $nome = preg_replace('/[^a-zA-Z-9_.-]/', '_', $nome);
                    $nome_foto = uniqid() . "_" . $nome;

                    $tipo_foto = $foto['type'];
                    $tamanho_foto = $foto['size'];

                    // Validação do tipo de arquivo
                    $formatos_img = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                    if (!in_array($tipo_foto, $formatos_img)) {
                        echo 
                            "<script>
                                alert('Tipo de arquivo inválido: " . $imagem['name'] . "');
                            </script>";
                        continue;
                    }

                    // Validação do tamanho do arquivo (100MB)
                    $tamanho_max = 50 * 1024 * 1024;
                    if ($tamanho_foto > $tamanho_max) {
                        echo 
                            "<script>
                                alert('Tamanho do arquivo inválido: " . $imagem['name'] . ". O tamanho máximo permitido é 50MB.');
                            </script>";
                        continue;
                    }

                    // Diretório para armazenar
                    $diretorio_upload = '/var/www/BDHC/uploads/images/';

                    // Move o arquivo para o diretório
                    $caminho_arquivo = $diretorio_upload . $nome_foto;

                    if (move_uploaded_file($foto['tmp_name'], $caminho_arquivo)) {
                        $query =
                            "INSERT INTO
                                FotoClinica(NomeFoto, TipoFoto, TamanhoFoto, CaminhoFoto, DadosLesao_id)
                            VALUES
                                (:NomeFoto, :TipoFoto, :TamanhoFoto, :CaminhoFoto, :DadosLesao_id)";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':NomeFoto', $nome_foto, PDO::PARAM_STR);
                        $stmt->bindParam(':TipoFoto', $tipo_foto, PDO::PARAM_STR);
                        $stmt->bindParam(':TamanhoFoto', $tamanho_foto, PDO::PARAM_INT);
                        $stmt->bindParam(':CaminhoFoto', $caminho_arquivo, PDO::PARAM_STR);
                        $stmt->bindParam(':DadosLesao_id', $dadoslesao_id, PDO::PARAM_INT);
                        $stmt->execute();
                    } else {
                        error_log("Erro ao mover o arquivo de foto clínica: " . $nome_foto . " para " . $caminho_arquivo);
                    }
                }
            }
        }

        echo
            "<script>
                window.location.href = '../pages/exames/macro.php?id=" . urlencode($paciente_id) . "';
            </script>";

    } else {
        echo
            "<script>
                alert('Erro ao inserir o registro. Favor entrar em contato. Detalhes do erro: " . $stmt->errorInfo()[2] . "');
                window.location.href = '../pages/funcionario/dados_lesao.php';
            </script>";
    }

} catch(Exception $e) {
    echo
        "<script>
            alert('Erro: " . $e->getMessage() . "');
            window.location.href = '../pages/dentista/funcionario/dados_lesao.php';
        </script>";
}

?>