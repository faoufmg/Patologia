<?php

include_once('../../config/db.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../config/vendor/autoload.php';

$mail = new PHPMailer(true);

$data = json_decode(file_get_contents('php://input'), true);
$email = $_SESSION['Email'];
$usuario = $_SESSION['nome_cadastro'];

// Gera um código aleatório
$codigo = rand(100000, 999999);

// Armazena o código na sessão para validação posterior
$_SESSION['codigo_verificacao'] = $codigo;

try {

    $mail->isSMTP();
    $mail->Host         = 'smtp.grude.ufmg.br';
    $mail->SMTPAuth     = true;
    $mail->Username     = 'odonto-suporte@ufmg.br';
    $mail->Password     = 'go3px';
    $mail->SMTPSecure   = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port         = 465;
    $mail->SMTPDebug    = 3;

    $mail->setFrom('bdhc@ufmg.br', 'Setor de Patologia - FAO');
    $mail->addAddress($email, $usuario);

    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    $mail->Subject      = "Código de Verificação";
    $mail->Body         = "<strong>NÃO RESPONDA ESSE E-MAIL</strong><br><br>Seu código de verificação é: $codigo";
    $mail->AltBody      = "NÃO RESPONDA ESSE E-MAIL Seu código de verificação é $codigo";

    $mail->send();

    // Retorna uma resposta JSON de sucesso
    echo json_encode(['success' => true, 'message' => 'Código enviado com sucesso.']);
} catch (Exception $e) {
    // Retorna uma resposta JSON de erro
    echo json_encode(['success' => false, 'message' => "Erro ao enviar o e-mail: {$mail->ErrorInfo}"]);
}
