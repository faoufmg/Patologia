<?php

// Inclui a conexão com o banco e o autoload do Composer para carregar o mPDF
include_once('../../../config/db.php');
// Certifique-se de que o caminho para o autoload.php está correto
require_once '../../../config/vendor/autoload.php';

session_start();

function gerarPdfDeImagens(array $arquivosDeImagem, string $diretorioDestino, string $prefixoNomeArquivo): ?array
{
    if (empty($arquivosDeImagem)) {
        return null;
    }

    try {
        $mpdf = new \Mpdf\Mpdf();

        foreach ($arquivosDeImagem as $index => $caminhoTemporario) {
            // Adiciona uma nova página para cada imagem, exceto a primeira
            if ($index > 0) {
                $mpdf->AddPage();
            }
            // Adiciona a imagem ao PDF, centralizada e ajustada à página
            $html = '<div style="text-align: center; width: 100%; height: 100%;"><img src="' . $caminhoTemporario . '" style="max-width: 100%; max-height: 95vh;"/></div>';
            $mpdf->WriteHTML($html);
        }

        // Gera um nome único para o arquivo PDF
        $nomePdf = $prefixoNomeArquivo . uniqid() . '.pdf';
        $caminhoPdf = $diretorioDestino . $nomePdf;

        // Salva o arquivo PDF no servidor
        $mpdf->Output($caminhoPdf, \Mpdf\Output\Destination::FILE);

        return [
            'nome' => $nomePdf,
            'caminho' => $caminhoPdf,
            'tamanho' => filesize($caminhoPdf)
        ];
    } catch (\Mpdf\MpdfException $e) {
        error_log("Erro ao gerar PDF com mPDF: " . $e->getMessage());
        return null;
    }
}


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
        
        // NOVO BLOCO PARA PROCESSAR IMAGENS DE 'envolvimento_osseo_img'
        if ($envolvimento_osseo === 'Lesão intra-óssea' && isset($_FILES['envolvimento_osseo_img'])) {
            $imagensOsseo = reArrayFiles($_FILES['envolvimento_osseo_img']);
            $imagensParaPdf = [];
            
            foreach ($imagensOsseo as $imagem) {
                if ($imagem['error'] == UPLOAD_ERR_OK) {
                    $tamanho_imagem = $imagem['size'];
                    $tipo_imagem = $imagem['type'];
                    
                    // Validação do tamanho do arquivo (50MB)
                    $tamanho_max = 50 * 1024 * 1024;
                    if ($tamanho_imagem > $tamanho_max) continue;

                    // Separa imagens de PDFs
                    $formatos_img_validos = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (in_array($tipo_imagem, $formatos_img_validos)) {
                        $imagensParaPdf[] = $imagem['tmp_name'];
                    } elseif ($tipo_imagem === 'application/pdf') {
                        // Lógica para salvar PDFs individualmente (mantendo o comportamento original para PDFs)
                        $nome = preg_replace('/[^a-zA-Z0-9_.-]/', '_', basename($imagem['name']));
                        $nome_imagem = uniqid() . "_" . $nome;
                        $diretorio_upload = '/var/www/BDHC/uploads/images/';
                        $caminho_arquivo = $diretorio_upload . $nome_imagem;

                        if (move_uploaded_file($imagem['tmp_name'], $caminho_arquivo)) {
                            $query = "INSERT INTO ImagemLesao(NomeImagem, TipoImagem, TamanhoImagem, CaminhoImagem, DadosLesao_id) VALUES (:Nome, :Tipo, :Tamanho, :Caminho, :Id)";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute([
                                ':Nome' => $nome_imagem,
                                ':Tipo' => $tipo_imagem,
                                ':Tamanho' => $tamanho_imagem,
                                ':Caminho' => $caminho_arquivo,
                                ':Id' => $dadoslesao_id
                            ]);
                        }
                    }
                }
            }

            // Se houver imagens para agrupar, gera o PDF
            if (!empty($imagensParaPdf)) {
                $diretorio_upload = '/var/www/BDHC/uploads/images/';
                $infoPdf = gerarPdfDeImagens($imagensParaPdf, $diretorio_upload, 'osseo_');

                if ($infoPdf) {
                    $query = "INSERT INTO ImagemLesao(NomeImagem, TipoImagem, TamanhoImagem, CaminhoImagem, DadosLesao_id) VALUES (:Nome, :Tipo, :Tamanho, :Caminho, :Id)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([
                        ':Nome' => $infoPdf['nome'],
                        ':Tipo' => 'application/pdf',
                        ':Tamanho' => $infoPdf['tamanho'],
                        ':Caminho' => $infoPdf['caminho'],
                        ':Id' => $dadoslesao_id
                    ]);
                }
            }
        }

        // NOVO BLOCO PARA PROCESSAR IMAGENS DE 'foto_clinica_img'
        if ($foto_clinica === "Sim" && isset($_FILES['foto_clinica_img'])) {
            $fotosClinicas = reArrayFiles($_FILES['foto_clinica_img']);
            $imagensParaPdf = [];

            foreach ($fotosClinicas as $foto) {
                if ($foto['error'] == UPLOAD_ERR_OK) {
                    $tamanho_foto = $foto['size'];
                    $tipo_foto = $foto['type'];
                    
                    $tamanho_max = 50 * 1024 * 1024;
                    if ($tamanho_foto > $tamanho_max) continue;

                    $formatos_img_validos = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (in_array($tipo_foto, $formatos_img_validos)) {
                        $imagensParaPdf[] = $foto['tmp_name'];
                    } elseif ($tipo_foto === 'application/pdf') {
                        // Lógica para salvar PDFs individualmente
                        $nome = preg_replace('/[^a-zA-Z0-9_.-]/', '_', basename($foto['name']));
                        $nome_foto = uniqid() . "_" . $nome;
                        $diretorio_upload = '/var/www/BDHC/uploads/images/';
                        $caminho_arquivo = $diretorio_upload . $nome_foto;

                        if (move_uploaded_file($foto['tmp_name'], $caminho_arquivo)) {
                            $query = "INSERT INTO FotoClinica(NomeFoto, TipoFoto, TamanhoFoto, CaminhoFoto, DadosLesao_id) VALUES (:Nome, :Tipo, :Tamanho, :Caminho, :Id)";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute([
                                ':Nome' => $nome_foto,
                                ':Tipo' => $tipo_foto,
                                ':Tamanho' => $tamanho_foto,
                                ':Caminho' => $caminho_arquivo,
                                ':Id' => $dadoslesao_id
                            ]);
                        }
                    }
                }
            }

            // Se houver imagens para agrupar, gera o PDF
            if (!empty($imagensParaPdf)) {
                $diretorio_upload = '/var/www/BDHC/uploads/images/';
                $infoPdf = gerarPdfDeImagens($imagensParaPdf, $diretorio_upload, 'clinica_');

                if ($infoPdf) {
                    $query = "INSERT INTO FotoClinica(NomeFoto, TipoFoto, TamanhoFoto, CaminhoFoto, DadosLesao_id) VALUES (:Nome, :Tipo, :Tamanho, :Caminho, :Id)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([
                        ':Nome' => $infoPdf['nome'],
                        ':Tipo' => 'application/pdf',
                        ':Tamanho' => $infoPdf['tamanho'],
                        ':Caminho' => $infoPdf['caminho'],
                        ':Id' => $dadoslesao_id
                    ]);
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