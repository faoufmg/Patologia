<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

$usuario = $_SESSION['nome_cadastro'];

try {

    $query =
        "SELECT
                *
            FROM
                SolicitacaoExame AS SE
            JOIN
                Paciente AS P
            ON SE.CodigoSolicitacao = P.CodigoSolicitacao
            WHERE
                SE.Solicitante = :Solicitante";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Solicitante', $usuario, PDO::PARAM_STR);
    $stmt->execute();

    $resultados = $stmt->fetchAll();

} catch (Exception $e) {
    echo
    "<script>
            alert('Erro ao acessar o banco de dados.');
        </script>";
}
?>

<div class="view">
    <section class="listar">

        <figure class="text-center">
            <h1>Dados</h1>
        </figure>

        <table id="visualizar" class="table table-striped table-bordered" style="width:100%; text-align:center;">
            <thead class="table-dark">
                <tr>
                    <th>Data Solicitacao</th>
                    <th>Nome Paciente</th>
                    <th>Status</th>
                    <!-- <th>Código de Solicitação</th> -->
                    <th>Visualizar Dados</th>
                    <th>Laudo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resultados) > 0): ?>
                    <?php foreach ($resultados as $row): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($row['DataSolicitacao'])); ?></td>
                            <td><?php echo htmlspecialchars($row['NomePaciente']); ?></td>
                            <td><?php echo htmlspecialchars($row['StatusSolicitacao']); ?></td>
                            <!-- <td><?php echo htmlspecialchars($row['CodigoSolicitacao']); ?></td> -->
                            <td>
                                <form action="visualizar_completo.php" method="post" target="_blank">
                                    <input type="hidden" name="Paciente_id" value="<?php echo htmlspecialchars($row['Paciente_id']); ?>">
                                    <button class="btn btn-primary" type="submit">Visualizar</button>
                                </form>
                            </td>
                            <td>
                                <?php if ($row['StatusSolicitacao'] === 'Liberado'): ?>
                                    <form action="../../models/visualizar_laudo.php" method="post" target="_blank">
                                        <input type="hidden" name="Paciente_id" value="<?php echo htmlspecialchars($row['Paciente_id']); ?>">
                                        <button class="btn btn-success" type="submit">Visualizar</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>Não disponível</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhum resultado encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="col text-center mt-3">
            <a class="btn btn-secondary" href="../index/index_dentista.php">Voltar</a>
        </div>

    </section>
</div>

<?php
include_once('../../partials/footer.php');
?>