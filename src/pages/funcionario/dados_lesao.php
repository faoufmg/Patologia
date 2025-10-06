<?php
include_once('../../../config/db.php');
include_once('../../partials/header.php');
session_start();

$cargo = $_SESSION['cargo'];

try {

    if($cargo != 'funcionário_dev') {
        $query =
                "SELECT
                    P.Paciente_id, NomePaciente
                FROM
                    Paciente AS P
                LEFT JOIN
                    DadosLesao AS DL
                ON
                    P.Paciente_id = DL.Paciente_id
                JOIN
                    SolicitacaoExame AS SE
                ON
                    SE.CodigoSolicitacao = P.CodigoSolicitacao
                WHERE
                    DL.DadosLesao_id IS NULL AND SE.Ativo = 1 AND P.NomePaciente NOT IN (SELECT NomePaciente FROM Paciente WHERE NomePaciente LIKE 'teste%')";
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
                    DadosLesao AS DL
                ON
                    P.Paciente_id = DL.Paciente_id
                JOIN
                    SolicitacaoExame AS SE
                ON
                    SE.CodigoSolicitacao = P.CodigoSolicitacao
                WHERE
                    (DL.DadosLesao_id IS NULL) AND (SE.Ativo = 1)";
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
            <h1>Dados da Lesão</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/dados_lesao.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-6 text-center">
                            <label for="paciente"><strong>Paciente</strong></label>
                            <select
                                class="selectpicker w-100"
                                data-style="btn btn-light border"
                                name="paciente"
                                id="paciente"
                                data-live-search="true"
                                data-live-search-normalize="true"
                                data-live-search-style="startsWith"
                                data-size="10"
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

                        <div class="col-md-6 text-center" >
                            <label for="cod_exame"><strong>Código do Exame</strong></label>
                            <input type="text" readonly name="cod_exame" class="form-control" id="cod_exame" >
                        </div>

                        <div class="col-md-6 text-center" id="paciente_div">
                            <label for="tempo_doenca"><strong>Tempo da Doença</strong></label>
                            <input type="text" placeholder="Digite há quanto tempo o paciente apresenta a doença" name="tempo_doenca" class="form-control" id="tempo_doenca" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="tipo_lesao"><strong>Tipo de Lesão</strong></label>
                            <select name="tipo_lesao[]" class="selectpicker w-100" id="tipo_lesao"
                            title="Selecione o tipo da lesão" multiple data-style="btn btn-light border">
                                <option value="" disabled>Selecione o tipo da lesão</option>
                                <option value="Úlcera">Úlcera</option>
                                <option value="Mácula">Mácula</option>
                                <option value="Placa">Placa</option>
                                <option value="Pápula">Pápula</option>
                                <option value="Nódulo">Nódulo</option>
                                <option value="Tumor">Tumor</option>
                                <option value="Vésico-bolhosa">Vésico-bolhosa</option>
                                <option value="Vegetante">Vegetante</option>
                                <option value="Cística">Cística</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="numero_lesao"><strong>Número de Lesões</strong></label>
                            <select title="Selecione a quantidade de lesões" name="numero_lesao" class="form-select" id="numero_lesao" >
                                <option value="" disabled selected>Selecione a quantidade de lesões</option>
                                <option value="Única">Única</option>
                                <option value="Múltiplas">Múltiplas</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="envolvimento_osseo_div">
                            <label for="envolvimento_osseo"><strong>Envolvimento Ósseo</strong></label>
                            <select title="Selecione se há envolvimento ósseo" name="envolvimento_osseo" class="form-select" id="envolvimento_osseo" >
                                <option value="" disabled selected>Selecione se há envolvimento ósseo</option>
                                <option value="Lesão extra-óssea">Lesão extra-óssea</option>
                                <option value="Lesão intra-óssea">Lesão intra-óssea</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="envolvimento_osseo_img_div" style="display: none;">
                            <label for="envolvimento_osseo_img"><strong>Anexar Imagem(ns)</strong></label>
                            <input type="file" name="envolvimento_osseo_img[]" class="form-control" id="envolvimento_osseo_img" multiple>
                        </div>

                        <div class="col-md-6 text-center" id="foto_clinica_div">
                            <label for="foto_clinica"><strong>Foto Clínica</strong></label>
                            <select title="Selecione se há envolvimento ósseo" name="foto_clinica" class="form-select" id="foto_clinica">
                                <option value="" disabled selected>Selecione se há foto clínica para anexo</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="foto_clinica_img_div" style="display: none;">
                            <label for="foto_clinica_img"><strong>Anexar Imagem(ns)</strong></label>
                            <input type="file" name="foto_clinica_img[]" class="form-control" id="foto_clinica_img" multiple>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="coloracao"><strong>Coloração</strong></label>
                            <select
                                name="coloracao[]"
                                class="selectpicker w-100"
                                id="coloracao"
                                multiple
                                data-style="btn btn-light border"
                                title="Selecione a coloração da lesão">
                                <option value="Acastanhada">Acastanhada</option>
                                <option value="Amarelada">Amarelada</option>
                                <option value="Arroxeada">Arroxeada</option>
                                <option value="Branca">Branca</option>
                                <option value="Enegrecida">Enegrecida</option>
                                <option value="Eritematosa">Eritematosa</option>
                                <option value="Semelhante à mucosa bucal">Semelhante à mucosa bucal</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="sintomatologia_div">
                            <label for="sintomatologia"><strong>Sintomatologia</strong></label>
                            <select title="Selecione a sintomatologia" name="sintomatologia" class="form-select" id="sintomatologia" >
                                <option value="" disabled selected>Selecione a sintomatologia</option>
                                <option value="Assintomática">Assintomática</option>
                                <option value="Sintomática">Sintomática</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="sintomas_div" style="display: none;">
                            <label for="sintomas"><strong>Sintomas</strong></label>
                            <input type="text" placeholder="Especifique os sintomas" name="sintomas" class="form-control" id="sintomas" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="tamanho"><strong>Tamanho</strong></label>
                            <input type="text" name="tamanho" placeholder="Especifique o tamanho da lesão" class="form-control" id="tamanho" >
                        </div>

                        <div class="col-md-6 text-center" id="modo_coleta_div">
                            <label for="modo_coleta"><strong>Modo de Coleta</strong></label>
                            <select title="Selecione o modo da coleta" name="modo_coleta" class="form-select" id="modo_coleta" >
                                <option value="" disabled selected>Selecione o modo da coleta</option>
                                <option value="Biópsia Incisional">Biópsia Incisional</option>
                                <option value="Biópsia Excisional">Biópsia Excisional</option>
                                <option value="Curetagem">Curetagem</option>
                                <option value="Punção Aspirativa">Punção Aspirativa</option>
                                <option value="Raspagem">Raspagem</option>
                                <option value="Outros">Outros</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="modo_coleta_outro_div" style="display: none;">
                            <label for="modo_coleta_outro"><strong>Modo de Coleta - Outros</strong></label>
                            <input type="text" placeholder="Especifique o modo da coleta" name="modo_coleta_outro" class="form-control" id="modo_coleta_outro" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="manifestacao"><strong>Manifestação</strong></label>
                            <select title="Selecione a manifestação da lesão" name="manifestacao" class="form-select" id="manifestacao" >
                                <option value="" disabled selected>Selecione a manifestação da lesão</option>
                                <option value="Recorrente">Recorrente</option>
                                <option value="Primitiva">Primitiva</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="data_coleta"><strong>Data da Coleta</strong></label>
                            <input type="date" name="data_coleta" min="2000-01-01" max="<?= date('Y-m-d') ?>" class="form-control" id="data_coleta">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="localizacao"><strong>Localização</strong></label>
                            <input type="text" name="localizacao" placeholder="Especifique a localização da lesão" class="form-control" id="localizacao">
                        </div>

                        <div class="col-md-6 text-center" id="exame_imagem_div">
                            <label for="exame_imagem"><strong>Achados Radiográficos</strong></label>
                            <select title="Selecione" name="exame_imagem" class="form-select" id="exame_imagem" >
                                <option value="" disabled selected>Selecione</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="achados_exame_imagem_div" style="display: none;">
                            <label for="achados_exame_imagem"><strong>Principais Achados</strong></label>
                            <input type="text" name="achados_exame_imagem" placeholder="Digite o que foi achado nos exames de imagem" class="form-control" id="achados_exame_imagem" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="diagnostico_clinico"><strong>Diagnóstico Clínico</strong></label>
                            <input type="text" name="diagnostico_clinico" placeholder="Dê o diagnóstico clínico" class="form-control" id="diagnostico_clinico" >
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="observacoes"><strong>Observações</strong></label>
                            <input type="text" name="observacoes" placeholder="Digite observações sobre a lesão" class="form-control" id="observacoes" >
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                        <?php if($cargo === 'funcionário'): ?>
                            <a class="btn btn-secondary" href="../index/index_funcionario.php">Voltar</a>
                        <?php elseif($cargo === 'alunopos'): ?>
                            <a class="btn btn-secondary" href="../index/index_alunopos.php">Voltar</a>
                        <?php elseif($cargo === 'professor'): ?>
                            <a class="btn btn-secondary" href="../index/index_professor.php">Voltar</a>
                        <?php endif ?>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<style>
    /* Para animação de fade-in e fade-out */
    #envolvimento_osseo_img_div,
    #sintomas_div,
    #modo_coleta_outro_div,
    #achados_exame_imagem_div,
    #foto_clinica_img_div {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    #envolvimento_osseo_div,
    #sintomatologia_div,
    #modo_coleta_div,
    #exame_imagem_div,
    #foto_clinica_img_div {
        transition: opacity 0.3s ease-out;
    }
</style>

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa o selectpicker
    $('#coloracao').selectpicker();
});
</script> -->

<script src="../../../public/js/camposEscondidosLesao.js"></script>
<script src="../../../public/js/informacoes.js"></script>

<?php
include_once('../../partials/footer.php');
?>