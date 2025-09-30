<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

session_start();
$cargo = $_SESSION['cargo'] ?? null;

$microscopia_id = $_POST['Microscopia_id'] ?? null;

$query = "SELECT * FROM Microscopia AS M LEFT JOIN Sjogren AS S ON M.Microscopia_id = S.Microscopia_id WHERE M.Microscopia_id = :Microscopia_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':Microscopia_id', $microscopia_id, PDO::PARAM_INT);
$stmt->execute();
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
$paciente_id = $resultado['Paciente_id'];

$query = "SELECT * FROM Paciente WHERE Paciente_id = :Paciente_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
$stmt->execute();
$resultado_paciente = $stmt->fetch(PDO::FETCH_ASSOC);
$paciente = $resultado_paciente['NomePaciente'];

$sjogren = [
    'Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjögren)',
    'Sialodenite crônica discreta e inespecífica'
];

// print_r($resultado);

function getRedirectUrl($cargo, $paciente_id) {
    $paths = [
        'professor' => 'professor',
        'funcionário' => 'funcionario',
        'dentista' => 'dentista',
        'alunopos' => 'aluno'
    ];
    
    if (!isset($paths[$cargo])) {
        return false;
    }
    
    return '../../pages/' . $paths[$cargo] . '/visualizar_completo.php?Paciente_id=' . $paciente_id;
}

