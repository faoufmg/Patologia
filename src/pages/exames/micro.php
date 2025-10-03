<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

$cargo = $_SESSION['cargo'];

try {

    if($cargo != 'funcionário_dev') {
        $query =
                "SELECT
                    P.Paciente_id, NomePaciente
                FROM
                    Paciente AS P
                LEFT JOIN
                    Microscopia AS M
                ON
                    P.Paciente_id = M.Paciente_id
                JOIN
                    SolicitacaoExame AS SE
                ON
                    SE.CodigoSolicitacao = P.CodigoSolicitacao
                WHERE
                    M.Microscopia_id IS NULL AND SE.Ativo = 1 AND P.NomePaciente NOT IN (SELECT NomePaciente FROM Paciente WHERE NomePaciente LIKE 'teste%')";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        
        // print_r($resultado);
    }
    else {
        $query =
                "SELECT
                    P.Paciente_id, NomePaciente
                FROM
                    Paciente AS P
                LEFT JOIN
                    Microscopia AS M
                ON
                    P.Paciente_id = M.Paciente_id
                JOIN
                    SolicitacaoExame AS SE
                ON
                    SE.CodigoSolicitacao = P.CodigoSolicitacao
                WHERE
                    M.Microscopia_id IS NULL AND SE.Ativo = 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        
        // print_r($resultado);
    }


} catch (Exception $e) {
    if($cargo === 'fucnionário'){
        echo
            "<script>
                alert('" . $e->getMessage() . "');
                window.location.href = '../pages/index/index_funcionario.php';
            </script>";
    } elseif($cargo === 'alunopos') {
        echo
            "<script>
                alert('" . $e->getMessage() . "');
                window.location.href = '../pages/index/index_alunopos.php';
            </script>";
    } elseif($cargo === 'professor') {
        echo
            "<script>
                alert('" . $e->getMessage() . "');
                window.location.href = '../pages/index/index_professor.php';
            </script>";
    }
}
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Microscopia</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/micro.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-6 text-center">
                            <label for="paciente"><strong>Paciente</strong></label>
                            <select
                                class="selectpicker w-100"
                                data-style="btn btn-light border"
                                name="paciente"
                                id="paciente"
                                data-live-search="true"
                                data-live-search-style="startsWith"
                                data-live-search-normalize="true"
                                data-size="9"
                                required>
                                <option value="Selecione o paciente" disabled selected>Selecione o paciente</option>
                                <?php
                                if (!empty($resultado)) {
                                    foreach ($resultado as $row) {
                                        echo '<option value="' . $row['Paciente_id'] . '">' . $row['NomePaciente'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Nenhum paciente encontrado.</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="cod_exame"><strong>Código do Exame</strong></label>
                            <input type="text" readonly name="cod_exame" class="form-control" id="cod_exame" >
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="micro"><strong>Microscopia</strong></label>
                            <textarea placeholder="Relate o que foi encontrado durante a microscopia" name="micro" class="form-control" id="micro" ></textarea>
                        </div>

                        <div class="col-md-12 text-center" id="diagnostico_div">
                            <label for="diagnostico"><strong>Diagnóstico</strong></label>
                            <select name="diagnostico" class="form-select" id="diagnostico" >
                                <option value="" disabled>Selecione o diagnóstico</option>
                                <option value="Sialodenite crônica discreta e inespecífica">Sialodenite crônica discreta e inespecífica</option>
                                <option value="Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjögren)">Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjogren)</option>
                                <option selected value="Outro">Padrão</option>
                            </select>
                        </div>

                        <div class="col-md-12 text-center" id="diagnostico_texto_div" style="display: none;">
                            <label for="diagnostico_texto"><strong>Diagnóstico</strong></label>
                            <input type="text" name="diagnostico_texto" placeholder="Dê o diagnóstico" class="form-control" id="diagnostico_texto" >
                        </div>

                        <div class="col-md-12 text-center" id="area_amostra_div" style="display: none;">
                            <label for="area_amostra"><strong>Área da amostra glandular</strong></label>
                            <input type="text" name="area_amostra" placeholder="Dê o diagnóstico" class="form-control" id="area_amostra" >
                        </div>

                        <div class="col-md-12 text-center" id="num_focos_div" style="display: none;">
                            <label for="num_focos"><strong>Nº de Focos Observados</strong></label>
                            <input type="text" name="num_focos" placeholder="Dê o diagnóstico" class="form-control" id="num_focos" >
                        </div>

                        <div class="col-md-12 text-center" id="focus_score_div" style="display: none;">
                            <label for="focus_score"><strong>Focus Score</strong></label>
                            <input type="text" name="focus_score" placeholder="Dê o diagnóstico" class="form-control" id="focus_score" >
                        </div>

                        <div class="col-md-12 text-center" id="grau_inflamacao_div" style="display: none;">
                            <label for="grau_inflamacao"><strong>Grau de Inflamação</strong></label>
                            <input type="text" name="grau_inflamacao" placeholder="Dê o diagnóstico" class="form-control" id="grau_inflamacao" >
                        </div>

                        <div class="col-md-12 text-center" id="centro_germinativo_div" style="display: none;">
                            <label for="centro_germinativo"><strong>Centros Germinativos</strong></label>
                            <input type="text" name="centro_germinativo" placeholder="Dê o diagnóstico" class="form-control" id="centro_germinativo" >
                        </div>

                        <div class="col-md-12 text-center" id="atrofia_acinar_div" style="display: none;">
                            <label for="atrofia_acinar"><strong>Atrofia Acinar</strong></label>
                            <input type="text" name="atrofia_acinar" placeholder="Dê o diagnóstico" class="form-control" id="atrofia_acinar" >
                        </div>

                        <div class="col-md-12 text-center" id="dilatacao_acinar_div" style="display: none;">
                            <label for="dilatacao_acinar"><strong>Dilatação Acinar</strong></label>
                            <input type="text" name="dilatacao_acinar" placeholder="Dê o diagnóstico" class="form-control" id="dilatacao_acinar" >
                        </div>

                        <div class="col-md-12 text-center" id="lesao_linfoepitelial_div" style="display: none;">
                            <label for="lesao_linfoepitelial"><strong>Lesão Linfoepitelial</strong></label>
                            <input type="text" name="lesao_linfoepitelial" placeholder="Dê o diagnóstico" class="form-control" id="lesao_linfoepitelial" >
                        </div>

                        <div class="col-md-12 text-center" id="dilatacao_ductal_div" style="display: none;">
                            <label for="dilatacao_ductal"><strong>Dilatação Ductal</strong></label>
                            <input type="text" name="dilatacao_ductal" placeholder="Dê o diagnóstico" class="form-control" id="dilatacao_ductal" >
                        </div>

                        <div class="col-md-12 text-center" id="fibrose_div" style="display: none;">
                            <label for="fibrose"><strong>Fibrose</strong></label>
                            <input type="text" name="fibrose" placeholder="Dê o diagnóstico" class="form-control" id="fibrose" >
                        </div>

                        <div class="col-md-12 text-center" id="infiltracao_adiposa_div" style="display: none;">
                            <label for="infiltracao_adiposa"><strong>Infiltração Adiposa</strong></label>
                            <input type="text" name="infiltracao_adiposa" placeholder="Dê o diagnóstico" class="form-control" id="infiltracao_adiposa" >
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="nota"><strong>Nota</strong></label>
                            <input type="text" name="nota" class="form-control" id="nota">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="patologista"><strong>Patologista</strong></label>
                            <select name="patologista" class="form-select" id="patologista" >
                                <option value="" disabled selected>Selecione o patologista</option>
                                <option>Dr. Felipe Paiva Fonseca - CRO-MG: 48.333</option>
                                <option>Dra. Maria Cássia Ferreira de Aguiar - CRO-MG: 13.052</option>
                                <option>Dra. Patrícia Carlos Caldeira - CRO-MG: 35.414</option>
                                <option>Dr. Ricardo Alves de Mesquita - CRO-MG: 21.189</option>
                                <option>Dr. Ricardo Santiago Gomez - CRO-MG: 15.331</option>
                                <option>Dra. Sílvia Ferreira de Sousa - CRO-MG: 36.519</option>
                                <option>Dra. Tarcília Aparecida da Silva - CRO-MG: 20.690</option>
                            </select>
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <!-- <a class="btn btn-light">Em Manutenção</button> -->
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                        <?php if($cargo === 'funcionário' || $cargo === 'funcionário_dev'): ?>
                            <a href='../index/index_funcionario.php' class='btn btn-primary'>Voltar</a>
                        <?php endif; ?>
                        <?php if($cargo === 'professor' || $cargo === 'professor_dev'): ?>
                            <a href='../index/index_professor.php' class='btn btn-primary'>Voltar</a>
                        <?php endif; ?>
                        <?php if($cargo === 'alunopos'): ?>
                            <a href='../index/index_alunopos.php' class='btn btn-primary'>Voltar</a>
                        <?php endif; ?>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<script src="../../../public/js/camposEscondidosMicro.js"></script>
<script src="../../../public/js/informacoes.js"></script>

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
include_once('../../partials/footer.php');
?>