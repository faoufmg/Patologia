<?php
include_once('../../partials/header.php');
include_once('../../../config/db.php');

session_start();

$cargo = $_SESSION['Cargo'] ?? null;
$macroscopia_id = $_POST['Macroscopia_id'] ?? null;

$query = "SELECT * FROM Macroscopia WHERE Macroscopia_id = :Macroscopia_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':Macroscopia_id', $macroscopia_id, PDO::PARAM_INT);
$stmt->execute();
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
$paciente_id = $resultado['Paciente_id'];

$query = "SELECT * FROM Paciente WHERE Paciente_id = :Paciente_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
$stmt->execute();
$resultado_paciente = $stmt->fetch(PDO::FETCH_ASSOC);
$paciente = $resultado_paciente['NomePaciente'];

$formatos = explode(', ', $resultado['Formato']);
$formato_array = [
    'Irregular', 'Arredondado', 'Ovalado', 'Triangular', 'Retangular',
    'Alongado', 'Navicular', 'Capsular', 'Quadrangular', ''
];

$superficies = explode(', ', $resultado['Superficie']);
$superficie_array = [
    'Irregular', 'Lisa', 'Papilar', 'Rugosa', 'Verrucosa', ''
];

$coloracoes = explode(', ', $resultado['ColoracaoMacro']);
$coloracao_array = [
    'Parda', 'Esbranquiçada', 'Acastanhada', 'Amarelada', 'Enegrecida',
    'Acinzentada', ''
];

$consistencias = explode(', ', $resultado['Consistencia']);
$consistencia_array = [
    'Fibrosa', 'Amolecida', 'Fibroelástica', 'Borrachoide', 'Friável', 'Dura', ''
];

// Função para verificar os valores que estão no banco
function value_in_array($values, $array): bool {
    foreach($values as $value) {
        if(in_array($value, $array)) {
            return true;
        }
    }
    return false;
}

