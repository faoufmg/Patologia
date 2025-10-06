<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

$paciente_id = isset($_POST['Paciente_id']) ? $_POST['Paciente_id'] : $_GET['Paciente_id'];

try {

    // Dados do paciente
    $query =
        "SELECT
            *
        FROM
            Paciente AS P
        WHERE
            Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();

    $dados_paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    // print_r($dados_paciente);

    $etilista = '';
    $etilista_tempo_parou = '';
    if (strpos($dados_paciente['Etilista'], 'Tempo que parou: ') !== false) {
        $start = strpos($dados_paciente['Etilista'], 'Tempo que parou: ') + strlen('Tempo que parou: ');
        $end = strpos($dados_paciente['Etilista'], ',', $start);
        $etilista_tempo_parou = $end !== false ? substr($dados_paciente['Etilista'], $start, $end - $start) : substr($dados_paciente['Etilista'], $start);
    }

    $etilista_tempo_parou === '' && $dados_paciente['Etilista'] !== 'Não' ? $etilista = 'Sim, ' . strtolower($dados_paciente['Etilista']) : '';
    $etilista_tempo_parou !== '' && $dados_paciente['Etilista'] !== 'Não' ? $etilista = 'Ex-etilista, ' . strtolower($dados_paciente['Etilista']) : '';
    $dados_paciente['Etilista'] === 'Não' ? $etilista = 'Não' : '';
    
    $fumante = '';
    $fumante_tempo_parou = '';
    if (strpos($dados_paciente['Fumante'], 'Tempo que parou: ') !== false) {
        $start = strpos($dados_paciente['Fumante'], 'Tempo que parou: ') + strlen('Tempo que parou: ');
        $end = strpos($dados_paciente['Fumante'], ',', $start);
        $fumante_tempo_parou = $end !== false ? substr($dados_paciente['Fumante'], $start, $end - $start) : substr($dados_paciente['Fumante'], $start);
    }

    $fumante_tempo_parou === '' && $dados_paciente['Fumante'] !== 'Não' ? $fumante = 'Sim, ' . strtolower($dados_paciente['Fumante']) : '';
    $fumante_tempo_parou !== '' && $dados_paciente['Fumante'] !== 'Não' ? $fumante = 'Ex-fumante, ' . strtolower($dados_paciente['Fumante']) : '';
    $dados_paciente['Fumante'] === 'Não' ? $fumante = 'Não' : '';

    // Dados da lesão
    $query =
        "SELECT
            *
        FROM
            DadosLesao
        WHERE
            Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();

    $dados_lesao = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // print_r($dados_lesao);

    // Dados da macroscopia
    $query =
        "SELECT
            *
        FROM
            Macroscopia
        WHERE
            Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();

    $macroscopia = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dados da microscopia
    $query =
        "SELECT
            *
        FROM
            Microscopia
        WHERE
            Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();

    $microscopia = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verifica se o paciente tem imagens clínicas
    $query =
            "SELECT
                COUNT(*) AS Total
            FROM
                DadosLesao AS DL
            RIGHT JOIN
                FotoClinica AS FC
            ON
                FC.DadosLesao_id = DL.DadosLesao_id
            WHERE
                DL.Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $resultado['Total'];
    // print_r($resultado);

    // Verifica se o exame já foi liberado e o professor responsável
    $query =
        "SELECT
            *
        FROM
            Laboratorio AS L
        JOIN
            Professores AS P
        ON
            L.Professores_id = P.Professores_id
        WHERE
            Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();
    $exame_liberado = $stmt->fetch(PDO::FETCH_ASSOC);
    $status_exame = $exame_liberado['Status'];
    $exame_num = $exame_liberado['ExameNum'];
    $nome_professor = $exame_liberado['NomeProfessor'];
} catch (Exception $e) {
    echo
    "<script>
            alert(Erro ao acessar o banco de dados.);
            window.location.href = '../index/index_funcionario.php';
        </script>";
}
?>

