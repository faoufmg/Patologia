<?php
include_once('../../partials/header.php');

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

    $resultados = $stmt->fetch(PDO::FETCH_ASSOC);

    $email = $resultados['Email'];

    // print_r($resultados);
} catch (Exception $e) {
    echo
        "<script>
            alert('Erro ao conectar ao banco de dados');
        </script>";
}
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Área do Úsuario</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <div class="row">

                    <div class="col-md-6 text-center">
                        <label for="usuario"><strong>Úsuario</strong></label>
                        <input type="text" name="usuario" id="usuario" class="form-control text-center" value="<?php echo $usuario ?>" readonly>
                    </div>

                    <div class="col-md-6 text-center">
                        <label for="email"><strong>E-mail</strong></label>
                        <input type="text" name="email" id="email" class="form-control text-center" value="<?php echo $resultados['Email'] ?>" readonly>
                    </div>

                    <div class="col-md-6 text-center">
                        <label for="cpf"><strong>CPF</strong></label>
                        <input type="text" name="cpf" id="cpf" class="form-control text-center" value="<?php echo $resultados['CPF'] ?>" readonly>
                    </div>

                    <div class="col-md-6 text-center">
                        <label for="telefone"><strong>Telefone</strong></label>
                        <input type="text" name="telefone" id="telefone" class="form-control text-center" value="<?php echo $resultados['Telefone'] ?>" readonly>
                    </div>

                </div>
            </div>
        </div>

        <div class="col text-center mt-3">
            <form action="../senha/recuperar_senha_logado.php" method="POST">
                <input type="hidden" name="Email" value="<?php echo $resultados['Email']; ?>">
                <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">
                    Alterar Senha
                </button>
                <a class="btn btn-secondary" href="../index/index_funcionario.php">Voltar</a>
            </form>
        </div>

    </div>
</section>

<?php
include_once('../../partials/footer.php');
?>