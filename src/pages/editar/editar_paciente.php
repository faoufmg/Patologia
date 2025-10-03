<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

session_start();

$cargo = $_SESSION['cargo'] ?? null;

$paciente_id = $_POST['Paciente_id'] ?? null;

$query = 
        "SELECT *
        FROM Paciente AS P
        JOIN Laboratorio AS L ON L.Paciente_id = P.Paciente_id
        JOIN Professores AS Pr ON Pr.Professores_id = L.Professores_id
        WHERE P.Paciente_id = :Paciente_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
$stmt->execute();
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

// print_r($paciente);

if($cargo === 'funcionário_dev'){
    $query =
            "SELECT
                Professores_id, NomeProfessor
            FROM
                Professores";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
} else {
    $query =
            "SELECT
                Professores_id, NomeProfessor
            FROM
                Professores
            LIMIT 7";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
}

// Pega o número do exame
$query =
    "SELECT
        *
    FROM
        Laboratorio
    WHERE
        Paciente_id = :Paciente_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
$stmt->execute();
$exame_liberado = $stmt->fetch(PDO::FETCH_ASSOC);
$exame_num = $exame_liberado['ExameNum'];
$data_entrada = $exame_liberado['DataEntradaMaterial'];

function getRedirectUrl($cargo, $paciente_id) {
    $paths = [
        'professor' => 'professor',
        'professor_dev' => 'professor',
        'funcionário' => 'funcionario',
        'funcionário_dev' => 'funcionario',
        'dentista' => 'dentista',
        'alunopos' => 'aluno'
    ];
    
    if (!isset($paths[$cargo])) {
        return false;
    }
    
    return '../../pages/' . $paths[$cargo] . '/visualizar_completo.php?Paciente_id=' . $paciente_id;
}

$redirectUrl = getRedirectUrl($cargo, $paciente_id);

$fumante = $paciente['Fumante'];
$etilista = $paciente['Etilista'];

$fumante_tipo = '';
if (strpos($fumante, 'Tipo: ') !== false) {
    $start = strpos($fumante, 'Tipo: ') + strlen('Tipo: ');
    $end = strpos($fumante, ',', $start);
    $fumante_tipo = $end !== false ? substr($fumante, $start, $end - $start) : substr($fumante, $start);
}

$fumante_quantidade = '';
if (strpos($fumante, 'Quantidade: ') !== false) {
    $start = strpos($fumante, 'Quantidade: ') + strlen('Quantidade: ');
    $end = strpos($fumante, ',', $start);
    $fumante_quantidade = $end !== false ? substr($fumante, $start, $end - $start) : substr($fumante, $start);
}

$fumante_tempo = '';
if (strpos($fumante, 'Tempo: ') !== false) {
    $start = strpos($fumante, 'Tempo: ') + strlen('Tempo: ');
    $end = strpos($fumante, ',', $start);
    $fumante_tempo = $end !== false ? substr($fumante, $start, $end - $start) : substr($fumante, $start);
} elseif (strpos($fumante, 'Tempo que fumou: ') !== false) {
    $start = strpos($fumante, 'Tempo que fumou: ') + strlen('Tempo que fumou: ');
    $end = strpos($fumante, ',', $start);
    $fumante_tempo = $end !== false ? substr($fumante, $start, $end - $start) : substr($fumante, $start);
}

$fumante_tempo_parou = '';
if (strpos($fumante, 'Tempo que parou: ') !== false) {
    $start = strpos($fumante, 'Tempo que parou: ') + strlen('Tempo que parou: ');
    $end = strpos($fumante, ',', $start);
    $fumante_tempo_parou = $end !== false ? substr($fumante, $start, $end - $start) : substr($fumante, $start);
}

$etilista_tipo = '';
if (strpos($etilista, 'Tipo: ') !== false) {
    $start = strpos($etilista, 'Tipo: ') + strlen('Tipo: ');
    $end = strpos($etilista, ',', $start);
    $etilista_tipo = $end !== false ? substr($etilista, $start, $end - $start) : substr($etilista, $start);
}

