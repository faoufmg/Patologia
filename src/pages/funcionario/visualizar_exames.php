<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

session_start();
$cargo = $_SESSION['cargo'];
// echo $cargo;

try {

    if($cargo != 'funcionário_dev'){
        $query =
                "SELECT
                    SE.*,
                    P.*,
                    L.*,
                    R.DataRetirada
                FROM
                    SolicitacaoExame AS SE
                JOIN
                    Paciente AS P ON SE.CodigoSolicitacao = P.CodigoSolicitacao
                JOIN
                    Laboratorio AS L ON L.Paciente_id = P.Paciente_id
                LEFT JOIN
                    Retirada AS R ON R.Laboratorio_id = L.Laboratorio_id
                WHERE 
                    SE.Ativo = 1 AND P.NomePaciente NOT IN (SELECT NomePaciente FROM Paciente WHERE NomePaciente LIKE 'teste%')";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // echo $resultados['Paciente_id'] . "<br>";

        // $query =
        //         "SELECT COUNT(*) FROM Laudos AS L JOIN Paciente AS P ON P.Paciente_id = L.Paciente_id WHERE P.Paciente_id = :Paciente_id";
        // $stmt = $pdo->prepare($query);
        // $stmt->bindParam(":Paciente_id", $resultados['Paciente_id'], PDO::PARAM_INT);
        // $stmt->execute();
        // $exite = $stmt->fetch(PDO::FETCH_ASSOC);

        // echo $existe;
    }
    else {
        $query =
                "SELECT
                    SE.*,
                    P.*,
                    L.*,
                    R.DataRetirada
                FROM
                    SolicitacaoExame AS SE
                JOIN
                    Paciente AS P ON SE.CodigoSolicitacao = P.CodigoSolicitacao
                JOIN
                    Laboratorio AS L ON L.Paciente_id = P.Paciente_id
                LEFT JOIN
                    Retirada AS R ON R.Laboratorio_id = L.Laboratorio_id
                WHERE 
                    SE.Ativo = 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo $resultados['Paciente_id'] . "<br>";

        $query =
                "SELECT COUNT(*) FROM Laudos AS L JOIN Paciente AS P ON P.Paciente_id = L.Paciente_id WHERE P.Paciente_id = :Paciente_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":Paciente_id", $resultados['Paciente_id'], PDO::PARAM_INT);
        $stmt->execute();
        $exite = $stmt->fetch(PDO::FETCH_ASSOC);

        echo $existe;
    }

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
                    <?php if($cargo === 'funcionário_dev'): ?>
                        <th>PacienteID</th>
                    <?php endif ?>
                    <th>Nome Paciente</th>
                    <th>Status</th>
                    <th>Visualizar Dados</th>
                    <th>Laudo</th>
                    <th>Atualizar Laudo</th>
                    <!-- <th>Retirar</th> -->
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resultados) > 0): ?>
                    <?php foreach ($resultados as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ExameNum']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['DataSolicitacao'])); ?></td>
                            <?php if($cargo === 'funcionário_dev'): ?>
                                <td><?php echo htmlspecialchars($row['Paciente_id']); ?></td>
                            <?php endif ?>
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
                            <td>
                                <?php 
                                    $query =
                                            "SELECT COUNT(*) AS Total FROM Laudos WHERE Paciente_id = :Paciente_id";
                                    $stmt = $pdo->prepare($query);
                                    $stmt->bindParam(":Paciente_id", $row['Paciente_id'], PDO::PARAM_INT);
                                    $stmt->execute();
                                    $existe = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <?php if (ctype_digit($row['ExameNum']) && $existe['Total'] === 1): ?>
                                    <!-- ExameNum contém apenas números: enviar para laudo_pato_atualizado.php -->
                                    <form action="../../models/laudos/laudo_pato_atualizado.php" method="post">
                                        <input type="hidden" name="Paciente_id" value="<?php echo htmlspecialchars($row['Paciente_id']); ?>">
                                        <button class="btn btn-success" type="submit">Atualizar</button>
                                    </form>
                                <?php elseif(!(ctype_digit($row['ExameNum'])) && $existe['Total'] === 1): ?>
                                    <!-- ExameNum contém letras ou outros caracteres: enviar para laudo_cito_atualizado.php -->
                                    <form action="../../models/laudos/laudo_cito_atualizado.php" method="post">
                                        <input type="hidden" name="Paciente_id" value="<?php echo htmlspecialchars($row['Paciente_id']); ?>">
                                        <button class="btn btn-success" type="submit">Atualizar</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>Não disponível</button>
                                <?php endif; ?>
                            </td>
                            <!-- <td>
                                <?php if($row['DataRetirada'] !== NULL): ?>
                                    <button class="btn btn-secondary" disabled>Não disponível</button>
                                <?php else: ?>
                                    <button class="btn btn-primary" onclick='removeExam(<?php echo $row['Laboratorio_id']; ?>)'>Retirar</button>
                                <?php endif; ?>
                            </td> -->
                            <td>
                                <?php if($row['StatusSolicitacao'] === 'Liberado'): ?>
                                    <button class="btn btn-secondary" disabled>Não disponível</button>
                                <?php else: ?>
                                    <button class="btn btn-primary" onclick='deleteExam(<?php echo $row['SolicitacaoExame_id']; ?>)'>Excluir</button>
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
            <a class="btn btn-secondary" href="../index/index_funcionario.php">Voltar</a>
        </div>

    </section>
</div>

<script>
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

    function removeExam(id) {
        if (confirm('Tem certeza que deseja retirar o exame?')) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../../models/remover_exame.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("id=" + id);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Espera que o PHP retorne o Laboratorio_id como resposta (JSON)
                    alert('Solicitação retirada com sucesso!');
                    location.reload(); // Recarrega a página para atualizar a tabela
                }
                if (xhr.readyState === 4 && xhr.status === 409) {
                    alert('Erro!');
                    location.reload(); // Recarrega a página para atualizar a tabela
                }
            };
        }
    }
</script>

<?php
include_once('../../partials/footer.php');
?>