<div class="view-infos">
    <div class="dados">

        <section id="paciente-info" class="listar" style="margin-top:30px;">
            <figure class="text-center">
                <h1>Informações do Paciente</h1>
            </figure>
    
            <table class="table table-striped table-bordered" style="width:100%; text-align:center;">
                <thead class="table-dark">
                    <tr>
                        <th>Exame Nº</th>
                        <th>Professor</th>
                        <th>Nome</th>
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
                        <th>Profissão</th>
                        <th>Procedência</th>
                        <th>Remetente</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody id="paciente-dados">
                    <?php

                        echo '<tr>
                        <td>' . $exame_num . '</td>
                        <td>' . $nome_professor . '</td>
                        <td>' . $dados_paciente['NomePaciente'] . '</td>
                        <td>' . (
                            $dados_paciente['DataNascimento'] === '0001-01-01'
                                ? 'Data de nascimento não informada'
                                : date('d/m/Y', strtotime($dados_paciente['DataNascimento']))
                        ) . '</td>
                        <td>' . $dados_paciente['Sexo'] . '</td>
                        <td>' . $dados_paciente['Idade'] . '</td>
                        <td>' . $dados_paciente['Telefone'] . '</td>
                        <td>' . $dados_paciente['Endereco'] . '</td>
                        <td>' . $dados_paciente['Bairro'] . '</td>
                        <td>' . $dados_paciente['CEP'] . '</td>
                        <td>' . $dados_paciente['CidadeEstado'] . '</td>
                        <td>' . $dados_paciente['CartaoSUS'] . '</td>
                        <td>' . $dados_paciente['CorPele'] . '</td>
                        <td>' . $fumante . '</td>
                        <td>' . $etilista . '</td>
                        <td>' . $dados_paciente['Profissao'] . '</td>
                        <td>' . $dados_paciente['ProcedenciaExame'] . '</td>
                        <td>' . $dados_paciente['SolicitantePaciente'] . '</td>
                        <td>';
                            

                                echo '<form action="../editar/editar_paciente.php" method="post" style="display:inline;">
                                        <input type="hidden" name="Paciente_id" value="' . $dados_paciente['Paciente_id'] . '">
                                        <button type="submit" class="btn btn-primary">Editar</button>
                                    </form>';
                            
                            
                        echo '</td>
                    </tr>';
                    
                    ?>
                </tbody>
            </table>
        </section>
    
        <section id="lesao-info" class="listar" style="margin-top:30px;">
            <figure class="text-center">
                <h1>Informações da Lesão</h1>
            </figure>
    
            <table class="table table-striped table-bordered" style="width:100%; text-align:center;">
                <thead class="table-dark">
                    <tr>
                        <th>Tempo</th>
                        <th>Tipo</th>
                        <th>Número</th>
                        <th>Envolvimento Ósseo</th>
                        <th>Coloração</th>
                        <th>Sintomatologia</th>
                        <th>Sintoma</th>
                        <th>Tamanho</th>
                        <th>Modo de Coleta</th>
                        <th>Manifestação</th>
                        <th>Data de Coleta</th>
                        <th>Exame de Imagem</th>
                        <th>Localização</th>
                        <th>Diagnostico Clínico</th>
                        <th>Observação</th>
                        <th>Visualizar Imagens</th>
                        <th>Visualizar Raio-X</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody id="lesao-dados">
                    <?php
                    foreach ($dados_lesao as $lesao) {
                        echo
                        '<tr>
                                <td>' . $lesao['Tempo'] . '</td>
                                <td>' . $lesao['Tipo'] . '</td>
                                <td>' . $lesao['Numero'] . '</td>
                                <td>' . $lesao['EnvolvimentoOsseo'] . '</td>
                                <td>' . $lesao['Coloracao'] . '</td>
                                <td>' . $lesao['Sintomatologia'] . '</td>
                                <td>' . $lesao['Sintoma'] . '</td>
                                <td>' . $lesao['Tamanho'] . '</td>
                                <td>' . $lesao['ModoColeta'] . '</td>
                                <td>' . $lesao['Manifestacao'] . '</td>
                                <td>' . date('d/m/Y', strtotime($lesao['DataColeta'])) . '</td>
                                <td>' . $lesao['ExameImagem'] . '</td>
                                <td>' . $lesao['Localizacao'] . '</td>
                                <td>' . $lesao['DiagnosticoClinico'] . '</td>
                                <td>' . $lesao['ObservacaoLesao'] . '</td>
                                <td>';
    
                                    if ($total > 0) {
                                        echo '<form action="../visualizar/visualizar_clinica.php" method="post" target="_blank" style="display:inline;">
                                                <input type="hidden" name="DadosLesao_id" value="' . $lesao['DadosLesao_id'] . '">
                                                <button type="submit" class="btn btn-primary">Visualizar</button>
                                            </form>';
                                    } else {
                                        echo "Nenhuma imagem enviada";
                                    }
    
                                echo '</td>
                                <td>';
    
                                    if ($lesao['EnvolvimentoOsseo'] === 'Lesão intra-óssea') {
                                        echo '<form action="../visualizar/visualizar_imagem.php" method="post" target="_blank" style="display:inline;">
                                                <input type="hidden" name="DadosLesao_id" value="' . $lesao['DadosLesao_id'] . '">
                                                <button type="submit" class="btn btn-primary">Visualizar</button>
                                            </form>';
                                    } else {
                                        echo "Nenhuma imagem enviada";
                                    }
    
                                echo '</td>
                                <td>';
                                    
                                        echo '<form action="../editar/editar_lesao.php" method="post" style="display:inline;">
                                                <input type="hidden" name="DadosLesao_id" value="' . $lesao['DadosLesao_id'] . '">
                                                <button type="submit" class="btn btn-primary">Editar</button>
                                            </form>';
                                    
                                echo '</td>
                            </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </section>
    
        <section id="lesao-info" class="listar" style="margin-top:30px;">
            <figure class="text-center">
                <h1>Macroscopia</h1>
            </figure>
    
            <table class="table table-striped table-bordered" style="width:100%; text-align:center;">
                <thead class="table-dark">
                    <tr>
                        <th>Fragmentos</th>
                        <th>Tipo</th>
                        <th>Formato</th>
                        <th>Superfície</th>
                        <th>Coloração</th>
                        <th>Consistência</th>
                        <th>Fragmentos Inclusão</th>
                        <th>Fragmentos Descalcificação</th>
                        <th>Tamanho</th>
                        <th>Data</th>
                        <th>Responsáveis</th>
                        <th>Observação</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody id="lesao-dados">
                    <?php
                    foreach ($macroscopia as $macro) {
                        if($macro['Observacao'] === "Revisão de Lâmina") {
                            echo
                            '<tr>
                                <td colspan="13">' . $macro['Observacao'] . '</td>
                            </tr>';
                        } else {
                            echo
                            '<tr>
                                <td>' . $macro['Fragmentos'] . '</td>
                                <td>' . $macro['TipoFragmento'] . '</td>
                                <td>' . $macro['Formato'] . '</td>
                                <td>' . $macro['Superficie'] . '</td>
                                <td>' . $macro['ColoracaoMacro'] . '</td>
                                <td>' . $macro['Consistencia'] . '</td>
                                <td>' . $macro['FragInclusao'] . '</td>
                                <td>' . $macro['FragDescalcificacao'] . '</td>
                                <td>' . $macro['TamanhoMacro'] . '</td>
                                <td>' . ( 
                                    $macro['Data'] === '0001-01-01' ?
                                    'Data não informada' :
                                    date('d/m/Y', strtotime($macro['Data'])) 
                                ) . '</td>
                                <td>' . $macro['Responsaveis'] . '</td>
                                <td>' . $macro['Observacao'] . '</td>
                                <td>';
                                    
                                        echo '<form action="../editar/editar_macro.php" method="post" style="display:inline;">
                                                <input type="hidden" name="Macroscopia_id" value="' . $macro['Macroscopia_id'] . '">
                                                <button type="submit" class="btn btn-primary">Editar</button>
                                            </form>';
                                    
                                    
                                echo '</td>
                            </tr>';
                        }

                    }
                    ?>
                </tbody>
            </table>
        </section>
    
        <section id="lesao-info" class="listar" style="margin-top:30px;">
            <figure class="text-center">
                <h1>Microscopia</h1>
            </figure>
    
            <table class="table table-striped table-bordered" style="width:100%; text-align:center;">
                <thead class="table-dark">
                    <tr>
                        <th>Microscopia</th>
                        <th>Diagnóstico</th>
                        <th>Nota</th>
                        <th>Patologista</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody id="lesao-dados">
                    <?php
                    foreach ($microscopia as $micro) {

                        $diagnosticos_com_link = [
                            'Sialodenite crônica discreta e inespecífica',
                            'Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjögren)'
                        ];

                        $celula_diagnostico = '';

                        if (in_array($micro['Diagnostico'], $diagnosticos_com_link)) {
                            $celula_diagnostico = '<a style="color: black;" target="_blank" href="../visualizar/visualizar_sjogren.php?id=' . $micro['Microscopia_id'] . '">' . $micro['Diagnostico'] . '</a>';
                        } else {
                            $celula_diagnostico = $micro['Diagnostico'];
                        }

                        echo
                        '<tr>
                            <td>' . $micro['Microscopia'] . '</td>
                            <td>' . $celula_diagnostico . '</td>
                            <td>' . $micro['Nota'] . '</td>
                            <td>' . $micro['Patologista'] . '</td>
                            <td>
                                <form action="../editar/editar_micro.php" method="post" style="display:inline;">
                                    <input type="hidden" name="Microscopia_id" value="' . $micro['Microscopia_id'] . '">
                                    <button type="submit" class="btn btn-primary">Editar</button>
                                </form>
                            </td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>
    
            <div class="col text-center mt-3">
                <a class="btn btn-secondary" href="visualizar_exames.php">Voltar</a>
            </div>
    
        </section>
    
    </div>
</div>

<?php
include_once('../../partials/footer.php');
?>