$etilista_quantidade = '';
if (strpos($etilista, 'Quantidade: ') !== false) {
    $start = strpos($etilista, 'Quantidade: ') + strlen('Quantidade: ');
    $end = strpos($etilista, ',', $start);
    $etilista_quantidade = $end !== false ? substr($etilista, $start, $end - $start) : substr($etilista, $start);
}

$etilista_tempo = '';
if (strpos($etilista, 'Tempo: ') !== false) {
    $start = strpos($etilista, 'Tempo: ') + strlen('Tempo: ');
    $end = strpos($etilista, ',', $start);
    $etilista_tempo = $end !== false ? substr($etilista, $start, $end - $start) : substr($etilista, $start);
} elseif (strpos($etilista, 'Tempo que bebeu: ') !== false) {
    $start = strpos($etilista, 'Tempo que bebeu: ') + strlen('Tempo que bebeu: ');
    $end = strpos($etilista, ',', $start);
    $etilista_tempo = $end !== false ? substr($etilista, $start, $end - $start) : substr($etilista, $start);
}

$etilista_tempo_parou = '';
if (strpos($etilista, 'Tempo que parou: ') !== false) {
    $start = strpos($etilista, 'Tempo que parou: ') + strlen('Tempo que parou: ');
    $end = strpos($etilista, ',', $start);
    $etilista_tempo_parou = $end !== false ? substr($etilista, $start, $end - $start) : substr($etilista, $start);
}

