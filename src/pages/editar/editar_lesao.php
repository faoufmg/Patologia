<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

session_start();
$cargo = $_SESSION['Cargo'] ?? null;

$lesao_id = $_POST['DadosLesao_id'] ?? null;
// echo $lesao_id;

$query = "SELECT * FROM DadosLesao WHERE DadosLesao_id = :DadosLesao_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':DadosLesao_id', $lesao_id, PDO::PARAM_INT);
$stmt->execute();
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
$paciente_id = $resultado['Paciente_id'];

$query = "SELECT * FROM Paciente WHERE Paciente_id = :Paciente_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
$stmt->execute();
$resultado_paciente = $stmt->fetch(PDO::FETCH_ASSOC);
$paciente = $resultado_paciente['NomePaciente'];

$coleta_array = [
    'Biópsia Incisional',
    'Biópsia Excisional',
    'Curetagem',
    'Punção Aspirativa',
    'Raspagem',
    'Não informado'
];

$tipos_lesao = explode(', ', $resultado['Tipo']);
$coloracoes = explode(', ', $resultado['Coloracao']);

function getRedirectUrl($cargo, $paciente_id)
{
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
            <h1>Dados da Lesão</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/editar/editar_lesao.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-12 text-center" style="display: none;">
                            <label for="lesao_id"><strong>ID</strong></label>
                            <input type="text" name="lesao_id" value="<?php echo $lesao_id; ?>" readonly class="form-control" id="lesao_id">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="paciente"><strong>Paciente</strong></label>
                            <input type="text" name="paciente" value="<?php echo $paciente; ?>" readonly class="form-control" id="paciente">
                        </div>

                        <div class="col-md-6 text-center" id="paciente_div">
                            <label for="tempo_doenca"><strong>Tempo da Doença</strong></label>
                            <input type="text" value="<?php echo $resultado['Tempo'] ?>" placeholder="Digite há quanto tempo o paciente apresenta a doença" name="tempo_doenca" class="form-control" id="tempo_doenca" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="tipo_lesao"><strong>Tipo de Lesão</strong></label>
                            <select title="Selecione o tipo da lesão" name="tipo_lesao[]" class="selectpicker w-100" id="tipo_lesao"
                            multiple data-style="btn btn-light border">
                                <option disabled >Selecione o tipo da lesão</option>
                                <option value="Úlcera" <?php echo (in_array("Úlcera", $tipos_lesao)) ? 'selected' : '' ?>>Úlcera</option>
                                <option value="Mácula" <?php echo (in_array("Mácula", $tipos_lesao)) ? 'selected' : '' ?>>Mácula</option>
                                <option value="Placa" <?php echo (in_array('Placa', $tipos_lesao)) ? 'selected' : '' ?>>Placa</option>
                                <option value="Pápula" <?php echo (in_array('Pápula', $tipos_lesao)) ? 'selected' : '' ?>>Pápula</option>
                                <option value="Nódulo" <?php echo (in_array('Nódulo', $tipos_lesao)) ? 'selected' : '' ?>>Nódulo</option>
                                <option value="Tumor" <?php echo (in_array('Tumor', $tipos_lesao)) ? 'selected' : '' ?>>Tumor</option>
                                <option value="Vésico-bolhosa" <?php echo (in_array('Vésico-bolhosa', $tipos_lesao)) ? 'selected' : '' ?>>Vésico-bolhosa</option>
                                <option value="Vegetante" <?php echo (in_array('Vegetante', $tipos_lesao)) ? 'selected' : '' ?>>Vegetante</option>
                                <option value="Cística" <?php echo (in_array('Cística', $tipos_lesao)) ? 'selected' : '' ?>>Cística</option>
                                <option value="Não informado" <?php echo (in_array('Não informado', $tipos_lesao)) ? 'selected' : '' ?>>Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="numero_lesao"><strong>Número de Lesões</strong></label>
                            <select <?php echo empty($resultado['Numero']) ? 'selected' : '' ?> title="Selecione a quantidade de lesões" name="numero_lesao" class="form-select" id="numero_lesao" >
                                <option disabled selected>Selecione a quantidade de lesões</option>
                                <option value="Única" <?php echo $resultado['Numero'] === 'Única' ? 'selected' : '' ?>>Única</option>
                                <option value="Múltiplas" <?php echo $resultado['Numero'] === 'Múltiplas' ? 'selected' : '' ?>>Múltiplas</option>
                                <option value="Não informado" <?php echo $resultado['Numero'] === 'Não informado' ? 'selected' : '' ?>>Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="envolvimento_osseo_div">
                            <label for="envolvimento_osseo"><strong>Envolvimento Ósseo</strong></label>
                            <select title="Selecione se há envolvimento ósseo" name="envolvimento_osseo" class="form-select" id="envolvimento_osseo" >
                                <option value="" disabled <?php echo empty($resultado['EnvolvimentoOsseo']) ? 'selected' : ''; ?>>Selecione se há envolvimento ósseo</option>
                                <option value="Lesão extra-óssea" <?php echo ($resultado['EnvolvimentoOsseo'] === 'Lesão extra-óssea') ? 'selected' : ''; ?>>Lesão extra-óssea</option>
                                <option value="Lesão intra-óssea" <?php echo ($resultado['EnvolvimentoOsseo'] === 'Lesão intra-óssea') ? 'selected' : ''; ?>>Lesão intra-óssea</option>
                                <option value="Não informado" <?php echo ($resultado['EnvolvimentoOsseo'] === 'Não informado') ? 'selected' : ''; ?>>Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="envolvimento_osseo_img_div" style="display: none;">
                            <label for="envolvimento_osseo_img"><strong>Anexar Imagem(ns)</strong></label>
                            <input type="file" name="envolvimento_osseo_img[]" class="form-control" id="envolvimento_osseo_img" multiple>
                        </div>

                        <div class="col-md-6 text-center" id="foto_clinica_div">
                            <label for="foto_clinica"><strong>Imagem Clínica</strong></label>
                            <select name="foto_clinica" class="form-select" id="foto_clinica" >
                                <option value="" selected disabled>Selecione se há imagens clínicas</option>
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
                            <select title="Selecione a coloração da lesão" name="coloracao[]" class="selectpicker w-100" id="coloracao"
                            multiple data-style="btn btn-light border">
                                <option disabled >Selecione a coloração da lesão</option>
                                <option value="Eritematosa" <?php echo (in_array('Eritematosa', $coloracoes)) ? 'selected' : '' ?>>Eritematosa</option>
                                <option value="Branca" <?php echo (in_array('Branca', $coloracoes)) ? 'selected' : '' ?>>Branca</option>
                                <option value="Arroxeada" <?php echo (in_array('Arroxeada', $coloracoes)) ? 'selected' : '' ?>>Arroxeada</option>
                                <option value="Acastanhada" <?php echo (in_array('Acastanhada', $coloracoes)) ? 'selected' : '' ?>>Acastanhada</option>
                                <option value="Enegrecida" <?php echo (in_array('Enegrecida', $coloracoes)) ? 'selected' : '' ?>>Enegrecida</option>
                                <option value="Semelhante à mucosa bucal" <?php echo (in_array('Semelhante à mucosa bucal', $coloracoes)) ? 'selected' : '' ?>>Semelhante à mucosa bucal</option>
                                <option value="Não informado" <?php echo (in_array('Não informado', $coloracoes)) ? 'selected' : '' ?>>Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="sintomatologia_div">
                            <label for="sintomatologia"><strong>Sintomatologia</strong></label>
                            <select title="Selecione a sintomatologia" name="sintomatologia" class="form-select" id="sintomatologia" >
                                <option value="" disabled <?php echo empty($resultado['Sintomatologia']) ? 'selected' : ''; ?>>Selecione a sintomatologia</option>
                                <option value="Assintomática" <?php echo ($resultado['Sintomatologia'] === 'Assintomática') ? 'selected' : ''; ?>>Assintomática</option>
                                <option value="Sintomática" <?php echo ($resultado['Sintomatologia'] === 'Sintomática') ? 'selected' : ''; ?>>Sintomática</option>
                                <option value="Não informado" <?php echo ($resultado['Sintomatologia'] === 'Não informado') ? 'selected' : ''; ?>>Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="sintomas_div" style="display: none;">
                            <label for="sintomas"><strong>Sintomas</strong></label>
                            <input type="text" value="<?php echo $resultado['Sintoma'] ?>" placeholder="Especifique os sintomas" name="sintomas" class="form-control" id="sintomas">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="tamanho"><strong>Tamanho</strong></label>
                            <input type="text" value="<?php echo $resultado['Tamanho'] ?>" name="tamanho" placeholder="Especifique o tamanho da lesão" class="form-control" id="tamanho" >
                        </div>

                        <div class="col-md-6 text-center" id="modo_coleta_div">
                            <label for="modo_coleta"><strong>Modo de Coleta</strong></label>
                            <select title="Selecione o modo da coleta" name="modo_coleta" class="form-select" id="modo_coleta" >
                                <option value="" disabled <?php echo empty($resultado['ModoColeta']) ? 'selected' : ''; ?>>Selecione o modo da coleta</option>
                                <option value="Biópsia Incisional" <?php echo ($resultado['ModoColeta'] === 'Biópsia Incisional') ? 'selected' : ''; ?>>Biópsia Incisional</option>
                                <option value="Biópsia Excisional" <?php echo ($resultado['ModoColeta'] === 'Biópsia Excisional') ? 'selected' : ''; ?>>Biópsia Excisional</option>
                                <option value="Curetagem" <?php echo ($resultado['ModoColeta'] === 'Curetagem') ? 'selected' : ''; ?>>Curetagem</option>
                                <option value="Punção Aspirativa" <?php echo ($resultado['ModoColeta'] === 'Punção Aspirativa') ? 'selected' : ''; ?>>Punção Aspirativa</option>
                                <option value="Raspagem" <?php echo ($resultado['ModoColeta'] === 'Raspagem') ? 'selected' : ''; ?>>Raspagem</option>
                                <option value="Não informado" <?php echo ($resultado['ModoColeta'] === 'Não informado') ? 'selected' : ''; ?>>Não informado</option>
                                <option value="Outros" <?php echo (!empty($resultado['ModoColeta']) && !in_array($resultado['ModoColeta'], $coleta_array)) ? 'selected' : ''; ?>>Outros</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="modo_coleta_outro_div" style="display: none;">
                            <label for="modo_coleta_outro"><strong>Modo de Coleta - Outros</strong></label>
                            <input type="text" value="<?php echo $resultado['ModoColeta'] ?>" placeholder="Especifique o modo da coleta" name="modo_coleta_outro" class="form-control" id="modo_coleta_outro">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="manifestacao"><strong>Manifestação</strong></label>
                            <select title="Selecione a manifestação da lesão" name="manifestacao" class="form-select" id="manifestacao" >
                                <option <?php echo empty($resultado['Manifestacao']) ? 'selected' : '' ?> disabled selected>Selecione a manifestação da lesão</option>
                                <option value="Recorrente" <?php echo $resultado['Manifestacao'] === 'Recorrente' ? 'selected' : '' ?>>Recorrente</option>
                                <option value="Primitiva" <?php echo $resultado['Manifestacao'] === 'Primitiva' ? 'selected' : '' ?>>Primitiva</option>
                                <option value="Não informado" <?php echo $resultado['Manifestacao'] === 'Não informado' ? 'selected' : '' ?>>Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="data_coleta"><strong>Data da Coleta</strong></label>
                            <input type="date" name="data_coleta" value="<?php echo $resultado['DataColeta'] ?>" min="2000-01-01" max="<?= date('Y-m-d') ?>" class="form-control" id="data_coleta" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="localizacao"><strong>Localização</strong></label>
                            <input type="text" name="localizacao" value="<?php echo $resultado['Localizacao'] ?>" placeholder="Especifique a localização da lesão" class="form-control" id="localizacao" >
                        </div>

                        <div class="col-md-6 text-center" id="exame_imagem_div">
                            <label for="exame_imagem"><strong>Exames de Imagem</strong></label>
                            <select title="Selecione" name="exame_imagem" class="form-select" id="exame_imagem" >
                                <option value="" disabled <?php echo empty($resultado['ExameImagem']) ? 'selected' : ''; ?>>Selecione</option>
                                <option value="Sim" <?php echo (strpos($resultado['ExameImagem'], 'Sim') === 0) ? 'selected' : ''; ?>>Sim</option>
                                <option value="Não" <?php echo ($resultado['ExameImagem'] === 'Não') ? 'selected' : ''; ?>>Não</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="achados_exame_imagem_div" style="display: none;">
                            <label for="achados_exame_imagem"><strong>Achados</strong></label>
                            <input type="text" value="<?php echo preg_replace('/^Sim, /', '', $resultado['ExameImagem']); ?>" name="achados_exame_imagem" placeholder="Digite o que foi achado nos exames de imagem" class="form-control" id="achados_exame_imagem">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="diagnostico_clinico"><strong>Diagnóstico Clínico</strong></label>
                            <input type="text" name="diagnostico_clinico" value="<?php echo $resultado['DiagnosticoClinico'] ?>" placeholder="Dê o diagnóstico clínico" class="form-control" id="diagnostico_clinico">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="observacoes"><strong>Observações</strong></label>
                            <input type="text" name="observacoes" value="<?php echo $resultado['Observacao'] ?>" placeholder="Insira observações" class="form-control" id="observacoes" >
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Editar</button>
                        <?php echo "<a href='$redirectUrl' class='btn btn-primary'>Voltar</a>" ?>
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

<script src="../../../public/js/camposEscondidosLesao.js"></script>

<?php
include_once('../../partials/footer.php');
?>