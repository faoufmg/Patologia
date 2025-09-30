<?php
include_once('../../../config/db.php');
session_start();
$usuario = $_SESSION['nome_cadastro'];

try {
    $query = 
            "SELECT
                *
            FROM
                SolicitacaoCadastro
            WHERE
                Usuario = :Usuario";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $resultado['Email'];
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao conectar ao banco de dados: " . $e->getMessage() . "');
        </script>";
    exit;
}
?>

<!DOCTYPE html>
<html class="bd-login" lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Trocar Senha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="#" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../public/css/login.css" /> <!-- Arquivo CSS personalizado -->
</head>

<body>
    <section class="container d-flex align-items-center justify-content-center vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-5 login-container">
                <h2 class="text-center mb-4"><strong>Trocar Senha</strong></h2>
                <form action="../../models/recuperar_senha.php" method="POST">
                    <div class="form-group mb-3">
                        <label for="Email" class="form-label"><strong>E-mail</strong></label>
                        <input type="text" class="form-control" value="<?php echo $email ?>" placeholder="Digite o e-mail cadastrado" id="Email" name="Email" required />
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <p class="copy"><?php echo date('Y'); ?> © Copyright -
            <a href="https://www.odonto.ufmg.br" target="_blank">Faculdade de Odontologia da UFMG</a>
            <img src="/public/image/sti (3).png" style="height: 50px;" alt="Logo UFMG" class="img-footer">
        </p>    
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>