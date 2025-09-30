<?php
$email = $_GET['email'];
?>

<!DOCTYPE html>
<html class="bd-login" lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="#" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../public/css/login.css" /> <!-- Arquivo CSS personalizado -->
</head>

<body>
    <section class="container d-flex align-items-center justify-content-center vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-5 login-container">
                <h2 class="text-center mb-4"><strong>Recuperar Senha</strong></h2>
                <form action="../../models/token.php" method="POST">
                    <div class="form-group mb-3">
                        <label for="Email" class="form-label"><strong>E-mail</strong></label>
                        <input type="text" value="<?php echo $email ?>" class="form-control" placeholder="Digite o e-mail cadastrado" id="Email" name="Email" required />
                    </div>

                    <div class="form-group mb-3">
                        <label for="Codigo" class="form-label"><strong>Código de verificação</strong></label>
                        <input type="text" class="form-control" placeholder="Digite o código recebido por email" id="Codigo" name="Codigo" required />
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Verificar</button>
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