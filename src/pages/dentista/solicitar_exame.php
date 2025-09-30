<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

$usuario = $_SESSION['nome_cadastro'];
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Solicitação de Exame</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/solicitar_exame.php" method="POST" enctype="multipart/form-data">
                    <div class="row">


                        <div class="col-md-12 text-center">
                            <label for="nome_usuario"><strong>Usuário</strong></label>
                            <input type="text" name="nome_usuario" value="<?php echo $usuario ?>" class="form-control" id="nome_usuario" readonly required oninput="CodigoExame()">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="data_solicitacao"><strong>Data de Solicitação</strong></label>
                            <input type="date" name="data_solicitacao" min="2000-01-01" max="2100-12-31" class="form-control" id="data_solicitacao" required oninput="CodigoExame()">
                        </div>

                        <div class="col-md-12 text-center" style="display: none;">
                            <label for="codigo_solicitacao"><strong>Código de Solicitação</strong></label>
                            <input type="text" name="codigo_solicitacao" class="form-control" id="codigo_solicitacao" readonly required>
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                        <a class="btn btn-secondary" href="../index/index_dentista.php">Voltar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<?php
include_once('../../partials/footer.php');
?>