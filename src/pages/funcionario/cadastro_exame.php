<?php
include_once('../../../config/db.php');
include_once('../../partials/header.php');

$codigo_solicitacao = isset($_GET['cod']) ? $_GET['cod'] : '';
$usuario = $_SESSION['nome_cadastro'];
$cargo = $_SESSION['cargo'];

try {

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
            <h1>Dados do Paciente</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/novo_exame.php" method="POST" enctype="multipart/form-data" id="form-cadastro-exame">
                    <div class="row">

                        <div class="col-md-4 text-center">
                            <label for="data_solicitacao"><strong>Data de Entrada</strong></label>
                            <input type="date" name="data_solicitacao" min="2000-01-01" max="2100-12-31" class="form-control" id="data_solicitacao" required oninput="CodigoExame()">
                        </div>

                        <div class="col-md-6 text-center" style="display: none;">
                            <label for="nome_usuario"><strong>Usuário</strong></label>
                            <input type="text" name="nome_usuario" value="<?php echo $usuario ?>" class="form-control" id="nome_usuario" readonly required oninput="CodigoExame()">
                        </div>

                        <div class="col-md-6 text-center" style="display: none;">
                            <label for="codigo_solicitacao"><strong>Código de Solicitação</strong></label>
                            <input type="text" name="codigo_solicitacao" class="form-control" id="codigo_solicitacao" readonly required>
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="exame_num"><strong>Número do Exame</strong></label>
                            <input type="text" placeholder="Digite o número do exame" name="exame_num" class="form-control" id="exame_num" oninput="verificarExameDuplicado()">
                            <div id="exame-num-error" style="color: red; display: none;">Este número de exame já está cadastrado.</div>
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
                                        $selected = ($row['Professores_id'] == $paciente_id) ? 'selected' : '';
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
                            <input type="text" placeholder="Digite o nome do paciente" name="paciente" class="form-control" id="paciente" >
                        </div>

                        <div class="col-md-4 text-center" id="procedencia_div">
                            <label for="procedencia"><strong>Procedência do Exame</strong></label>
                            <select title="Selecione a procedência do exame" name="procedencia" class="form-select" id="procedencia" >
                                <option value="" disabled selected>Selecione a procedência do exame</option>
                                <option value="CEO - PBH">CEO - PBH</option>
                                <option value="FAO-UFMG">FAO-UFMG</option>
                                <option value="Hospital das Clínicas">Hospital das Clínicas</option>
                                <option value="Hospital Municipal Odilon Behrens">Hospital Municipal Odilon Behrens</option>
                                <option value="Outros">Outros</option>
                                <option value="Particular">Particular</option>
                                <option value="Patologia FAO-UFMG">Patologia FAO-UFMG</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="especificacao_procedencia_div" style="display: none;">
                            <label for="especificacao_procedencia"><strong>Especificação</strong></label>
                            <input type="text" name="especificacao_procedencia" placeholder="Especifique a procedência do exame" class="form-control" id="especificacao_procedencia">
                        </div>

                        <div class="col-md-4 text-center" id="remetente_div">
                            <label for="remetente"><strong>Remetente</strong></label>
                            <input type="text" placeholder="Digite quem enviou o exame" name="remetente" class="form-control" id="remetente" >
                        </div>

                        <div class="col-md-6 text-center" id="cartao_sus_div" style="display: none;">
                            <label for="cartao_sus"><strong>Cartão Nacional do SUS (CNS)</strong></label>
                            <input type="text" name="cartao_sus" maxlength="16" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o número do cartão do SUS do paciente" class="form-control" id="cartao_sus">
                        </div>

                        <div class="col-md-4 text-center" id="sexo_div">
                            <label for="sexo"><strong>Sexo</strong></label>
                            <select title="Selecione o sexo do paciente" name="sexo" class="form-select" id="sexo">
                                <option value="" disabled selected>Selecionar</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-4 text-center" id="data_nascimento_div">
                            <label for="data_nascimento"><strong>Data de Nascimento</strong></label>
                            <input type="date" min="1900-01-01" max="<?= date('Y-m-d') ?>" name="data_nascimento" class="form-control" id="data_nascimento" oninput="calcularIdade()">
                        </div>

                        <div class="col-md-4 text-center" id="idade_div">
                            <label for="idade"><strong>Idade</strong></label>
                            <input type="number" min="0" name="idade" placeholder="Digite a idade do paciente" class="form-control" id="idade" >
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
                            <input type="text" name="telefone" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o telefone do paciente (apenas números)" class="form-control" id="telefone">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="cep"><strong>CEP</strong></label>
                            <input type="text" name="cep" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o CEP do paciente" class="form-control" id="cep">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="endereco"><strong>Endereço</strong></label>
                            <input type="text" name="endereco" placeholder="Digite o endereço do paciente" class="form-control" id="endereco">
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="bairro"><strong>Bairro</strong></label>
                            <input type="text" name="bairro" placeholder="Digite o bairro do paciente" class="form-control" id="bairro">
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <label for="cidade_estado"><strong>Cidade/Estado</strong></label>
                            <input type="text" name="cidade_estado" placeholder="Digite a cidade e o estado do paciente" class="form-control" id="cidade_estado">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="cor_pele"><strong>Cor da Pele</strong></label>
                            <select title="Selecione a cor da pele" name="cor_pele" class="form-select" id="cor_pele" >
                                <option value="" disabled selected>Selecione a cor da pele</option>
                                <option value="Leucoderma">Leucoderma</option>
                                <option value="Feoderma">Feoderma</option>
                                <option value="Melanoderma">Melanoderma</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="profissao"><strong>Profissao</strong></label>
                            <input type="text" name="profissao" placeholder="Digite a profissão do paciente" class="form-control" id="profissao">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="fumante"><strong>Fumante</strong></label>
                            <select title="O paciente é fumante?" name="fumante" class="form-select" id="fumante" >
                                <option value="" disabled selected>Selecionar</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                                <option value="Ex-fumante">Ex-fumante</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="especificacao_fumante_div" style="display: none;">
                            <label for="especificacao_fumante"><strong>Especificação</strong></label>
                            <input type="text" name="especificacao_fumante" placeholder="Especifique o tipo de fumante" class="form-control" id="especificacao_fumante">
                        </div>

                        <div class="col-md-6 text-center" id="quantidade_fumante_div" style="display: none;">
                            <label for="quantidade_fumante"><strong>Quantidade</strong></label>
                            <input type="text" name="quantidade_fumante" placeholder="Digite quantos cigarros o paciente usava" class="form-control" id="quantidade_fumante">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_fumante_div" style="display: none;">
                            <label for="tempo_fumante"><strong>Tempo</strong></label>
                            <input type="text" name="tempo_fumante" placeholder="Digite há quanto tempo o paciente é fumante" class="form-control" id="tempo_fumante">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_foi_fumante_div" style="display: none;">
                            <label for="tempo_foi_fumante"><strong>Tempo Que Fumou</strong></label>
                            <input type="text" name="tempo_foi_fumante" placeholder="Digite por quanto tempo o paciente fumou" class="form-control" id="tempo_foi_fumante">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_fumante_parou_div" style="display: none;">
                            <label for="tempo_fumante_parou"><strong>Tempo Que Parou</strong></label>
                            <input type="text" name="tempo_fumante_parou" placeholder="Digite há quanto tempo o paciente parou de fumar" class="form-control" id="tempo_fumante_parou">
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="etilista"><strong>Etilista</strong></label>
                            <select title="O paciente é etilista?" name="etilista" class="form-select" id="etilista" >
                                <option value="" disabled selected>Selecionar</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                                <option value="Ex-etilista">Ex-etilista</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="especificacao_etilista_div" style="display: none;">
                            <label for="especificacao_etilista"><strong>Especificação</strong></label>
                            <input type="text" name="especificacao_etilista" placeholder="Especifique o tipo de etilista" class="form-control" id="especificacao_etilista">
                        </div>

                        <div class="col-md-6 text-center" id="quantidade_etilista_div" style="display: none;">
                            <label for="quantidade_etilista"><strong>Quantidade</strong></label>
                            <input type="text" name="quantidade_etilista" placeholder="Digite a quantidade de álcool que o paciente ingeria" class="form-control" id="quantidade_etilista">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_etilista_div" style="display: none;">
                            <label for="tempo_etilista"><strong>Tempo</strong></label>
                            <input type="text" name="tempo_etilista" placeholder="Digite há quanto tempo o paciente é etilista" class="form-control" id="tempo_etilista">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_foi_etilista_div" style="display: none;">
                            <label for="tempo_foi_etilista"><strong>Tempo Que Bebeu</strong></label>
                            <input type="text" name="tempo_foi_etilista" placeholder="Digite por quanto tempo o paciente foi etilista" class="form-control" id="tempo_foi_etilista">
                        </div>

                        <div class="col-md-6 text-center" id="tempo_etilista_parou_div" style="display: none;">
                            <label for="tempo_etilista_parou"><strong>Tempo Que Parou</strong></label>
                            <input type="text" name="tempo_etilista_parou" placeholder="Digite há quanto tempo o paciente parou de fumar" class="form-control" id="tempo_etilista_parou">
                        </div>

                    </div>

                    <div class="col text-center mt-3">
                        <button type="submit" class="btn btn-primary me-2" id="btn-cadastrar" style="background-color: #831D1C">Cadastrar</button>
                        <!-- <a class="btn btn-secondary">Manutenção</a> -->
                        <a class="btn btn-secondary" href="../index/index_funcionario.php">Voltar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<script src="../../../public/js/camposEscondidosPaciente.js"></script>
<script src="../../../public/js/codigo_solicitacao.js"></script>
<script src="../../../public/js/buscar_cep.js"></script>

<script>
    function verificarExameDuplicado() {
        const exameNum = document.getElementById('exame_num').value;
        const erroDiv = document.getElementById('exame-num-error');
        const btnCadastrar = document.getElementById('btn-cadastrar');

        if (exameNum.length > 0) {
            const formData = new FormData();
            formData.append('exame_num', exameNum);

            fetch('../../models/verificar_exame.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'duplicado') {
                    erroDiv.style.display = 'block';
                    btnCadastrar.disabled = true;
                } else {
                    erroDiv.style.display = 'none';
                    btnCadastrar.disabled = false;
                }
            })
            .catch(error => console.error('Erro:', error));
        } else {
            erroDiv.style.display = 'none';
            btnCadastrar.disabled = false;
        }
    }
</script>

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

<?php
include_once('../../partials/footer.php');
?>