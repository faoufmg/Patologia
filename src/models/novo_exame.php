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

// Cargo do usuário
$cargo = $_SESSION['cargo'];

// Dados para a tabela SolicitacaoExame e Laboratorio
$data_solicitacao = isset($_POST['data_solicitacao']) ? sanitizeInput($_POST['data_solicitacao']) : NULL;
$status = 'Em Andamento';
$codigo_solicitacao = isset($_POST['codigo_solicitacao']) ? sanitizeInput($_POST['codigo_solicitacao']) : NULL;
$solicitante = isset($_POST['remetente']) ? sanitizeInput($_POST['remetente']) : NULL;
$exame_num = isset($_POST['exame_num']) ? sanitizeInput($_POST['exame_num']) : NULL;

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
$professores_id = isset(($_POST['professor'])) ? sanitizeInt($_POST['professor']) : NULL;

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

// Verifica se a data de nascimento é nula
if ($data_nascimento === NULL) {
    $data_nascimento = '0001-01-01';
}

try {

    // Inserção na tabela SolicitacaoExame
    $query =
            "INSERT INTO
                SolicitacaoExame(DataSolicitacao, StatusSolicitacao, CodigoSolicitacao, SolicitanteExame)
            VALUES
                (:DataSolicitacao, :StatusSolicitacao, :CodigoSolicitacao, :SolicitanteExame)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':DataSolicitacao', $data_solicitacao, PDO::PARAM_STR);
    $stmt->bindParam(':StatusSolicitacao', $status, PDO::PARAM_STR);
    $stmt->bindParam(':CodigoSolicitacao', $codigo_solicitacao, PDO::PARAM_STR);
    $stmt->bindParam('SolicitanteExame', $solicitante, PDO::PARAM_STR);
    $stmt->execute();

    // echo "inseriu solicitação<br>";

    // Inserçõa na tabela Paciente
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

    // echo "inseriu paciente<br>";

    // Obtem o ID do paciente inserido
    $paciente_id = $pdo->lastInsertId();

    // Insere na tabela Laboratorio
    $query =
            "INSERT INTO
                Laboratorio(ExameNum, DataEntradaMaterial, Paciente_id, Status, Professores_id)
            VALUES
                (:ExameNum, :DataEntradaMaterial, :Paciente_id, :Status, :Professores_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ExameNum', $exame_num, PDO::PARAM_STR);
    $stmt->bindParam(':DataEntradaMaterial', $data_solicitacao, PDO::PARAM_STR);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->bindParam(':Status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':Professores_id', $professores_id, PDO::PARAM_INT);
    $stmt->execute();

    // echo "inseriu laboratorio<br>";

    echo
        "<script>
            alert('Paciente cadastrado com sucesso.');
            window.location.href = '../pages/index/index_funcionario.php';
        </script>";
    
    exit;

} catch (Exception $e) {
    echo
        "<script>
            alert('" . $e->getMessage() . "');
            window.location.href = '../pages/funcionario/cadastro_exame.php';
        </script>";
}

?>