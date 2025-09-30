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

function validarCPF($cpf) {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verifica se o CPF tem 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais (caso contrário, o CPF é inválido)
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Calcula o primeiro dígito verificador
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += $cpf[$i] * (10 - $i);
    }
    $resto = $soma % 11;
    $digito1 = ($resto < 2) ? 0 : 11 - $resto;

    // Calcula o segundo dígito verificador
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += $cpf[$i] * (11 - $i);
    }
    $resto = $soma % 11;
    $digito2 = ($resto < 2) ? 0 : 11 - $resto;

    // Verifica se os dígitos calculados são iguais aos informados
    if ($cpf[9] != $digito1 || $cpf[10] != $digito2) {
        return false;
    }

    return true;
}

$usuario = isset($_POST['usuario']) ? sanitizeInput($_POST['usuario']) : NULL;
$email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : NULL;
$telefone = isset($_POST['telefone']) ? sanitizeInput($_POST['telefone']) : NULL;
$endereco = isset($_POST['endereco']) ? sanitizeInput($_POST['endereco']) : NULL;
$funcao = isset($_POST['funcao']) ? sanitizeInput($_POST['funcao']) : NULL;

$cpf = isset($_POST['cpf']) ? sanitizeInput($_POST['cpf']) : NULL;
if(!validarCPF($cpf)) {
    echo
        "<script>
            alert('CPF inválido!');
            window.location.href = '../pages/solicitar_cadastro.php';
        </script>";
    exit();
}

try {
    $query =
            "SELECT
                COUNT(*)
            FROM
                SolicitacaoCadastro
            WHERE
                Usuario = :Usuario";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetchColumn();

    if($resultado > 0) {
        echo
            "<script>
                alert('Usuário já cadastrado.');
                window.location.href = '../pages/solicitar_cadastro.php';
            </script>";
        exit();
    }

    $query =
            "SELECT
                COUNT(*)
            FROM
                SolicitacaoCadastro
            WHERE
                Email = :Email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $resultado_email = $stmt->fetchColumn();
    
    if($resultado_email > 0) {
        echo
            "<script>
                alert('E-mail já cadastrado.');
                window.location.href = '../pages/solicitar_cadastro.php';
            </script>";
        exit();
    }

    $query =
            "INSERT INTO
                SolicitacaoCadastro(Usuario, Email, CPF, Telefone, Endereco, Funcao)
            VALUES
                (:Usuario, :Email, :CPF, :Telefone, :Endereco, :Funcao)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':Email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':CPF', $cpf, PDO::PARAM_STR);
    $stmt->bindParam(':Telefone', $telefone, PDO::PARAM_STR);
    $stmt->bindParam(':Endereco', $endereco, PDO::PARAM_STR);
    $stmt->bindParam(':Funcao', $funcao, PDO::PARAM_STR);

    if($stmt->execute()) {
        echo
            "<script>
                alert('Solicitação realizada com sucesso! Fique atento ao e-mail cadastrado para atualizações.');
                window.location.href = '../pages/login.php';
            </script>";
        exit();
    }
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
            window.location.href = '../pages/login.php';
        </script>";
    exit();
}