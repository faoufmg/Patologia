<?php
include_once('../../config/db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../config/vendor/autoload.php';
$mail = new PHPMailer(true);

function gerarToken($length = 8)
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

$token = gerarToken(8);
$email = $_POST['Email'];

try {
    // echo
    //     "<script>
    //         alert('email: $email, token: $token');
    //     </script>";

    $query =
            "INSERT INTO
                RecuperarSenha(Token, EmailRecuperacao)
            VALUES
                (:Token, :EmailRecuperacao)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':EmailRecuperacao', $email, PDO::PARAM_STR);
    $stmt->execute();

    $query =
            "SELECT
                Usuario
            FROM
                SolicitacaoCadastro
            WHERE
                Email = :Email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $nome = $resultado['Usuario'];

    if($stmt->execute()) {
        
        // Configurações servidor SMTP
        $mail->isSMTP();
        $mail->Host             = 'smtp.grude.ufmg.br';
        $mail->SMTPAuth         = true;
        $mail->Username         = 'odonto-suporte@ufmg.br';
        $mail->Password         = 'go3px';
        $mail->SMTPSecure       = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port             = 465;
        $mail->SMTPDebug        = 0;

        // Configurações do e-mail
        $mail->setFrom('bdhc@odonto.ufmg.br', 'Setor de Patologia - FAO');
        $mail->addAddress($email, $nome);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Mensagem que será enviada
        $mail->Subject          = "Código de verificação";
        $mail->Body             = "<strong>NÃO RESPONDA ESTE EMAIL</strong><br><br>Código de verificação: $token<br>";
        $mail->AltBody          = "NÃO RESPONDA ESTE EMAIL Código de verificação: $token";

        $mail->send();

        echo
            "<script>
                alert('Código de verificação enviado para sua caixa de e-mail.');
                window.location.href = '../pages/senha/token.php?email=$email';
            </script>";
        exit;
    }
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao conectar ao banco de dados: " . $e->getMessage() . "');
            window.location.href = '../pages/senha/recuperar_senha.php';
        </script>";
    exit;
}

?>