<?php
include_once('../../../config/db.php');
include_once('../../partials/header.php');

try {
    $query =
        "SELECT 
            *
        FROM
            Paciente AS P
        WHERE NOT EXISTS (
            SELECT 1 FROM Laboratorio AS L WHERE L.Paciente_id = P.Paciente_id
        )";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo
    "<script>
            alert('Erro: " . $e->getMessage() . "');
        </script>";
}
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Registro do Exame</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/registro.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-12 text-center">
                            <label for="paciente"><strong>Paciente</strong></label>
                            <select title="Selecione o paciente" name="paciente" class="form-select" id="paciente" required>
                                <option disabled selected>Selecione o paciente</option>
                                <?php
                                foreach ($resultados as $row) {
                                    echo "<option value='" . htmlspecialchars($row['Paciente_id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['NomePaciente'], ENT_QUOTES, 'UTF-8') . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="numero_exame"><strong>Número do Exame</strong></label>
                            <input type="text" placeholder="Digite o número do exame" name="numero_exame" class="form-control" id="numero_exame" required>
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="data_entrada"><strong>Data de Entrada do Material</strong></label>
                            <input type="date" name="data_entrada" class="form-control" id="data_entrada" required>
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                        <a class="btn btn-primary" href="../index/index_funcionario.php">Voltar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>


<?php
include_once('../../partials/footer.php');
?>