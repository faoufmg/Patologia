<!DOCTYPE html>
<html class="bd-login" lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Solicitação de Cadastro</title>
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
                <h2 class="login-text">Solicitação de Cadastro</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-8">
                <form action="../models/solicitar_cadastro.php" method="POST">
                    <div class="row list-box">

                        <div class="col-md-12 text-center">
                            <label for="usuario"><strong>Usuário</strong></label>
                            <input type="text" class="form-control" placeholder="Digite o usuário" id="usuario" name="usuario" required />
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="email"><strong>E-mail</strong></label>
                            <input type="email" class="form-control" placeholder="Digite seu e-mail" id="email" name="email" required />
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="cpf"><strong>CPF</strong></label>
                            <input type="text" class="form-control" maxlength="11" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite seu CPF (apenas números)" id="cpf" name="cpf" required />
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="telefone"><strong>Telefone</strong></label>
                            <input type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite um telefone para contato (apenas números)" id="telefone" name="telefone" required />
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="endereco"><strong>Endereço</strong></label>
                            <input type="text" class="form-control" placeholder="Digite seu endereço" id="endereco" name="endereco" required />
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="funcao"><strong>Função</strong></label>
                            <select class="form-control" id="funcao" name="funcao" required>
                                <option value="" disabled selected>Selecione sua função</option>
                                <option value="Aluno">Aluno</option>
                                <option value="Aluno Pós">Aluno Pós-Graduação</option>
                                <option value="Dentista">Dentista</option>
                                <option value="Funcionário">Funcionário</option>
                                <option value="Professor">Professor</option>
                            </select>
                        </div>

                        <div class="col text-center mt-3">
                            <button class="btn btn-primary mr-2" type="submit">Solicitar</button>
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