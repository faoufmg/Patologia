<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

try {
    $query =
        "SELECT
                *
            FROM
                SolicitacaoCadastro
            WHERE
                status = 'pendente'";
    $stmt = $pdo->prepare($query);
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
            <h1>Aprovar</h1>
        </figure>

        <table id="visualizar" class="table table-striped table-bordered" style="width:100%; text-align:center;">
            <thead class="table-dark">
                <tr>
                    <th>Usuário</th>
                    <th>E-mail</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Função</th>
                    <th>Aprovar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resultados) > 0): ?>
                    <?php foreach ($resultados as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['CPF']); ?></td>
                            <td><?php echo htmlspecialchars($row['Telefone']); ?></td>
                            <td><?php echo htmlspecialchars($row['Endereco']); ?></td>
                            <td><?php echo htmlspecialchars($row['Funcao']); ?></td>
                            <td>
                                <form action="aprovacao.php" method="post">
                                    <input type="hidden" name="SolicitacaoCadastro_id" value="<?php echo htmlspecialchars($row['SolicitacaoCadastro_id']); ?>">
                                    <button class="btn btn-primary" type="submit">Aprovar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Nenhum resultado encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="col text-center mt-3">
            <a class="btn btn-secondary" href="../index/index_funcionario.php">Voltar</a>
        </div>

    </section>
</div>

<?php
include_once('../../partials/footer.php');
?>