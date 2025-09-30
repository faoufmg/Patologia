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

// Campos comuns para todos pacientes
$codigo_solicitacao = isset($_POST['codigo_solicitacao']) ? sanitizeInput($_POST['codigo_solicitacao']) : NULL;
$procedencia = isset($_POST['procedencia']) ? sanitizeInput($_POST['procedencia']) : NULL;
$especificacao_procedencia = isset($_POST['especificacao_procedencia']) ? sanitizeInput($_POST['especificacao_procedencia']) : NULL;
$paciente = isset($_POST['paciente']) ? sanitizeInput($_POST['paciente']) : NULL;
$sexo = isset($_POST['sexo']) ? sanitizeInput($_POST['sexo']) : NULL;
$data_nascimento = !empty($_POST['data_nascimento']) ? sanitizeInput($_POST['data_nascimento']) : NULL;
$idade = isset($_POST['idade']) ? sanitizeInt($_POST['idade']) : NULL;
$telefone = isset($_POST['telefone']) ? sanitizeInput($_POST['telefone']) : NULL;
$endereco = isset($_POST['endereco']) ? sanitizeInput($_POST['endereco']) : NULL;
$bairro = isset($_POST['bairro']) ? sanitizeInput($_POST['bairro']) : NULL;
$cep = isset($_POST['cep']) ? sanitizeInput($_POST['cep']) : NULL;
$cidade_estado = isset($_POST['cidade_estado']) ? sanitizeInput($_POST['cidade_estado']) : NULL;
$cor_pele = isset($_POST['cor_pele']) ? sanitizeInput($_POST['cor_pele']) : NULL;
$profissao = isset($_POST['profissao']) ? sanitizeInput($_POST['profissao']) : NULL;
$remetente = isset($_POST['remetente']) ? sanitizeInput($_POST['remetente']) : NULL;
$exame_num = isset($_POST['exame_num']) ? sanitizeInput($_POST['exame_num']) : NULL;

// Campos para fumantes
$fumante = isset($_POST['fumante']) ? sanitizeInput($_POST['fumante']) : NULL;
$especificacao_fumante = isset($_POST['especificacao_fumante']) ? sanitizeInput($_POST['especificacao_fumante']) : NULL;
$quantidade_fumante = isset($_POST['quantidade_fumante']) ? sanitizeInput($_POST['quantidade_fumante']) : NULL;
$tempo_fumante = isset($_POST['tempo_fumante']) ? sanitizeInput($_POST['tempo_fumante']) : NULL;
$tempo_foi_fumante = isset($_POST['tempo_foi_fumante']) ? sanitizeInput($_POST['tempo_foi_fumante']) : NULL;
$tempo_fumante_parou = isset($_POST['tempo_fumante_parou']) ? sanitizeInput($_POST['tempo_fumante_parou']) : NULL;

if ($fumante === 'Sim') {
    $fumante = "Tipo: " . $especificacao_fumante . ", Quantidade: " . $quantidade_fumante . ", Tempo: " . $tempo_fumante;
} elseif ($fumante === 'Ex-fumante') {
    $fumante = "Tipo: " . $especificacao_fumante . ", Quantidade: " . $quantidade_fumante . ", Tempo que fumou: " . $tempo_foi_fumante . ", Tempo que parou: " . $tempo_fumante_parou;
}

// Campos para etilistas
$etilista = isset($_POST['etilista']) ? sanitizeInput($_POST['etilista']) : NULL;
$especificacao_estilista = isset($_POST['especificacao_etilista']) ? sanitizeInput($_POST['especificacao_etilista']) : NULL;
$quantidade_etilista = isset($_POST['quantidade_etilista']) ? sanitizeInput($_POST['quantidade_etilista']) : NULL;
$tempo_etilista = isset($_POST['tempo_etilista']) ? sanitizeInput($_POST['tempo_etilista']) : NULL;
$tempo_foi_etilista = isset($_POST['tempo_foi_etilista']) ? sanitizeInput($_POST['tempo_foi_etilista']) : NULL;
$tempo_etilista_parou = isset($_POST['tempo_etilista_parou']) ? sanitizeInput($_POST['tempo_etilista_parou']) : NULL;

