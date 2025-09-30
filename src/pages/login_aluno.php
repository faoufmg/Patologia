<!DOCTYPE html>
<html class="bd-login" lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Login BDHC - Alunos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="#" type="image/x-icon" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />

    <!-- Estilos customizados -->
    <link rel="stylesheet" href="../../public/css/bootstrap-grid.css" />
    <link rel="stylesheet" href="../../public/css/bootstrap-reboot.css" />
    <link rel="stylesheet" href="../../public/css/bootstrap-utilities.css" />
    <link rel="stylesheet" href="../../public/css/bootstrap.css" />
    <link rel="stylesheet" href="../../public/css/css.css" />
    <link rel="stylesheet" href="../../public/css/footer.css" />
</head>

<body>

    <section class="container">
        <div class="row">

            <div class="col text-center">
                <h2 class="login-text">Login - Alunos</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <form action="../models/login_aluno.php" method="POST">
                    <div class="row list-box p-3">

                        <div class="form-group mb-3 text-center">
                            <label for="nome"><strong>Usuário</strong></label>
                            <input type="text" class="form-control" id="nome_cadastro" name="nome_cadastro" />
                        </div>

                        <div class="form-group mb-3 text-center">
                            <label for="Senha"><strong>Senha</strong></label>
                            <input type="password" class="form-control" id="senha_cadastro" name="senha_cadastro" />
                        </div>


                        <div class="col text-center mt-3">
                            <button class="btn btn-primary mr-2" type="submit">Entrar</button>
                            <a href="login.php" class="btn btn-primary">Voltar</a>
                        </div>
                        
                    </div>

                </form>
            </div>
        </div>

    </section>


    <?php
    include_once("../partials/footer.php");
    ?>
</body>

</html>