<?php
include_once('../../config/db.php');

$nova_senha = $_POST['NovaSenha'];
$options = [
    'cost' => 12
];
$nova_senha_criptografada = password_hash($nova_senha, PASSWORD_BCRYPT, $options);

$email = $_POST['Email'];

try {
    $query =
            "UPDATE
                SolicitacaoCadastro
            SET
                senha = :senha, senha_bcrypt = :senha_bcrypt
            WHERE
                Email = :Email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':senha', $nova_senha_criptografada, PDO::PARAM_STR);
    $stmt->bindParam(':senha_bcrypt', $nova_senha_criptografada, PDO::PARAM_STR);
    $stmt->bindParam(':Email', $email, PDO::PARAM_STR);

    if($stmt->execute()) {
        echo
            "<script>
                alert('Senha alterada com sucesso.');
                window.location.href = '../pages/login.php';
            </script>";
        exit;
    } else {
        echo
            "<script>
                alert('Erro ao alterar a senha.');
                window.history.back();
            </script>";
        exit;
    }
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
            window.history.back();
        </script>";
    exit;
}