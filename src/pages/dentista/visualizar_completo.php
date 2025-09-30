<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

$paciente_id = $_POST['Paciente_id'];

try {
    $query =
        "SELECT
                *
            FROM
                Paciente
            WHERE
                Paciente_id = :Paciente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();

    $dados_paciente = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // print_r($dados_paciente);

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
} catch (Exception $e) {
    echo
    "<script>
            alert(Erro ao acessar o banco de dados.);
            window.location.href = '../index/index_dentista.php';
        </script>";
}
?>

<div class="view">

    <section id="paciente-info" class="listar" style="margin-top:30px;">
        <figure class="text-center">
            <h1>Informações do Paciente</h1>
        </figure>

        <table class="table table-striped table-bordered" style="width:100%; text-align:center;">
            <thead class="table-dark">
                <tr>
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
                </tr>
            </thead>
            <tbody id="paciente-dados">
                <?php
                foreach ($dados_paciente as $paciente) {
                    echo
                        '<tr>
                            <td>' . $paciente['NomePaciente'] . '</td>
                            <td>' . date('d/m/Y', strtotime($paciente['DataNascimento'])) . '</td>
                            <td>' . $paciente['Sexo'] . '</td>
                            <td>' . $paciente['Idade'] . '</td>
                            <td>' . $paciente['Telefone'] . '</td>
                            <td>' . $paciente['Endereco'] . '</td>
                            <td>' . $paciente['Bairro'] . '</td>
                            <td>' . $paciente['CEP'] . '</td>
                            <td>' . $paciente['CidadeEstado'] . '</td>
                            <td>' . $paciente['CartaoSUS'] . '</td>
                            <td>' . $paciente['CorPele'] . '</td>
                            <td>' . $paciente['Fumante'] . '</td>
                            <td>' . $paciente['Etilista'] . '</td>
                            <td>' . $paciente['Profissao'] . '</td>
                        </tr>';
                }
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
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- <div class="col text-center mt-3">
        <a class="btn btn-secondary" href="visualizar_exames.php">Voltar</a>
    </div> -->

</div>

<?php
include_once('../../partials/footer.php');
?>