function getRedirectUrl($cargo, $paciente_id) {
    $paths = [
        'professor' => 'professor',
        'funcionário' => 'funcionario',
        'dentista' => 'dentista',
        'alunopos' => 'alunopos'
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
            <h1>Macroscopia</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/editar/editar_macro.php" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-12 text-center" style="display: none;">
                            <label for="macroscopia_id"><strong>ID</strong></label>
                            <input type="text" name="macroscopia_id" value="<?php echo $macroscopia_id; ?>" readonly class="form-control" id="macroscopia_id">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="diagnostico"><strong>Paciente</strong></label>
                            <input type="text" name="diagnostico" value="<?php echo $paciente; ?>" readonly class="form-control" id="diagnostico">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="fragmentos"><strong>Quantidade de Fragmentos</strong></label>
                            <input type="text" name="fragmentos" value="<?php echo $resultado['Fragmentos'] ?>" placeholder="Digite a quantidade de fragmentos" min="0" class="form-control" id="fragmentos" required>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="tecido"><strong>Tecido do Fragmento</strong></label>
                            <select title="Selecione o tecido da amostra" name="tecido" class="form-select" id="tecido" required>
                                <option disabled <?php echo empty($resultado['TipoFragmento']) ? 'selected' : ''; ?>>Selecione o tecido da amostra</option>
                                <option value="Tecido mole" <?php echo $resultado['TipoFragmento'] === 'Tecido mole' ? 'selected' : ''; ?>>Tecido mole</option>
                                <option value="Tecido duro" <?php echo $resultado['TipoFragmento'] === 'Tecido duro' ? 'selected' : ''; ?>>Tecido duro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="formato_div">
                            <label for="formato"><strong>Formato</strong></label>
                            <select title="Selecione o formato da amostra" name="formato[]" class="selectpicker w-100" id="formato"
                            multiple data-style="btn btn-light border" data-size="7">
                                <option disabled>Selecione o formato da amostra</option>
                                <option value="Irregular" <?php echo (in_array("Irregular", $formatos)) ? 'selected' : ''; ?>>Irregular</option>
                                <option value="Arredondado" <?php echo (in_array("Arredondado", $formatos)) ? 'selected' : ''; ?>>Arredondado</option>
                                <option value="Ovalado" <?php echo (in_array("Ovalado", $formatos)) ? 'selected' : ''; ?>>Ovalado</option>
                                <option value="Triangular" <?php echo (in_array("Triangular", $formatos)) ? 'selected' : ''; ?>>Triangular</option>
                                <option value="Retangular" <?php echo (in_array("Retangular", $formatos)) ? 'selected' : ''; ?>>Retangular</option>
                                <option value="Alongado" <?php echo (in_array("Alongado", $formatos)) ? 'selected' : ''; ?>>Alongado</option>
                                <option value="Navicular" <?php echo (in_array("Navicular", $formatos)) ? 'selected' : ''; ?>>Navicular</option>
                                <option value="Capsular" <?php echo (in_array("Capsular", $formatos)) ? 'selected' : ''; ?>>Capsular</option>
                                <option value="Quadrangular" <?php echo (in_array("Quadrangular", $formatos)) ? 'selected' : ''; ?>>Quadrangular</option>
                                <option value="Outro" <?php echo (!value_in_array($formatos, $formato_array)) ? 'selected' : ''; ?>>Outro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="formato_outro_div" style="display: none;">
                            <label for="formato_outro"><strong>Formato - Outro</strong></label>
                            <input type="text" value="<?php echo $resultado['Formato'] ?>" name="formato_outro" placeholder="Especifique o formato da amostra" class="form-control" id="formato_outro">
                        </div>

                        <div class="col-md-6 text-center" id="superficie_div">
                            <label for="superficie"><strong>Superfície</strong></label>
                            <select title="Selecione a superfície da amostra" name="superficie[]" class="selectpicker w-100" id="superficie" 
                            multiple data-style="btn btn-light border">
                                <option disabled>Selecione a superfície da amostra</option>
                                <option value="Irregular" <?php echo (in_array("Irregular", $superficies)) ? 'selected' : ''; ?>>Irregular</option>
                                <option value="Lisa" <?php echo (in_array("Lisa", $superficies)) ? 'selected' : ''; ?>>Lisa</option>
                                <option value="Papilar" <?php echo (in_array("Papilar", $superficies)) ? 'selected' : ''; ?>>Papilar</option>
                                <option value="Rugosa" <?php echo (in_array("Rugosa", $superficies)) ? 'selected' : ''; ?>>Rugosa</option>
                                <option value="Verrucosa" <?php echo (in_array("Verrucosa", $superficies)) ? 'selected' : ''; ?>>Verrucosa</option>
                                <option value="Outro" <?php echo (!value_in_array($superficies, $superficie_array)) ? 'selected' : ''; ?>>Outro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="superficie_outro_div" style="display: none;">
                            <label for="superficie_outro"><strong>Superfície - Outro</strong></label>
                            <input type="text" value="<?php echo $resultado['Superficie'] ?>" name="superficie_outro" placeholder="Especifique a superfície da amostra" class="form-control" id="superficie_outro">
                        </div>

                        <div class="col-md-6 text-center" id="coloracao_div">
                            <label for="coloracao"><strong>Coloração</strong></label>
                            <select title="Selecione a coloração da amostra" name="coloracao[]" class="selectpicker w-100" id="coloracao" 
                            multiple data-style="btn btn-light border" data-size="6">
                                <option disabled>Selecione a coloração da amostra</option>
                                <option value="Parda" <?php echo (in_array("Parda", $coloracoes)) ? 'selected' : ''; ?>>Parda</option>
                                <option value="Esbranquiçada" <?php echo (in_array("Esbranquiçada", $coloracoes)) ? 'selected' : ''; ?>>Esbranquiçada</option>
                                <option value="Acastanhada" <?php echo (in_array("Acastanhada", $coloracoes)) ? 'selected' : ''; ?>>Acastanhada</option>
                                <option value="Amarelada" <?php echo (in_array("Amarelada", $coloracoes)) ? 'selected' : ''; ?>>Amarelada</option>
                                <option value="Enegrecida" <?php echo (in_array("Enegrecida", $coloracoes)) ? 'selected' : ''; ?>>Enegrecida</option>
                                <option value="Acizentada" <?php echo (in_array("Acinzentada", $coloracoes)) ? 'selected' : ''; ?>>Acinzentada</option>
                                <option value="Outro" <?php echo (!value_in_array($coloracoes, $coloracao_array)) ? 'selected' : ''; ?>>Outro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="coloracao_outro_div" style="display: none;">
                            <label for="coloracao_outro"><strong>Coloração - Outro</strong></label>
                            <input type="text" value="<?php echo $resultado['ColoracaoMacro'] ?>" name="coloracao_outro" placeholder="Especifique a coloração da amostra" class="form-control" id="coloracao_outro">
                        </div>

                        <div class="col-md-6 text-center" id="consistencia_div">
                            <label for="consistencia"><strong>Consistência</strong></label>
                            <select title="Selecione a consistência da amostra" name="consistencia[]" class="selectpicker w-100" id="consistencia"
                            multiple data-style="btn btn-light border", data-size="6">
                                <option disabled >Selecione a consistência da amostra</option>
                                <option value="Fibrosa" <?php echo (in_array("Fibrosa", $consistencias)) ? 'selected' : ''; ?>>Fibrosa</option>
                                <option value="Amolecida" <?php echo (in_array("Amolecida", $consistencias)) ? 'selected' : ''; ?>>Amolecida</option>
                                <option value="Fibroelástica" <?php echo (in_array("Fibroelástica", $consistencias)) ? 'selected' : ''; ?>>Fibroelástica</option>
                                <option value="Borrachoide" <?php echo (in_array("Borrachoide", $consistencias)) ? 'selected' : ''; ?>>Borrachoide</option>
                                <option value="Friável" <?php echo (in_array("Friável", $consistencias)) ? 'selected' : ''; ?>>Friável</option>
                                <option value="Dura" <?php echo (in_array("Dura", $consistencias)) ? 'selected' : ''; ?>>Dura</option>
                                <option value="Outro" <?php echo (!value_in_array($consistencias, $consistencia_array)) ? 'selected' : ''; ?>>Outro</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="consistencia_outro_div" style="display: none;">
                            <label for="consistencia_outro"><strong>Consistência - Outro</strong></label>
                            <input type="text" value="<?php echo $resultado['Consistencia'] ?>" name="consistencia_outro" placeholder="Especifique a consistência da amostra" class="form-control" id="consistencia_outro">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="frag_inclusao"><strong>Fragmentos Para Inclusão</strong></label>
                            <input type="text" value="<?php echo $resultado['FragInclusao'] ?>" name="frag_inclusao" placeholder="Digite a quantidade de fragmentos para inclusão" min="0" class="form-control" id="frag_inclusao">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="frag_descalcificacao"><strong>Fragmentos Para Descalcificação</strong></label>
                            <input type="text" name="frag_descalcificacao" value="<?php echo $resultado['FragDescalcificacao'] ?>" placeholder="Digite a quantidade de fragmentos para descalcificação" min="0" class="form-control" id="frag_descalcificacao">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="tam_macro"><strong>Tamanho</strong></label>
                            <input type="text" name="tam_macro" value="<?php echo $resultado['TamanhoMacro'] ?>" placeholder="Digite o tamanho do fragmento (em mm)" min="0" class="form-control" id="tam_macro">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="data"><strong>Data</strong></label>
                            <input type="date" name="data" value="<?php echo $resultado['Data'] ?>" min="2000-01-01" max="2100-12-31" class="form-control" id="data" required>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="responsavel"><strong>Responsáveis</strong></label>
                            <input type="text" name="responsavel" value="<?php echo $resultado['Responsaveis'] ?>" placeholder="Digite o responsável pelo exame" class="form-control" id="responsavel" required>
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="observacao"><strong>Observações</strong></label>
                            <input type="text" name="observacao" value="<?php echo $resultado['Observacao'] ?>" placeholder="Insira observações" class="form-control" id="observacao">
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
#formato_outro_div, #superficie_outro_div, #coloracao_outro_div, #consistencia_outro_div
{
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
</style>

<script src="../../../public/js/camposEscondidosMacro.js"></script>

<?php
include_once('../../partials/footer.php');
?>