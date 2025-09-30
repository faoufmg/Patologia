<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

try {
    $query =
            "SELECT
                *
            FROM
                SolicitacaoExame AS SE
            JOIN
                Paciente AS P ON SE.CodigoSolicitacao = P.CodigoSolicitacao
            LEFT JOIN
                Laboratorio AS L ON L.Paciente_id = P.Paciente_id
            LEFT JOIN
                Retirada AS R ON R.Laboratorio_id = L.Laboratorio_id
            WHERE
                SE.Ativo = 1 AND ExameNum NOT IN ('1','2','3','4','5','6','7','8','9','10')";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
            window.location.href = '../index/index_funcionario.php';
        </script>";
    exit();
}
?>

<div class="view">
    <section class="listar">

        <figure class="text-center">
            <h1>Exames</h1>
        </figure>

        <table id="visualizar" class="table table-striped table-bordered" style="width:100%; text-align:center;">
            <thead class="table-dark">
                <tr>
                    <th>Exame Nº</th>
                    <th>Data Solicitacao</th>
                    <th>Nome Paciente</th>
                    <th>Status</th>
                    <th>Visualizar Dados</th>
                    <th>Laudo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resultados) > 0): ?>
                    <?php foreach ($resultados as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ExameNum']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['DataSolicitacao'])); ?></td>
                            <td><?php echo htmlspecialchars($row['NomePaciente']); ?></td>
                            <td><strong><?php echo htmlspecialchars(strtoupper($row['StatusSolicitacao'])); ?></strong></td>
                            <td>
                                <form action="visualizar_completo.php" method="post" target="_blank">
                                    <input type="hidden" name="Paciente_id" value="<?php echo htmlspecialchars($row['Paciente_id']); ?>">
                                    <button class="btn btn-primary" type="submit">Visualizar</button>
                                </form>
                            </td>
                            <td>
                                <?php if ($row['StatusSolicitacao'] === 'Liberado'): ?>
                                    <form action="../../models/laudos/visualizar_laudo.php" method="post" target="_blank">
                                        <input type="hidden" name="Paciente_id" value="<?php echo htmlspecialchars($row['Paciente_id']); ?>">
                                        <button class="btn btn-success" style="background-color: green;" type="submit">Baixar</button>
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
            <a class="btn btn-secondary" href="../index/index_alunopos.php">Voltar</a>
        </div>

    </section>
</div>

<!-- <script>
    function deleteExam(id) {
    if (confirm('Tem certeza que deseja deletar a solicitação de exame?')) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../models/deletar_exame.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("id=" + id);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Solicitação deletado com sucesso!');
                location.reload(); // Recarrega a página para atualizar a tabela
            }
        };
    }
}
</script> -->

<?php
include_once('../../partials/footer.php');
?>