$redirectUrl = getRedirectUrl($cargo, $paciente_id);
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Microscopia</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/editar/editar_micro.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-12 text-center" style="display: none;">
                            <label for="microscopia_id"><strong>ID</strong></label>
                            <input type="text" name="microscopia_id" value="<?php echo $microscopia_id; ?>" readonly class="form-control" id="microscopia_id">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="paciente"><strong>Paciente</strong></label>
                            <input type="text" name="paciente" value="<?php echo $paciente; ?>" readonly class="form-control" id="paciente">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="micro"><strong>Microscopia</strong></label>
                            <textarea placeholder="Relate o que foi encontrado durante a microscopia" name="micro" class="form-control" id="micro"><?php echo $resultado['Microscopia']; ?></textarea>
                        </div>

                        <div class="col-md-12 text-center" id="diagnostico_div">
                            <label for="diagnostico"><strong>Diagnóstico</strong></label>
                            <select name="diagnostico" class="form-select" id="diagnostico" >
                                <option value="" disabled selected>Selecione o diagnóstico</option>
                                <option value="Sialodenite crônica discreta e inespecífica" 
                                <?php echo $resultado['Diagnostico'] === 'Sialodenite crônica discreta e inespecífica' ?
                                'selected' : '' ?>>
                                    Sialodenite crônica discreta e inespecífica
                                </option>
                                <option value="Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjögren)"
                                <?php echo $resultado['Diagnostico'] === 'Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjögren)' ? 'selected' : '' ?>>
                                    Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjögren)
                                </option>
                                <option value="Outro" <?php echo (!in_array($resultado['Diagnostico'], $sjogren)) ? 'selected' : '' ?>>
                                    Padrão
                                </option>
                            </select>
                        </div>

                        <div class="col-md-12 text-center" id="diagnostico_texto_div" style="display: none;">
                            <label for="diagnostico_texto"><strong>Diagnóstico</strong></label>
                            <input type="text" value="<?php echo $resultado['Diagnostico'] ?>" name="diagnostico_texto" placeholder="Dê o diagnóstico" class="form-control" id="diagnostico_texto" >
                        </div>

                        <div class="col-md-12 text-center" id="area_amostra_div" style="display: none;">
                            <label for="area_amostra"><strong>Área da amostra glandular</strong></label>
                            <input type="text" value="<?php echo $resultado['AreaAmostra'] ?>" name="area_amostra" placeholder="Dê o diagnóstico" class="form-control" id="area_amostra" >
                        </div>

                        <div class="col-md-12 text-center" id="num_focos_div" style="display: none;">
                            <label for="num_focos"><strong>Nº de Focos Observados</strong></label>
                            <input type="text" value="<?php echo $resultado['Focos'] ?>" name="num_focos" placeholder="Dê o diagnóstico" class="form-control" id="num_focos" >
                        </div>

                        <div class="col-md-12 text-center" id="focus_score_div" style="display: none;">
                            <label for="focus_score"><strong>Focus Score</strong></label>
                            <input type="text" value="<?php echo $resultado['FocusScore'] ?>" name="focus_score" placeholder="Dê o diagnóstico" class="form-control" id="focus_score" >
                        </div>

                        <div class="col-md-12 text-center" id="grau_inflamacao_div" style="display: none;">
                            <label for="grau_inflamacao"><strong>Grau de Inflamação</strong></label>
                            <input type="text" value="<?php echo $resultado['GrauInflamacao'] ?>" name="grau_inflamacao" placeholder="Dê o diagnóstico" class="form-control" id="grau_inflamacao" >
                        </div>

                        <div class="col-md-12 text-center" id="centro_germinativo_div" style="display: none;">
                            <label for="centro_germinativo"><strong>Centros Germinativos</strong></label>
                            <input type="text" value="<?php echo $resultado['CentrosGerminativos'] ?>" name="centro_germinativo" placeholder="Dê o diagnóstico" class="form-control" id="centro_germinativo" >
                        </div>

                        <div class="col-md-12 text-center" id="atrofia_acinar_div" style="display: none;">
                            <label for="atrofia_acinar"><strong>Atrofia Acinar</strong></label>
                            <input type="text" value="<?php echo $resultado['AtrofiaAcinar'] ?>" name="atrofia_acinar" placeholder="Dê o diagnóstico" class="form-control" id="atrofia_acinar" >
                        </div>

                        <div class="col-md-12 text-center" id="dilatacao_acinar_div" style="display: none;">
                            <label for="dilatacao_acinar"><strong>Dilatação Acinar</strong></label>
                            <input type="text" value="<?php echo $resultado['DilatacaoAcinar'] ?>" name="dilatacao_acinar" placeholder="Dê o diagnóstico" class="form-control" id="dilatacao_acinar" >
                        </div>

                        <div class="col-md-12 text-center" id="lesao_linfoepitelial_div" style="display: none;">
                            <label for="lesao_linfoepitelial"><strong>Lesão Linfoepitelial</strong></label>
                            <input type="text" value="<?php echo $resultado['LesaoLinfoepitelial'] ?>" name="lesao_linfoepitelial" placeholder="Dê o diagnóstico" class="form-control" id="lesao_linfoepitelial" >
                        </div>

                        <div class="col-md-12 text-center" id="dilatacao_ductal_div" style="display: none;">
                            <label for="dilatacao_ductal"><strong>Dilatação Ductal</strong></label>
                            <input type="text" value="<?php echo $resultado['DilatacaoDuctal'] ?>" name="dilatacao_ductal" placeholder="Dê o diagnóstico" class="form-control" id="dilatacao_ductal" >
                        </div>

                        <div class="col-md-12 text-center" id="fibrose_div" style="display: none;">
                            <label for="fibrose"><strong>Fibrose</strong></label>
                            <input type="text" value="<?php echo $resultado['Fibrose'] ?>" name="fibrose" placeholder="Dê o diagnóstico" class="form-control" id="fibrose" >
                        </div>

                        <div class="col-md-12 text-center" id="infiltracao_adiposa_div" style="display: none;">
                            <label for="infiltracao_adiposa"><strong>Infiltração Adiposa</strong></label>
                            <input type="text" value="<?php echo $resultado['InfiltracaoAdiposa'] ?>" name="infiltracao_adiposa" placeholder="Dê o diagnóstico" class="form-control" id="infiltracao_adiposa" >
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="nota"><strong>Nota</strong></label>
                            <input type="text" name="nota" value="<?php echo $resultado['Nota']; ?>" class="form-control" id="nota">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="patologista"><strong>Patologista</strong></label>
                            <select name="patologista" class="form-select" id="patologista" required>
                                <option disabled>Selecione o patologista</option>
                                <option value="Dr. Felipe Paiva Fonseca - CRO-MG: 48.333" <?php echo ($resultado['Patologista'] == 'Dr. Felipe Paiva Fonseca - CRO-MG: 48.333') ? 'selected' : ''; ?>>Dr. Felipe Paiva Fonseca - CRO-MG: 48.333</option>
                                <option value="Dra. Maria Cássia Ferreira de Aguiar - CRO-MG: 13.052" <?php echo ($resultado['Patologista'] == 'Dra. Maria Cássia Ferreira de Aguiar - CRO-MG: 13.052') ? 'selected' : ''; ?>>Dra. Maria Cássia Ferreira de Aguiar - CRO-MG: 13.052</option>
                                <option value="Dra. Patrícia Carlos Caldeira - CRO-MG: 35.414" <?php echo ($resultado['Patologista'] == 'Dra. Patrícia Carlos Caldeira - CRO-MG: 35.414') ? 'selected' : ''; ?>>Dra. Patrícia Carlos Caldeira - CRO-MG: 35.414</option>
                                <option value="Dr. Ricardo Alves de Mesquita - CRO-MG: 21.189" <?php echo ($resultado['Patologista'] == 'Dr. Ricardo Alves de Mesquita - CRO-MG: 21.189') ? 'selected' : ''; ?>>Dr. Ricardo Alves de Mesquita - CRO-MG: 21.189</option>
                                <option value="Dr. Ricardo Santiago Gomez - CRO-MG: 15.331" <?php echo ($resultado['Patologista'] == 'Dr. Ricardo Santiago Gomez - CRO-MG: 15.331') ? 'selected' : ''; ?>>Dr. Ricardo Santiago Gomez - CRO-MG: 15.331</option>
                                <option value="Dra. Sílvia Ferreira de Sousa - CRO-MG: 36.519" <?php echo ($resultado['Patologista'] == 'Dra. Sílvia Ferreira de Sousa - CRO-MG: 36.519') ? 'selected' : ''; ?>>Dra. Sílvia Ferreira de Sousa - CRO-MG: 36.519</option>
                                <option value="Dra. Tarcília Aparecida da Silva - CRO-MG: 20.690" <?php echo ($resultado['Patologista'] == 'Dra. Tarcília Aparecida da Silva - CRO-MG: 20.690') ? 'selected' : ''; ?>>Dra. Tarcília Aparecida da Silva - CRO-MG: 20.690</option>
                            </select>
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Atualizar</button>
                        <?php echo "<a href='$redirectUrl' class='btn btn-primary'>Voltar</a>" ?>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<script src="../../../public/js/camposEscondidosMicro.js"></script>

<style>
    /* Para animação de fade-in e fade-out */
    #diagnostico_texto_div, #area_amostra_div,
    #num_focos_div, #focus_score_div,
    #grau_inflamacao_div, #centros_germinativos,
    #atrofia_acinar_div, #dilatacao_acinar_div,
    #lesao_linfoepitelial_div, #dilatacao_ductal_div,
    #fibrose_div, #infiltracao_adiposa_div {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    #diagnostico_texto_div, #area_amostra_div,
    #num_focos_div, #focus_score_div,
    #grau_inflamacao_div, #centros_germinativos,
    #atrofia_acinar_div, #dilatacao_acinar_div,
    #lesao_linfoepitelial_div, #dilatacao_ductal_div,
    #fibrose_div, #infiltracao_adiposa_div {
        transition: opacity 0.3s ease-out;
    }
</style>

<?php
include_once('../../partials/header.php');
?>