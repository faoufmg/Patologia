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
                SolicitacaoCadastro_id NOT IN (5, 18, 19, 20, 21, 51, 52)";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $cargo = $_SESSION['cargo'];
    // print($cargo);
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
            <h1>Usuários</h1>
        </figure>

        <table id="visualizar" class="table table-striped table-bordered" style="width:100%; text-align:center;">
            <thead class="table-dark">
                <tr>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Cargo</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resultados) > 0): ?>
                    <?php foreach ($resultados as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['Cargo']); ?></td>
                            <td><?php echo htmlspecialchars($row['CPF']); ?></td>
                            <td><?php echo htmlspecialchars($row['Telefone']); ?></td>
                            <td>
                                <?php if($row['status'] === 'aprovada'): ?>
                                    <button onclick="deleteUser(<?php echo $row['SolicitacaoCadastro_id']; ?>)" class="btn btn-primary" type="submit">Revogar Acesso</button>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>Não disponível</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Nenhum resultado encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="col text-center mt-3">
            <?php if($cargo === 'funcionário'): ?>
                <a href='../index/index_funcionario.php' class="btn btn-primary">Voltar</a>
            <?php elseif($cargo === 'professor'): ?>
                <a href='../index/index_professor.php' class="btn btn-primary">Voltar</a>]
            <?php elseif($cargo === 'adm'): ?>
                <a href='../index/index.php' class="btn btn-primary">Voltar</a>]
            <?php endif ?>
        </div>

    </section>
</div>

<script>
    function deleteUser(id) {
        if (confirm('Tem certeza que deseja revogar o acesso desse usuário?')) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../../models/remover_user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("id=" + id);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert('Usuário removido com sucesso!');
                    location.reload(); // Recarrega a página para atualizar a tabela
                }
            };
        }
    }
</script>

<?php
include_once('../../partials/footer.php');
?>