?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Dados do Paciente</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/editar/editar_paciente.php" method="POST" enctype="multipart/form-data">
                    <div class="row">


                        <div class="col-md-12 text-center" style="display: none;">
                            <label for="paciente_id"><strong>ID</strong></label>
                            <input type="text" name="paciente_id" value="<?php echo $paciente_id; ?>" readonly class="form-control" id="paciente_id">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="data_entrada"><strong>Data de Entrada</strong></label>
                            <input type="date" value="<?php echo $data_entrada ?>" name="data_entrada" readonly class="form-control" id="data_entrada" >
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="exame_num"><strong>Número do Exame</strong></label>
                            <input type="text" value="<?php echo $exame_num ?>" name="exame_num" class="form-control" id="exame_num" >
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="professor"><strong>Professor</strong></label>
                            <select
                                class="selectpicker w-100"
                                data-style="btn btn-light border"
                                name="professor"
                                id="professor"
                                data-live-search="true"
                                data-size="10"
                                required>
                                <option selected disabled>Selecione o professor</option>
                                <?php
                                if (!empty($resultado)) {
                                    foreach ($resultado as $row) {
                                        $selected = ($row['NomeProfessor'] == $paciente['NomeProfessor']) ? 'selected' : '';
                                        echo '<option value="' . $row['Professores_id'] . '" ' . $selected . '>' . $row['NomeProfessor'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Nenhum professor encontrado.</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 text-center" id="paciente_div">
                            <label for="paciente"><strong>Paciente</strong></label>
                            <input type="text" value="<?php echo $paciente['NomePaciente'] ?>" placeholder="Digite o nome do paciente" name="paciente" class="form-control" id="paciente" >
                        </div>

                        <div class="col-md-4 text-center" id="procedencia_div">
                            <label for="procedencia"><strong>Procedência do Exame</strong></label>
                            <select title="Selecione a procedência do exame" name="procedencia" class="form-select" id="procedencia" >
                                <option disabled selected>Selecione a procedência do exame</option>
                                <option value="CEO - PBH" <?php echo $paciente['ProcedenciaExame'] === 'CEO - PBH' ? 'selected' : '' ?>>CEO - PBH</option>
                                <option value="FAO-UFMG" <?php echo $paciente['ProcedenciaExame'] === 'FAO-UFMG' ? 'selected' : '' ?>>FAO-UFMG</option>
                                <option value="Hospital das Clínicas" <?php echo $paciente['ProcedenciaExame'] === 'Hospital das Clínicas' ? 'selected' : '' ?>>Hospital das Clínicas</option>
                                <option value="Hospital Municipal Odilon Behrens" <?php echo $paciente['ProcedenciaExame'] === 'Hospital Municipal Odilon Behrens' ? 'selected' : '' ?>>Hospital Municipal Odilon Behrens</option>
                                <option value="Outros" <?php echo $paciente['ProcedenciaExame'] === 'Outros' ? 'selected' : '' ?>>Outros</option>
                                <option value="Particular" <?php echo $paciente['ProcedenciaExame'] === 'Particular' ? 'selected' : '' ?>>Particular</option>
                                <option value="Patologia FAO-UFMG" <?php echo $paciente['ProcedenciaExame'] === 'Patologia FAO-UFMG' ? 'selected' : '' ?>>Patologia FAO-UFMG</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="especificacao_procedencia_div" style="display: none;">
                            <label for="especificacao_procedencia"><strong>Especificação</strong></label>
                            <input type="text" name="especificacao_procedencia" value="<?php echo $paciente['EspecificacaoExame'] ?>" placeholder="Especifique a procedência do exame" class="form-control" id="especificacao_procedencia">
                        </div>

                        <div class="col-md-4 text-center" id="remetente_div">
                            <label for="remetente"><strong>Remetente</strong></label>
                            <input type="text" value="<?php echo $paciente['SolicitantePaciente'] ?>" name="remetente" placeholder="Digite o remetente do exame" class="form-control" id="profissao" >
                        </div>

                        <div class="col-md-6 text-center" id="cartao_sus_div" style="display: none;">
                            <label for="cartao_sus"><strong>Cartão Nacional do SUS (CNS)</strong></label>
                            <input type="text" value="<?php echo $paciente['CartaoSUS'] ?>" name="cartao_sus" maxlength="16" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o número do cartão do SUS do paciente" class="form-control" id="cartao_sus" >
                        </div>

                        <div class="col-md-4 text-center" id="sexo_div">
                            <label for="sexo"><strong>Sexo</strong></label>
                            <select title="Selecione o sexo do paciente" name="sexo" class="form-select" id="sexo" >
                                <option disabled selected>Selecionar</option>
                                <option value="Masculino" <?php echo $paciente['Sexo'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                <option value="Feminino" <?php echo $paciente['Sexo'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                            </select>
                        </div>

                        <div class="col-md-4 text-center" id="data_nascimento_div">
                            <label for="data_nascimento"><strong>Data de Nascimento</strong></label>
                            <input type="date" value="<?php echo $paciente['DataNascimento'] ?>" min="1900-01-01" max="<?= date('Y-m-d') ?>" name="data_nascimento" class="form-control" id="data_nascimento"  oninput="calcularIdade()">
                        </div>

                        <div class="col-md-4 text-center" id="idade_div">
                            <label for="idade"><strong>Idade</strong></label>
                            <input type="number" value="<?php echo $paciente['Idade'] ?>" min="0" name="idade" placeholder="Digite a idade do paciente" class="form-control" id="idade"  readonly>
                        </div>

                        <script>
                            function calcularIdade() {
                                const dataNascimento = document.getElementById('data_nascimento').value;
                                const hoje = new Date();
                                const nascimento = new Date(dataNascimento);
                                let idade = hoje.getFullYear() - nascimento.getFullYear();
                                const mes = hoje.getMonth() - nascimento.getMonth();
                                if (mes < 0 || (mes === 0 && hoje.getDate() < nascimento.getDate())) {
                                    idade--;
                                }
                                document.getElementById('idade').value = idade;
                            }
                        </script>

                        <div class="col-md-6 text-center">
                            <label for="telefone"><strong>Telefone</strong></label>
                            <input type="text" value="<?php echo $paciente['Telefone'] ?>" name="telefone" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o telefone do paciente (apenas números)" class="form-control" id="telefone" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="endereco"><strong>Endereço</strong></label>
                            <input type="text" value="<?php echo $paciente['Endereco'] ?>" name="endereco" placeholder="Digite o endereço do paciente" class="form-control" id="endereco" >
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="bairro"><strong>Bairro</strong></label>
                            <input type="text" value="<?php echo $paciente['Bairro'] ?>" name="bairro" placeholder="Digite o bairro do paciente" class="form-control" id="bairro" >
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="cep"><strong>CEP</strong></label>
                            <input type="text" value="<?php echo $paciente['CEP'] ?>" name="cep" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o CEP do paciente" class="form-control" id="cep" >
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <label for="cidade_estado"><strong>Cidade/Estado</strong></label>
                            <input type="text" value="<?php echo $paciente['CidadeEstado'] ?>" name="cidade_estado" placeholder="Digite a cidade e o estado do paciente" class="form-control" id="cidade_estado" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="cor_pele"><strong>Cor da Pele</strong></label>
                            <select title="Selecione a cor da pele" name="cor_pele" class="form-select" id="cor_pele" >
                                <option disabled selected>Selecione a cor da pele</option>
                                <option value="Leucoderma" <?php echo $paciente['CorPele'] === 'Leucoderma' ? 'selected' : '' ?>>Leucoderma</option>
                                <option value="Feoderma" <?php echo $paciente['CorPele'] === 'Feoderma' ? 'selected' : '' ?>>Feoderma</option>
                                <option value="Melanoderma" <?php echo $paciente['CorPele'] === 'Melanoderma' ? 'selected' : '' ?>>Melanoderma</option>
                                <option value="Não Informado" <?php echo $paciente['CorPele'] === 'Não Informado' ? 'selected' : '' ?>>Não Informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="profissao"><strong>Profissao</strong></label>
                            <input type="text" value="<?php echo $paciente['Profissao'] ?>" name="profissao" placeholder="Digite a profissão do paciente" class="form-control" id="profissao" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="fumante"><strong>Fumante</strong></label>
                            <select title="O paciente é fumante?" name="fumante" class="form-select" id="fumante" >
                                <option disabled selected>Selecionar</option>
                                <option value="Sim" <?php echo $fumante_tempo_parou === '' ? 'selected' : '' ?>>Sim</option>
                                <option value="Não" <?php echo $paciente['Fumante'] === 'Não' ? 'selected' : '' ?>>Não</option>
                                <option value="Ex-fumante" <?php echo $fumante_tempo_parou !== '' ? 'selected' : '' ?>>Ex-fumante</option>
                                <option value="Não Informado" <?php echo $paciente['Fumante'] === 'Não Informado' ? 'selected' : '' ?>>Não Informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="especificacao_fumante_div" style="display: none;">
                            <label for="especificacao_fumante"><strong>Especificação</strong></label>
                            <input type="text" value="<?php echo $fumante_tipo; ?>" name="especificacao_fumante" placeholder="Especifique o tipo de fumante" class="form-control" id="especificacao_fumante">
                        </div>

                        <div class="col-md-6 text-center" id="quantidade_fumante_div" style="display: none;">
                            <label for="quantidade_fumante"><strong>Quantidade</strong></label>
                            <input type="text" value="<?php echo $fumante_quantidade ?>" name="quantidade_fumante" placeholder="Digite quantos cigarros o paciente usava" class="form-control" id="quantidade_fumante">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_fumante_div" style="display: none;">
                            <label for="tempo_fumante"><strong>Tempo</strong></label>
                            <input type="text" value="<?php echo $fumante_tempo ?>" name="tempo_fumante" placeholder="Digite há quanto tempo o paciente é fumante" class="form-control" id="tempo_fumante">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_foi_fumante_div" style="display: none;">
                            <label for="tempo_foi_fumante"><strong>Tempo Que Fumou</strong></label>
                            <input type="text" value="<?php echo $fumante_tempo ?>" name="tempo_foi_fumante" placeholder="Digite por quanto tempo o paciente fumou" class="form-control" id="tempo_foi_fumante">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_fumante_parou_div" style="display: none;">
                            <label for="tempo_fumante_parou"><strong>Tempo Que Parou</strong></label>
                            <input type="text" value="<?php echo $fumante_tempo_parou ?>" name="tempo_fumante_parou" placeholder="Digite há quanto tempo o paciente parou de fumar" class="form-control" id="tempo_fumante_parou">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="etilista"><strong>Etilista</strong></label>
                            <select title="O paciente é etilista?" name="etilista" class="form-select" id="etilista" >
                                <option disabled selected>Selecionar</option>
                                <option value="Sim" <?php echo $etilista_tempo_parou === '' ? 'selected' : '' ?>>Sim</option>
                                <option value="Não" <?php echo $paciente['Etilista'] === 'Não' ? 'selected' : '' ?>>Não</option>
                                <option value="Ex-etilista" <?php echo $etilista_tempo_parou !== '' ? 'selected' : '' ?>>Ex-etilista</option>
                                <option value="Não Informado" <?php echo $paciente['Etilista'] === 'Não Informado' ? 'selected' : '' ?>>Não Informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="especificacao_etilista_div" style="display: none;">
                            <label for="especificacao_etilista"><strong>Especificação</strong></label>
                            <input type="text" value="<?php echo $etilista_tipo ?>" name="especificacao_etilista" placeholder="Especifique o tipo de etilista" class="form-control" id="especificacao_etilista">
                        </div>

                        <div class="col-md-6 text-center" id="quantidade_etilista_div" style="display: none;">
                            <label for="quantidade_etilista"><strong>Quantidade</strong></label>
                            <input type="text" value="<?php echo $etilista_quantidade ?>" name="quantidade_etilista" placeholder="Digite a quantidade de álcool que o paciente ingeria" class="form-control" id="quantidade_etilista">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_etilista_div" style="display: none;">
                            <label for="tempo_etilista"><strong>Tempo</strong></label>
                            <input type="text" value="<?php echo $etilista_tempo ?>" name="tempo_etilista" placeholder="Digite há quanto tempo o paciente é etilista" class="form-control" id="tempo_etilista">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_foi_etilista_div" style="display: none;">
                            <label for="tempo_foi_etilista"><strong>Tempo Que Bebeu</strong></label>
                            <input type="text" value="<?php echo $etilista_tempo ?>" name="tempo_foi_etilista" placeholder="Digite por quanto tempo o paciente foi etilista" class="form-control" id="tempo_foi_etilista">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_etilista_parou_div" style="display: none;">
                            <label for="tempo_etilista_parou"><strong>Tempo Que Parou</strong></label>
                            <input type="text" value="<?php echo $etilista_tempo_parou ?>" name="tempo_etilista_parou" placeholder="Digite há quanto tempo o paciente parou de fumar" class="form-control" id="tempo_etilista_parou">
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Atualizar</button>
                        <!-- <?php echo "<a href='$redirectUrl' class='btn btn-primary'>Voltar</a>" ?> -->
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<style>
/* Para animação de fade-in e fade-out */
#especificacao_etilista_div, #quantidade_etilista_div, #tempo_etilista_div, #tempo_foi_etilista_div, #tempo_etilista_parou_div, 
#especificacao_fumante_div, #quantidade_fumante_div, #tempo_fumante_div, #tempo_foi_fumante_div, #tempo_fumante_parou_div,
#cartao_sus_div, #especificacao_procedencia_div
{
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
</style>

<script src="../../../public/js/camposEscondidosPaciente.js"></script>

<?php
include_once('../../partials/footer.php');
?>