if ($etilista === 'Sim') {
    $etilista = "Tipo: " . $especificacao_estilista . ", Quantidade: " . $quantidade_etilista . ", Tempo: " . $tempo_etilista;
} elseif ($etilista === 'Ex-etilista') {
    $etilista = "Tipo: " . $especificacao_estilista . ", Quantidade: " . $quantidade_etilista . ", Tempo que bebeu: " . $tempo_foi_etilista . ", Tempo que parou: " . $tempo_etilista_parou;
}

// Campos especificos
$cartao_sus = isset($_POST['cartao_sus']) ? sanitizeInput($_POST['cartao_sus']) : NULL;

// Solicitante
if ($remetente !== NULL) {
    $solicitante = $remetente;
} else {
    $solicitante = $_SESSION['nome_cadastro'];
}

if ($data_nascimento === NULL) {
    $data_nascimento = '0001-01-01';
}

try {

    $query =
        "INSERT INTO
                Paciente(NomePaciente, DataNascimento, Sexo, Idade, Telefone, Endereco, Bairro, CEP, CidadeEstado, CartaoSUS, CorPele, Fumante, Etilista, Profissao, SolicitantePaciente, CodigoSolicitacao, ProcedenciaExame, EspecificacaoExame)
            VALUES
                (:NomePaciente, :DataNascimento, :Sexo, :Idade, :Telefone, :Endereco, :Bairro, :CEP, :CidadeEstado, :CartaoSUS, :CorPele, :Fumante, :Etilista, :Profissao, :SolicitantePaciente, :CodigoSolicitacao, :ProcedenciaExame, :EspecificacaoExame)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':NomePaciente', $paciente, PDO::PARAM_STR);
    $stmt->bindParam(':DataNascimento', $data_nascimento, PDO::PARAM_STR);
    $stmt->bindParam(':Sexo', $sexo, PDO::PARAM_STR);
    $stmt->bindParam(':Idade', $idade, PDO::PARAM_INT);
    $stmt->bindParam(':Telefone', $telefone, PDO::PARAM_STR);
    $stmt->bindParam(':Endereco', $endereco, PDO::PARAM_STR);
    $stmt->bindParam(':Bairro', $bairro, PDO::PARAM_STR);
    $stmt->bindParam(':CEP', $cep, PDO::PARAM_STR);
    $stmt->bindParam(':CidadeEstado', $cidade_estado, PDO::PARAM_STR);
    $stmt->bindParam(':CartaoSUS', $cartao_sus, PDO::PARAM_STR);
    $stmt->bindParam(':CorPele', $cor_pele, PDO::PARAM_STR);
    $stmt->bindParam(':Fumante', $fumante, PDO::PARAM_STR);
    $stmt->bindParam(':Etilista', $etilista, PDO::PARAM_STR);
    $stmt->bindParam(':Profissao', $profissao, PDO::PARAM_STR);
    $stmt->bindParam(':SolicitantePaciente', $solicitante, PDO::PARAM_STR);
    $stmt->bindParam(':CodigoSolicitacao', $codigo_solicitacao, PDO::PARAM_STR);
    $stmt->bindParam(':ProcedenciaExame', $procedencia, PDO::PARAM_STR);
    $stmt->bindParam(':EspecificacaoExame', $especificacao_procedencia, PDO::PARAM_STR);
    $resultados = $stmt->execute();

    $paciente_id = $pdo->lastInsertId();

    if ($resultados) {

        if ($procedencia === 'Odilon Behrens') {
            if (isset($_FILES['pep']) && $_FILES['pep']['error'] == UPLOAD_ERR_OK) {
                $documento = $_FILES['pep'];
                $nome = basename($documento['name']);

                // Sanitização do nome do arquivo
                $nome = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $nome); // Substitui caracteres inválidos por "_"
                $nome_documento = uniqid() . "_" . $nome; // Adiciona um ID único ao nome do arquivo

                $tipo_documento = $documento['type'];
                $tamanho_documento = $documento['size'];

                // Validação do tipo de arquivo
                $formatos_doc = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($tipo_documento, $formatos_doc)) {
                    echo 
                        "<script>
                            alert('Tipo de arquivo não permitido.');
                            window.location.href = '../pages/dentista/dados_paciente.php?cod=" . urlencode($codigo_solicitacao) . "';
                        </script>";
                    exit;
                }

                // Validação do tamanho do arquivo (50MB)
                $tamanho_maximo = 50 * 1024 * 1024; // 50MB em bytes
                if ($tamanho_documento > $tamanho_maximo) {
                    echo 
                        "<script>
                            alert('O arquivo é muito grande. O tamanho máximo permitido é 50MB.');
                            window.location.href = '../pages/dentista/dados_paciente.php?cod=" . urlencode($codigo_solicitacao) . "';
                        </script>";
                    exit;
                }

                // Diretório para armazenar os arquivos
                $diretorio_upload = '/var/www/BDHC/uploads/docs/';

                // Move o arquivo para o diretório de upload
                $caminho_arquivo = $diretorio_upload . $nome_documento;
                if (move_uploaded_file($documento['tmp_name'], $caminho_arquivo)) {
                    $query = 
                            "INSERT INTO 
                                Documentos(Paciente_id, NomeDocumento, TipoDocumento, TamanhoDocumento, CaminhoDocumento)
                            VALUES
                                (:Paciente_id, :NomeDocumento, :TipoDocumento, :TamanhoDocumento, :CaminhoDocumento)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
                    $stmt->bindParam(':NomeDocumento', $nome_documento, PDO::PARAM_STR);
                    $stmt->bindParam(':TipoDocumento', $tipo_documento, PDO::PARAM_STR);
                    $stmt->bindParam(':TamanhoDocumento', $tamanho_documento, PDO::PARAM_INT);
                    $stmt->bindParam(':CaminhoDocumento', $caminho_arquivo, PDO::PARAM_STR);
                    $stmt->execute();
                    $id = $pdo->lastInsertId();
                } else {
                    echo
                        "<script>
                            alert('Erro ao mover o arquivo para o diretório de upload.');
                        </script>";
                }
            } else {
                echo
                    "<script>
                        alert('Erro no upload do arquivo.');
                    </script>";
            }
        }

        date_default_timezone_set('America/Sao_Paulo');
        $data_solicitacao = date('Y-m-d');
        $status = 'Em Andamento';

        $query = "INSERT INTO Laboratorio(ExameNum, DataEntradaMaterial, Paciente_id, Status, Remetente)
                VALUES (:ExameNum, :DataEntradaMaterial, :Paciente_id, :Status, :Remetente)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':ExameNum', $exame_num, PDO::PARAM_STR);
        $stmt->bindParam(':DataEntradaMaterial', $data_solicitacao, PDO::PARAM_STR);
        $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
        $stmt->bindParam(':Status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':Remetente', $solicitante, PDO::PARAM_STR);
        $stmt->execute();

        if ($cargo === 'funcionário') {
            echo
                "<script>
                    window.location.href = '../pages/index/index_funcionario.php';
                </script>";
        } elseif($cargo === 'alunopos') {
            echo
                "<script>
                    window.location.href = '../pages/index/aluno_pos.php';
                </script>";
        }

        exit();
    } else {
        echo
            "<script>
                alert('Erro ao inserir o registro. Favor entrar em contato. Detalhes do erro: " . $stmt->errorInfo()[2] . "');
                window.location.href = '../pages/dentista/dados_paciente.php?cod=" . urlencode($codigo_solicitacao) . "';
            </script>";
    }
} catch (Exception $e) {
    echo
    "<script>
            alert('Erro: " . $e->getMessage() . "');
            window.location.href = '../pages/dentista/dados_paciente.php?cod=" . urlencode($codigo_solicitacao) . "';
        </script>";
}
