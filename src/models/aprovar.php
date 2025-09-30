<?php

include_once('../../config/db.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../config/vendor/autoload.php';

$mail = new PHPMailer(true);

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function sanitizeInt($data)
{
    return filter_var($data, FILTER_VALIDATE_INT);
}

function gerarSenha($length = 12)
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&'), 0, $length);
}

if(isset($_POST['solicitacao_id'], $_POST['status'])) {
    if($_POST['status'] === 'aprovada') {
        $solicitacao_id = isset($_POST['solicitacao_id']) ? sanitizeInt($_POST['solicitacao_id']) : NULL;
        $status = isset($_POST['status']) ? sanitizeInput($_POST['status']) : NULL;
        $cargo = isset($_POST['cargo']) ? sanitizeInput($_POST['cargo']) : NULL;

        // Gera a senha
        $senha = gerarSenha(12);
        $options = [
            'cost' => 12
        ];
        $senha_criptografada = password_hash($senha, PASSWORD_BCRYPT, $options);

        // Atualiza os dados no banco de dados
        $query =
                "UPDATE
                    SolicitacaoCadastro
                SET
                    status = :status, Cargo = :Cargo, senha = :senha, senha_bcrypt = :senha_bcrypt
                WHERE
                    SolicitacaoCadastro_id = :SolicitacaoCadastro_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':Cargo', $cargo, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha_criptografada, PDO::PARAM_STR);
        $stmt->bindParam(':senha_bcrypt', $senha_criptografada, PDO::PARAM_STR);
        $stmt->bindParam(':SolicitacaoCadastro_id', $solicitacao_id, PDO::PARAM_INT);
        
        if($stmt->execute()) {
            // Busca o email do usuário
            $query =
                    "SELECT
                        *
                    FROM
                        SolicitacaoCadastro
                    WHERE
                        SolicitacaoCadastro_id = :SolicitacaoCadastro_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':SolicitacaoCadastro_id', $solicitacao_id, PDO::PARAM_INT);
            
            if($stmt->execute()) {
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $email = $resultado['Email'];
                $usuario = $resultado['Usuario'];

                try {
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
                    $mail->addAddress($email, $usuario);
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';

                    // Mensagem que será enviada
                    $mail->Subject          = "Sua solicitação de cadastro foi aprovada!";
                    $mail->Body             = "<strong>NÃO RESPONDA ESTE EMAIL</strong><br><br>Olá, $usuario!<br>Sua solicitação de cadastro foi aprovada.<br><br><strong>Usuário:</strong> $usuario<br><strong>Senha:</strong> $senha<br><br>Por favor, altere sua senha após o primeiro login.";
                    $mail->AltBody          = "NÃO RESPONDA ESTE EMAIL Olá, $usuario! Sua solicitação de cadastro foi aprovada. Usuário: $usuario. Senha: $senha. Por favor, altere sua senha após o primeiro login.";

                    $mail->send();

                    echo
                        "<script>
                            alert('Solicitação processada e e-mail enviado com sucesso.');
                            window.location.href = '../pages/funcionario/aprovar.php';
                        </script>";
                } catch(Exception $e) {
                    echo
                        "<script>
                            alert('Erro ao enviar o e-mail: '" . $mail->ErrorInfo . "');
                        </script>";
                }
            }

        }
    } else {
        $solicitacao_id = isset($_POST['solicitacao_id']) ? sanitizeInt($_POST['solicitacao_id']) : NULL;
        $status = isset($_POST['status']) ? sanitizeInput($_POST['status']) : NULL;

        // Atualiza os dados no banco de dados
        $query =
        "UPDATE
            SolicitacaoCadastro
        SET
            status = :status
        WHERE
            SolicitacaoCadastro_id = :SolicitacaoCadastro_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':SolicitacaoCadastro_id', $solicitacao_id, PDO::PARAM_INT);

        if($stmt->execute()) {
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $email = $resultado['Email'];
            $usuario = $resultado['Usuario'];

            try {
                // Configurações servidor SMTP
                $mail->isSMTP();
                $mail->Host             = 'smtp.grude.ufmg.br';
                $mail->SMTPAuth         = true;
                $mail->Username         = 'odonto-suporte@ufmg.br';
                $mail->Password         = 'go3px';
                $mail->SMTPSecure       = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port             = 485;
                $mail->SMTPDebug        = 0;

                // Configurações do e-mail
                $mail->setFrom('bdhc@odonto.ufmg.br', 'Setor de Patologia - FAO');
                $mail->addAddress($email, $usuario);
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';

                // Mensagem que será enviada
                $mail->Subject = "Sua solicitação de cadastro foi reprovada.";
                $mail->Body    = "<strong>NÃO RESPONDA ESTE EMAIL</strong><br><br>Olá, $usuario!<br>Lamentamos informar, mas sua solicitação de cadastro foi reprovada.<br>Caso tenha alguma dúvida, entre em contato com o Setor de Patologia da Faculdade de Odontologia.";
                $mail->AltBody = "NÃO RESPONDA ESTE EMAIL Olá, $usuario! Lamentamos informar, mas sua solicitação de cadastro foi reprovada. Caso tenha alguma dúvida, entre em contato com o Setor de Patologia da Faculdade de Odontologia.";

                $mail->send();

                echo
                    "<script>
                        alert('Solicitação processada e e-mail enviado com sucesso.');
                        window.location.href = '../pages/funcionario/aprovar.php';
                    </script>";
            } catch(Exception $e) {
                echo
                    "<script>
                        alert('Erro ao enviar o e-mail: '" . $mail->ErrorInfo . "');
                    </script>";
            }
        }
    }
}