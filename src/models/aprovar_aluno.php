<?php
include_once('../../config/db.php');

function gerarSenha($length = 8)
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function sanitizeInt($data)
{
    return filter_var($data, FILTER_VALIDATE_INT);
}

$nome_aluno = isset($_POST['nome_aluno']) ? sanitizeInput($_POST['nome_aluno']) : NULL;

$senha = gerarSenha(8);
$senha_criptografada = md5($senha);

date_default_timezone_set('America/Sao_Paulo');
$liberado = date('Y-m-d H:i:s');

$status = 'aprovada';
$cargo = 'aluno';

try {

    $query = 
            "SELECT
                NomeAluno
            FROM
                AcessoAluno
            WHERE
                Ativo = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($resultados as $row) {
        if($row['NomeAluno'] === $nome_aluno) {
            $nome_existe = TRUE;
        }
    }

    if(!$nome_existe){
        $query =
                "INSERT INTO
                    AcessoAluno(NomeAluno, Senha, Liberado, status, Cargo, SenhaView)
                VALUES
                    (:NomeAluno, :Senha, :Liberado, :status, :Cargo, :SenhaView)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':NomeAluno', $nome_aluno, PDO::PARAM_STR);
        $stmt->bindParam('Senha', $senha_criptografada, PDO::PARAM_STR);
        $stmt->bindParam('Liberado', $liberado, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':Cargo', $cargo, PDO::PARAM_STR);
        $stmt->bindParam('SenhaView', $senha, PDO::PARAM_STR);

        if($stmt->execute()) {
            echo
                "<script>
                    alert('Acesso liberado com sucesso.');
                    window.location.href = '../pages/funcionario/aprovar_aluno.php';
                </script>";
            exit();
        }
    } else {
        echo
            "<script>
                alert('Usuário " . $nome_aluno . " já cadastrado');
                window.location.href = '../pages/funcionario/aprovar_aluno.php';
            </script>";
    }
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
            windown.location.href = '../pages/funcionario/aprovar_aluno.php';
        </script>";
    exit();
}