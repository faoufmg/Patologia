<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

$solicitacao_id = $_POST['SolicitacaoCadastro_id'];

try {
    $query =
        "SELECT
                *
            FROM
                SolicitacaoCadastro
            WHERE
                SolicitacaoCadastro_id = :SolicitacaoCadastro_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':SolicitacaoCadastro_id', $solicitacao_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch();
} catch (Exception $e) {
    echo
    "<script>
            alert('Erro ao acessar o banco de dados.');
        </script>";
}
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Aprovação</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/aprovar.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-1 text-center">
                            <label for="solicitacao_id"><strong>ID</strong></label>
                            <input type="text" name="solicitacao_id" value="<?php echo $solicitacao_id ?>" class="form-control text-center" id="solicitacao_id" readonly required>
                        </div>

                        <div class="col-md-5 text-center">
                            <label for="usuario"><strong>Usuário</strong></label>
                            <input type="text" name="usuario" value="<?php echo $resultado['Usuario'] ?>" class="form-control text-center" id="usuario" readonly required>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="cpf"><strong>CPF</strong></label>
                            <input type="text" name="cpf" value="<?php echo $resultado['CPF'] ?>" class="form-control text-center" id="cpf" readonly required>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="telefone"><strong>Telefone</strong></label>
                            <input type="text" name="telefone" value="<?php echo $resultado['Telefone'] ?>" class="form-control text-center" id="telefone" readonly required>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="endereco"><strong>Endereço</strong></label>
                            <input type="text" name="endereco" value="<?php echo $resultado['Endereco'] ?>" class="form-control text-center" id="endereco" readonly required>
                        </div>

                        <div class="col-md-12 text-center" id="status_div">
                            <label for="status"><strong>Status</strong></label>
                            <select title="Selecione o status" name="status" class="form-select" id="status" required>
                                <option disabled selected>Selecione o status</option>
                                <option value="aprovada">Aprovado</option>
                                <option value="rejeitada">Rejeitado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="cargo_div" style="display: none;">
                            <label for="cargo"><strong>Cargo</strong></label>
                            <select title="Selecione o cargo do usuário" name="cargo" class="form-select" id="cargo" required>
                                <option disabled selected>Selecione o cargo do usuário</option>
                                <option value="alunopos">Aluno Pós</option>
                                <option value="dentista">Dentista</option>
                                <option value="funcionário">Funcionário</option>
                                <option value="professor">Professor</option>
                            </select>
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

<style>
    /* Para animação de fade-in e fade-out */
    #cargo_div {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const status = document.getElementById("status");
        const statusDiv = document.getElementById("status_div");
        const cargoDiv = document.getElementById("cargo_div");

        function showDiv(div) {
            div.style.display = "block";
            setTimeout(() => div.style.opacity = "1", 10);
        }

        function hideDiv(div) {
            div.style.opacity = "0";
            setTimeout(() => div.style.display = "none", 10);
        }

        status.addEventListener("change", function(){
            let value = this.value;

            if(value === 'aprovada'){
                showDiv(cargoDiv);

                statusDiv.classList.remove('col-md-12');
                statusDiv.classList.add('col-md-6');
            } else {
                hideDiv(cargoDiv);

                statusDiv.classList.remove('col-md-6');
                statusDiv.classList.add('col-md-12');
            }
        })
    })
</script>

<?php
include_once('../../partials/footer.php');
?>