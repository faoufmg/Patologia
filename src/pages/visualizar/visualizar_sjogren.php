<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

$cargo = $_SESSION['cargo'];
$microscopia_id = $_GET['id'];

try {
    $query =
            "SELECT
                S.*, M.Diagnostico, P.NomePaciente
            FROM 
                Sjogren AS S
            JOIN
                Microscopia AS M
            ON
                M.Microscopia_id = S.Microscopia_id
            JOIN
                Paciente AS P
            ON
                P.Paciente_id = M.Paciente_id
            WHERE
                S.Microscopia_id = :Microscopia_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Microscopia_id', $microscopia_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // print_r($resultado);
} catch (Exception $e) {
    if($cargo === 'fucnionário' || $cargo === 'funcionário_dev'){
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
    } elseif($cargo === 'professor' || $cargo === 'professor_dev') {
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
            <h1>Diagnóstico</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/micro.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-6 text-center" >
                            <label for="diagnostico_texto"><strong>Paciente</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['NomePaciente'] ?>" name="diagnostico_texto" placeholder="Dê o diagnóstico" class="form-control" id="diagnostico_texto" >
                        </div>

                        <div class="col-md-6 text-center" id="diagnostico_texto_div" >
                            <label for="diagnostico_texto"><strong>Diagnóstico</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['Diagnostico'] ?>" name="diagnostico_texto" placeholder="Dê o diagnóstico" class="form-control" id="diagnostico_texto" >
                        </div>

                        <div class="col-md-6 text-center" id="area_amostra_div" >
                            <label for="area_amostra"><strong>Área da amostra glandular</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['AreaAmostra'] ?>" name="area_amostra" placeholder="Dê o diagnóstico" class="form-control" id="area_amostra" >
                        </div>

                        <div class="col-md-6 text-center" id="num_focos_div" >
                            <label for="num_focos"><strong>Nº de Focos Observados</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['Focos'] ?>" name="num_focos" placeholder="Dê o diagnóstico" class="form-control" id="num_focos" >
                        </div>

                        <div class="col-md-6 text-center" id="focus_score_div" >
                            <label for="focus_score"><strong>Focus Score</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['FocusScore'] ?>" name="focus_score" placeholder="Dê o diagnóstico" class="form-control" id="focus_score" >
                        </div>

                        <div class="col-md-6 text-center" id="grau_inflamacao_div" >
                            <label for="grau_inflamacao"><strong>Grau de Inflamação</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['GrauInflamacao'] ?>" name="grau_inflamacao" placeholder="Dê o diagnóstico" class="form-control" id="grau_inflamacao" >
                        </div>

                        <div class="col-md-6 text-center" id="centro_germinativo_div" >
                            <label for="centro_germinativo"><strong>Centros Germinativos</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['CentrosGerminativos'] ?>" name="centro_germinativo" placeholder="Dê o diagnóstico" class="form-control" id="centro_germinativo" >
                        </div>

                        <div class="col-md-6 text-center" id="atrofia_acinar_div" >
                            <label for="atrofia_acinar"><strong>Atrofia Acinar</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['AtrofiaAcinar'] ?>" name="atrofia_acinar" placeholder="Dê o diagnóstico" class="form-control" id="atrofia_acinar" >
                        </div>

                        <div class="col-md-6 text-center" id="dilatacao_acinar_div" >
                            <label for="dilatacao_acinar"><strong>Dilatação Acinar</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['DilatacaoAcinar'] ?>" name="dilatacao_acinar" placeholder="Dê o diagnóstico" class="form-control" id="dilatacao_acinar" >
                        </div>

                        <div class="col-md-6 text-center" id="lesao_linfoepitelial_div" >
                            <label for="lesao_linfoepitelial"><strong>Lesão Linfoepitelial</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['LesaoLinfoepitelial'] ?>" name="lesao_linfoepitelial" placeholder="Dê o diagnóstico" class="form-control" id="lesao_linfoepitelial" >
                        </div>

                        <div class="col-md-4 text-center" id="dilatacao_ductal_div" >
                            <label for="dilatacao_ductal"><strong>Dilatação Ductal</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['DilatacaoDuctal'] ?>" name="dilatacao_ductal" placeholder="Dê o diagnóstico" class="form-control" id="dilatacao_ductal" >
                        </div>

                        <div class="col-md-4 text-center" id="fibrose_div" >
                            <label for="fibrose"><strong>Fibrose</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['Fibrose'] ?>" name="fibrose" placeholder="Dê o diagnóstico" class="form-control" id="fibrose" >
                        </div>

                        <div class="col-md-4 text-center" id="infiltracao_adiposa_div" >
                            <label for="infiltracao_adiposa"><strong>Infiltração Adiposa</strong></label>
                            <input type="text" readonly value="<?php echo $resultado['InfiltracaoAdiposa'] ?>" name="infiltracao_adiposa" placeholder="Dê o diagnóstico" class="form-control" id="infiltracao_adiposa" >
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <!-- <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button> -->
                        <!-- <a class="btn btn-light">Em Manutenção</button> -->
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<?php
include_once('../../partials/footer.php');
?>