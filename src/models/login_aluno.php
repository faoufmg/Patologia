<?php
include_once('../../config/db.php');
session_start();

$usuario = isset($_POST['nome_cadastro']) ? trim($_POST['nome_cadastro']) : FALSE;
$senha = isset($_POST['senha_cadastro']) ? md5($_POST['senha_cadastro']) : FALSE;

if(!$usuario || !$senha) {
    echo
        "<script>
            alert('Você deve digitar seu usuário e senha!');
            window.location.href = '../pages/login_aluno.php';
        </script>";
    exit();
}

try {
    // Verifica se o usuário está cadastrado
    $query =
            "SELECT
                NomeAluno, Senha, status, Ativo
            FROM
                AcessoAluno
            WHERE
                NomeAluno = :NomeAluno";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':NomeAluno', $usuario, PDO::PARAM_STR);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['nome_cadastro'] = stripslashes($dados['NomeAluno']);
        $_SESSION['Cargo'] = stripslashes($dados['Cargo']);

        // Verifica o status do aluno
        if($dados['status'] === 'revogado') {
            echo
                "<script>
                    alert('Seu acesso foi revogado!');
                    window.location.href = '../pages/login_aluno.php';
                </script>";
            exit;
        }

        // Verifica a senha
        if(!strcmp($senha, $dados['Senha'])) {
            header('Location: ../pages/index/index_aluno.php');
            exit;
        } else {
            echo
                "<script>
                    alert('Acesso negado.');
                    window.location.href = '../pages/login_aluno.php';
                </script>";
            exit;
        }
    } else {
        echo
            "<script>
                alert('Login inexistente.');
                window.location.href = '../pages/login_aluno.php';
            </script>";
        exit;
    }
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
            window.location.href = '../pages/login.php';
        </script>";
    exit();
}

?>