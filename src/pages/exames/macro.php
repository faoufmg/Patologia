<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

$cargo = $_SESSION['cargo'];
$paciente_id = isset($_GET['id']) ? $_GET['id'] : '';

try {

    if($cargo != 'funcionário_dev' && $cargo != 'professor_dev' && $cargo != 'alunopos_dev') {
        $query =
                "SELECT
                    P.Paciente_id, NomePaciente
                FROM
                    Paciente AS P
                LEFT JOIN
                    Macroscopia AS M
                ON
                    P.Paciente_id = M.Paciente_id
                JOIN
                    SolicitacaoExame AS SE
                ON
                    SE.CodigoSolicitacao = P.CodigoSolicitacao
                JOIN
                    Laboratorio AS L
                ON
                    L.Paciente_id = P.Paciente_id
                WHERE
                    (M.Macroscopia_id IS NULL) AND (SE.Ativo = 1) AND 
                    P.NomePaciente NOT IN (SELECT NomePaciente FROM Paciente WHERE NomePaciente LIKE 'teste%')
                    AND (ExameNum NOT REGEXP '[a-zA-Z]')";
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
                    Macroscopia AS M
                ON
                    P.Paciente_id = M.Paciente_id
                JOIN
                    SolicitacaoExame AS SE
                ON
                    SE.CodigoSolicitacao = P.CodigoSolicitacao
                JOIN
                    Laboratorio AS L
                ON
                    L.Paciente_id = P.Paciente_id
                WHERE
                    (M.Macroscopia_id IS NULL) AND (SE.Ativo = 1) AND (ExameNum NOT REGEXP '[a-zA-Z]')";
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
            <h1>Macroscopia</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/macro.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-5 text-center">
                            <label for="paciente"><strong>Paciente</strong></label>
                            <select
                                class="selectpicker w-100"
                                data-style="btn btn-light border"
                                name="paciente"
                                id="paciente"
                                data-live-search="true"
                                data-live-search-style="startsWith"
                                data-live-search-normalize="true"
                                data-size="10"
                                required>
                                <option value="Selecione o paciente" disabled selected>Selecione o paciente</option>
                                <?php
                                if (!empty($resultado)) {
                                    foreach ($resultado as $row) {
                                        $selected = ($row['Paciente_id'] == $paciente_id) ? 'selected' : '';
                                        echo '<option value="' . $row['Paciente_id'] . '" ' . $selected . '>' . $row['NomePaciente'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Nenhum paciente encontrado.</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-5 text-center" >
                            <label for="cod_exame"><strong>Código do Exame</strong></label>
                            <input type="text" readonly name="cod_exame" class="form-control" id="cod_exame" >
                        </div>

                        <div class="col-md-2 text-center">
                            <label for="rev_lamina"><strong>Revisão de Lâmina</strong></label>
                            <select name="rev_lamina" class="form-select" id="rev_lamina" >
                                <option value="Não" selected>Não</option>
                                <option value="Sim">Sim</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="fragmentos"><strong>Quantidade de Fragmentos</strong></label>
                            <input type="text" name="fragmentos" placeholder="Digite a quantidade de fragmentos" min="0" class="form-control" id="fragmentos" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="tecido"><strong>Tecido do Fragmento</strong></label>
                            <select title="Selecione o tecido da amostra" name="tecido" class="form-select" id="tecido" >
                                <option disabled selected>Selecione o tecido da amostra</option>
                                <option>Tecido mole</option>
                                <option>Tecido duro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="formato_div">
                            <label for="formato"><strong>Formato</strong></label>
                            <select name="formato[]" class="selectpicker w-100" id="formato"
                            multiple title="Selecione o formato da amostra" data-style="btn btn-light border" data-size="7">
                                <option value="" disabled>Selecione o formato da amostra</option>
                                <option>Irregular</option>
                                <option>Arredondado</option>
                                <option>Ovalado</option>
                                <option>Triangular</option>
                                <option>Retangular</option>
                                <option>Alongado</option>
                                <option>Navicular</option>
                                <option>Capsular</option>
                                <option>Quadrangular</option>
                                <option>Outro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="formato_outro_div" style="display: none;">
                            <label for="formato_outro"><strong>Formato - Outro</strong></label>
                            <input type="text" name="formato_outro" placeholder="Especifique o formato da amostra" class="form-control" id="formato_outro">
                        </div>

                        <div class="col-md-6 text-center" id="superficie_div">
                            <label for="superficie"><strong>Superfície</strong></label>
                            <select name="superficie[]" class="selectpicker w-100" id="superficie"
                            multiple data-style="btn btn-light border" title="Selecione o tipo de superfície" data-size="7">
                                <option disabled>Selecione a superfície da amostra</option>
                                <option>Irregular</option>
                                <option>Lisa</option>
                                <option>Papilar</option>
                                <option>Rugosa</option>
                                <option>Verrucosa</option>
                                <option>Outro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="superficie_outro_div" style="display: none;">
                            <label for="superficie_outro"><strong>Superfície - Outro</strong></label>
                            <input type="text" name="superficie_outro" placeholder="Especifique a superfície da amostra" class="form-control" id="superficie_outro">
                        </div>

                        <div class="col-md-6 text-center" id="coloracao_div">
                            <label for="coloracao"><strong>Coloração</strong></label>
                            <select 
                                name="coloracao[]" class="selectpicker w-100" id="coloracao"
                                multiple data-style="btn btn-light border" title="Selecione a coloração" data-size="6">
                                <option disabled>Selecione a coloração da amostra</option>
                                <option>Parda</option>
                                <option>Esbranquiçada</option>
                                <option>Acastanhada</option>
                                <option>Amarelada</option>
                                <option>Enegrecida</option>
                                <option>Acinzentada</option>
                                <option>Outro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="coloracao_outro_div" style="display: none;">
                            <label for="coloracao_outro"><strong>Coloração - Outro</strong></label>
                            <input type="text" name="coloracao_outro" placeholder="Especifique a coloração da amostra" class="form-control" id="coloracao_outro">
                        </div>

                        <div class="col-md-6 text-center" id="consistencia_div">
                            <label for="consistencia"><strong>Consistência</strong></label>
                            <select 
                                name="consistencia[]" class="selectpicker w-100" id="consistencia"
                                multiple data-style="btn btn-light border" title="Selecione a consistência" data-size="6">
                                <option disabled>Selecione a consistência da amostra</option>
                                <option value="Fibrosa">Fibrosa</option>
                                <option value="Amolecida">Amolecida</option>
                                <option value="Fibroelástica">Fibroelástica</option>
                                <option value="Borrachoide">Borrachoide</option>
                                <option value="Friável">Friável</option>
                                <option value="Dura">Dura</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="consistencia_outro_div" style="display: none;">
                            <label for="consistencia_outro"><strong>Consistência - Outro</strong></label>
                            <input type="text" name="consistencia_outro" placeholder="Especifique a consistência da amostra" class="form-control" id="consistencia_outro">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="frag_inclusao"><strong>Fragmentos Para Inclusão</strong></label>
                            <input type="text" name="frag_inclusao" placeholder="Digite a quantidade de fragmentos para inclusão" min="0" class="form-control" id="frag_inclusao">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="frag_descalcificacao"><strong>Fragmentos Para Descalcificação</strong></label>
                            <input type="text" name="frag_descalcificacao" placeholder="Digite a quantidade de fragmentos para descalcificação" min="0" class="form-control" id="frag_descalcificacao">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="tam_macro"><strong>Tamanho</strong></label>
                            <input type="text" name="tam_macro" placeholder="Digite o tamanho do fragmento (em mm)" class="form-control" id="tam_macro">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="data"><strong>Data</strong></label>
                            <input type="date" name="data" min="2000-01-01" max="2100-12-31" class="form-control" id="data" >
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="responsavel"><strong>Responsáveis</strong></label>
                            <input type="text" name="responsavel" placeholder="Digite o responsável pelo exame" class="form-control" id="responsavel" >
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="observacoes"><strong>Observações</strong></label>
                            <input type="text" name="observacoes" placeholder="Digite observações sobre o exame" class="form-control" id="observacoes" >
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                        <!-- <a class='btn btn-primary'>Manutenção</a> -->
                        <!-- <?php if ($cargo === 'alunopos_dev' || $cargo === 'professor_dev' || $cargo === 'funcionário_dev'): ?>
                            <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                        <?php endif ?> -->
                        <?php if ($cargo === 'funcionário' || $cargo === 'funcionário_dev'): ?>
                            <a href='../index/index_funcionario.php' class='btn btn-primary'>Voltar</a>
                        <?php endif; ?>
                        <?php if ($cargo === 'professor' || $cargo === 'professor_dev'): ?>
                            <a href='../index/index_professor.php' class='btn btn-primary'>Voltar</a>
                        <?php endif; ?>
                        <?php if ($cargo === 'alunopos' || $cargo === 'alunopos_dev'): ?>
                            <a href='../index/index_alunopos.php' class='btn btn-primary'>Voltar</a>
                        <?php endif; ?>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<style>
/* Para animação de fade-in e fade-out */
#formato_outro_div, #superficie_outro_div, #coloracao_outro_div, #consistencia_outro_div
{
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa o selectpicker
    $('#coloracao').selectpicker();
});
</script>

<script src="../../../public/js/camposEscondidosMacro.js"></script>
<script src="../../../public/js/informacoes.js"></script>

<?php
include_once('../../partials/footer.php');
?>