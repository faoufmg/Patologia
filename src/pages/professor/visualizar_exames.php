<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

session_start();
$cargo = $_SESSION['cargo'];
$nome = $_SESSION['nome_cadastro'];
try {
    if($cargo === 'professor') {

        $query =
                "SELECT
                    P.Professores_id
                FROM
                    Professores AS P
                JOIN
                    SolicitacaoCadastro AS SC
                ON
                    SC.SolicitacaoCadastro_id = P.SolicitacaoCadastro_id
                WHERE
                    SC.Usuario = :Usuario";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Usuario', $nome, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch();
        $professor_id = $resultado['Professores_id'];

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
                    (SE.Ativo = 1) AND (ExameNum NOT IN ('1','2','3','4','5','6','7','8','9','10')) AND (L.Professores_id = :Professores_id OR L.Professores_id = 8)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Professores_id', $professor_id, PDO::PARAM_INT);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    elseif($cargo === 'professor_dev') {
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
                    SE.Ativo = 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <th>Nome Paciente</th>
                    <th>Status</th>
                    <th>Visualizar Dados</th>
                    <th>Liberar Laudo</th>
                    <th>Visualizar Laudo</th>
                    <th>Atualizar Laudo</th>
                    <!-- <th>Status</th> -->
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
                                <?php if (ctype_digit($row['ExameNum'])): ?>
                                    <!-- ExameNum contém apenas números: enviar para laudo_pato.php -->
                                    <form action="../../models/laudos/laudo_pato.php" method="post">
                                        <input type="hidden" name="Paciente_id" value="<?php echo htmlspecialchars($row['Paciente_id']); ?>">
                                        <button class="btn btn-success" type="submit">Liberar</button>
                                    </form>
                                <?php else: ?>
                                    <!-- ExameNum contém letras ou outros caracteres: enviar para laudo_cito.php -->
                                    <form action="../../models/laudos/laudo_cito.php" method="post">
                                        <input type="hidden" name="Paciente_id" value="<?php echo htmlspecialchars($row['Paciente_id']); ?>">
                                        <button class="btn btn-success" type="submit">Liberar</button>
                                    </form>
                                <?php endif; ?>
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
                                <?php if ($row['DataRetirada'] !== NULL): ?>
                                    Lâmina retirada pelo paciente no dia <?php echo date('d/m/Y', strtotime($row['DataRetirada'])); ?>
                                <?php else: ?>
                                    Lâmina não retirada pelo paciente
                                <?php endif; ?>
                            </td> -->
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
            <a class="btn btn-secondary" href="../index/index_professor.php">Voltar</a>
        </div>

    </section>
</div>

<?php
include_once('../../partials/footer.php');
?>