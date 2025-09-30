<?php
include_once('../../config/db.php');

$email = $_POST['Email'];
$token = $_POST['Codigo'];
$token_desativado = 0;

try {

    $query =
            "SELECT
                *
            FROM
                RecuperarSenha
            WHERE
                EmailRecuperacao = :EmailRecuperacao AND TokenAtivo = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':EmailRecuperacao', $email, PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    $query =
            "UPDATE
                RecuperarSenha
            SET
                TokenAtivo = :TokenAtivo
            WHERE
                EmailRecuperacao = :EmailRecuperacao AND TokenAtivo = 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':TokenAtivo', $token_desativado, PDO::PARAM_INT);
    $stmt->bindParam(':EmailRecuperacao', $email, PDO::PARAM_STR);
    $stmt->execute();

    if($resultado && $token === $resultado['Token']) {
        header("Location: ../pages/senha/nova_senha.php?email=" . urlencode($email));
        exit;
    } else {
        echo
            "<script>
                alert('Código incorreto.');
                window.history.back();
            </script>";
        exit;
    }
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao conectar ao banco de dados: " . $e->getMessage() . "');
        </script>";
    exit;
}