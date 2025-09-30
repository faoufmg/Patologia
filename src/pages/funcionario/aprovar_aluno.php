<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

try {
    $query =
            "SELECT
                *
            FROM
                AcessoAluno
            WHERE
                Ativo = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
            window.lotacion.href = '../index/index_funcionario.php';
        </script>";
    exit();
}
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Acesso Para Alunos</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/aprovar_aluno.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-12 text-center">
                            <label for="nome_aluno"><strong>Nome do Aluno</strong></label>
                            <input type="text" placeholder="Digite o nome do aluno" name="nome_aluno" class="form-control" id="nome_aluno" required>
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                        <a class="btn btn-primary" href="../index/index_funcionario.php">Voltar</a>
                    </div>

                </form>

                <table id="aprovar_aluno" class="table table-striped table-bordered" style="width:100%; text-align:center;">
                    <thead class="table-dark">
                        <tr>
                            <th>Usuário</th>
                            <th>Senha</th>
                            <th>Status</th>
                            <th>Horario Liberação</th>
                            <th>Horario Revogação</th>
                            <th>Ações</th>
                            <th>Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($resultados) > 0): ?>
                            <?php foreach ($resultados as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['NomeAluno']); ?></td>
                                    <td><?php echo htmlspecialchars($row['SenhaView']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td>
                                        <?php
                                            if($row['status'] === 'revogado'){
                                                echo "Aluno não possui acesso.";
                                            } else{
                                                echo date('d/m/Y H:i:s', strtotime($row['Liberado']));
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            if($row['Revogado'] === NULL || $row['status'] === 'aprovada'){
                                                echo "Aluno possui acesso.";
                                            } else{
                                                echo date('d/m/Y H:i:s', strtotime($row['Revogado']));
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if($row['status'] === 'revogado'): ?>
                                            <button class="btn btn-primary" onclick='reAddAccess(<?php echo $row['AcessoAluno_id']; ?>)'>Liberar Acesso</button>
                                        <?php endif; ?>
                                        <?php if($row['status'] === 'aprovada'): ?>
                                            <button class="btn btn-primary" onclick='revokeAccess(<?php echo $row['AcessoAluno_id']; ?>)'>Revogar Acesso</button>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" onclick='deleteUser(<?php echo $row['AcessoAluno_id']; ?>)'>Excluir</button>
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

            </div>
        </div>
    </div>
</section>

<script src="../../../public/js/acessoAluno.js"></script>

<?php
include_once('../../partials/footer.php');
?>