<?php
include_once('../../config/db.php');
include_once('../partials/header.php');

try {
    $query =
            "SELECT
                *
            FROM
                SolicitacaoCadastro
            WHERE
                Cargo = 'professor'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
        </script>";
    exit();
}
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Assinatura Professores</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../models/add_assinatura.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-12 text-center">
                            <label for="SolicitacaoCadastro"><strong>Usuário</strong></label>
                            <select name="SolicitacaoCadastro" class="form-control" id="SolicitacaoCadastro" required>
                                <option selected disabled>Selecione um professor</option>
                                <?php
                                // Supondo que $resultado seja um array de arrays associativos
                                foreach ($resultado as $valor) {
                                    echo "<option value='{$valor['SolicitacaoCadastro_id']}'>{$valor['Usuario']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-3 text-center">
                            <label for="cro"><strong>CRO</strong></label>
                            <input type="number" class="form-control" id="cro" name="cro" />
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="assinatura"><strong>Assinatura</strong></label>
                            <input type="file" name="assinatura" class="form-control" id="assinatura" required>
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<?php
include_once('../partials/footer.php');
?>