<?php
include_once('../../config/db.php');
include_once('../partials/header.php');

$cargo = $_SESSION['cargo'];

try {

    if($cargo != 'funcionário_dev' || $cargo != 'professor_dev' || $cargo != 'alunopos_dev') {
        $query =
            "SELECT
                P.*, DL.*, Ma.*, Mi.*, L.*
            FROM
                Paciente AS P
            LEFT JOIN
                DadosLesao AS DL ON P.Paciente_id = DL.Paciente_id
            LEFT JOIN
                Macroscopia AS Ma ON P.Paciente_id = Ma.Paciente_id
            LEFT JOIN
                Microscopia AS Mi ON P.Paciente_id = Mi.Paciente_id
            LEFT JOIN
                Laboratorio AS L ON P.Paciente_id = L.Paciente_id
            JOIN
                SolicitacaoExame AS SE ON SE.CodigoSolicitacao = P.CodigoSolicitacao
            WHERE
                (SE.Ativo = 1) AND
                P.NomePaciente NOT IN (SELECT NomePaciente FROM Paciente WHERE NomePaciente LIKE 'teste%') AND
                L.ExameNum != '2'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query =
            "SELECT
                P.*, DL.*, Ma.*, Mi.*, L.*
            FROM
                Paciente AS P
            LEFT JOIN
                DadosLesao AS DL ON P.Paciente_id = DL.Paciente_id
            LEFT JOIN
                Macroscopia AS Ma ON P.Paciente_id = Ma.Paciente_id
            LEFT JOIN
                Microscopia AS Mi ON P.Paciente_id = Mi.Paciente_id
            LEFT JOIN
                Laboratorio AS L ON P.Paciente_id = L.Paciente_id
            JOIN
                SolicitacaoExame AS SE ON SE.CodigoSolicitacao = P.CodigoSolicitacao
            WHERE
                SE.Ativo = 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (Exception $e) {
    echo
    "<script>
            alert('Erro ao acessar o banco de dados: " . $e->getMessage() . "');
        </script>";
    exit();
}
?>

<section class="conteudo">
    <div class="container-fluid">
        <div class="col">

            <table id="pesquisa_tabela" class="table table-striped table-bordered" style="width:100%; text-align:center;">
                <thead class="table-dark">
                    <tr>
                        <th>Número do Exame</th>
                        <th>Nome Paciente</th>
                        <th>Data de Nascimento</th>
                        <th>Sexo</th>
                        <th>Idade</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Bairro</th>
                        <th>CEP</th>
                        <th>Cidade/Estado</th>
                        <th>Cartão SUS</th>
                        <th>Cor da Pele</th>
                        <th>Fumante</th>
                        <th>Etilista</th>
                        <th>Profissao</th>
                        <th>Procedência</th>
                        <th>Remetente</th>
                        <th>Tempo de Lesão</th>
                        <th>Tipo de Lesão</th>
                        <th>Número de Lesões</th>
                        <th>Envolvimento Ósseo</th>
                        <th>Coloração</th>
                        <th>Sintomatologia</th>
                        <th>Sintomas</th>
                        <th>Tamanho da Lesão</th>
                        <th>Modo de Coleta</th>
                        <th>Manifestação</th>
                        <th>Data da Coleta</th>
                        <th>Localização</th>
                        <th>Diagnostico Clínico</th>
                        <th>Total de Fragmentos</th>
                        <th>Tipo de Fragmentos</th>
                        <th>Formato</th>
                        <th>Superfície</th>
                        <th>Coloração</th>
                        <th>Consistência</th>
                        <th>Fragmentos p/ Inclusão</th>
                        <th>Fragmentos p/ Descalcificação</th>
                        <th>Data</th>
                        <th>Responsáveis</th>
                        <th>Microscopia</th>
                        <th>Diagnostico</th>
                        <th>Nota</th>
                        <th>Patologista</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($resultados) > 0): ?>
                        <?php foreach ($resultados as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ExameNum']); ?></td>
                                <td><?php echo htmlspecialchars($row['NomePaciente']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['DataNascimento']))); ?></td>
                                <td><?php echo htmlspecialchars($row['Sexo']); ?></td>
                                <td><?php echo htmlspecialchars($row['Idade']); ?></td>
                                <td><?php echo htmlspecialchars($row['Telefone']); ?></td>
                                <td><?php echo htmlspecialchars($row['Endereco']); ?></td>
                                <td><?php echo htmlspecialchars($row['Bairro']); ?></td>
                                <td><?php echo htmlspecialchars($row['CEP']); ?></td>
                                <td><?php echo htmlspecialchars($row['CidadeEstado']); ?></td>
                                <td><?php echo htmlspecialchars($row['CartaoSUS']); ?></td>
                                <td><?php echo htmlspecialchars($row['CorPele']); ?></td>
                                <td><?php echo htmlspecialchars($row['Fumante']); ?></td>
                                <td><?php echo htmlspecialchars($row['Etilista']); ?></td>
                                <td><?php echo htmlspecialchars($row['Profissao']); ?></td>
                                <td><?php echo htmlspecialchars($row['ProcedenciaExame']); ?></td>
                                <td><?php echo htmlspecialchars($row['SolicitantePaciente']); ?></td>

                                <td><?php echo htmlspecialchars($row['Tempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['Tipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['Numero']); ?></td>
                                <td><?php echo htmlspecialchars($row['EnvolvimentoOsseo']); ?></td>
                                <td><?php echo htmlspecialchars($row['Coloracao']); ?></td>
                                <td><?php echo htmlspecialchars($row['Sintomatologia']); ?></td>
                                <td><?php echo htmlspecialchars($row['Sintoma']); ?></td>
                                <td><?php echo htmlspecialchars($row['Tamanho']); ?></td>
                                <td><?php echo htmlspecialchars($row['ModoColeta']); ?></td>
                                <td><?php echo htmlspecialchars($row['Manifestacao']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['DataColeta']))); ?></td>
                                <td><?php echo htmlspecialchars($row['Localizacao']); ?></td>
                                <td><?php echo htmlspecialchars($row['DiagnosticoClinico']); ?></td>

                                <td><?php echo htmlspecialchars($row['Fragmentos']); ?></td>
                                <td><?php echo htmlspecialchars($row['TipoFragmento']); ?></td>
                                <td><?php echo htmlspecialchars($row['Formato']); ?></td>
                                <td><?php echo htmlspecialchars($row['Superficie']); ?></td>
                                <td><?php echo htmlspecialchars($row['ColoracaoMacro']); ?></td>
                                <td><?php echo htmlspecialchars($row['Consistencia']); ?></td>
                                <td><?php echo htmlspecialchars($row['FragInclusao']); ?></td>
                                <td><?php echo htmlspecialchars($row['FragDescalcificacao']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['Data']))); ?></td>
                                <td><?php echo htmlspecialchars($row['Responsaveis']); ?></td>

                                <td><?php echo htmlspecialchars($row['Microscopia']); ?></td>
                                <td><?php echo htmlspecialchars($row['Diagnostico']); ?></td>
                                <td><?php echo htmlspecialchars($row['Nota']); ?></td>
                                <td><?php echo htmlspecialchars($row['Patologista']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum resultado encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>

        <div class="col text-center mt-3">
            <?php if($_SESSION['Cargo'] === 'funcionário'): ?>
                <a class="btn btn-secondary" href="../pages/index/index_funcionario.php">Voltar</a>
            <?php endif; ?>
            <?php if($_SESSION['Cargo'] === 'professor'): ?>
                <a class="btn btn-secondary" href="../pages/index/index_professor.php">Voltar</a>
            <?php endif; ?>
            <?php if($_SESSION['Cargo'] === 'alunopos'): ?>
                <a class="btn btn-secondary" href="../pages/index/index_alunopos.php">Voltar</a>
            <?php endif; ?>
        </div>

</section>
</div>

<?php
include_once('../partials/footer.php');
?>