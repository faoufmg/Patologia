<?php
include_once('../../../config/db.php');
include_once('../../partials/header.php');

$codigo_solicitacao = isset($_GET['cod']) ? $_GET['cod'] : '';
?>

<section class="conteudo">
    <div class="container-fluid">

        <figure class="text-center">
            <h1>Dados do Paciente</h1>
        </figure>

        <div class="row list-box">
            <div class="col">
                <form action="../../models/dados_paciente.php" method="POST" enctype="multipart/form-data">
                    <div class="row">


                        <div class="col-md-2 text-center" style="display: none;">
                            <label for="codigo_solicitacao"><strong>Código de Solicitação</strong></label>
                            <input type="text" name="codigo_solicitacao" value="<?php echo $codigo_solicitacao ?>" class="form-control" id="codigo_solicitacao" readonly required>
                        </div>

                        <div class="col-md-6 text-center" id="procedencia_div">
                            <label for="procedencia"><strong>Procedência do Exame</strong></label>
                            <select title="Selecione a procedência do exame" name="procedencia" class="form-select" id="procedencia" required>
                                <option value="" disabled selected>Selecione a procedência do exame</option>
                                <option value="Patologia FO-UFMG">Patologia FO-UFMG</option>
                                <option value="FO-UFMG - Outros">FO-UFMG - Outros</option>
                                <option value="Hospital Municipal Odilon Behrens">Hospital Municipal Odilon Behrens</option>
                                <option value="Hospital das Clínicas">Hospital das Clínicas</option>
                                <option value="CEO -PBH">CEO - PBH</option>
                                <option value="Particular">Particular</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center" id="especificacao_procedencia_div" style="display: none;">
                            <label for="especificacao_procedencia"><strong>Especificação</strong></label>
                            <input type="text" name="especificacao_procedencia" placeholder="Especifique a procedência do exame" class="form-control" id="especificacao_procedencia">
                        </div>

                        <div class="col-md-6 text-center" id="paciente_div">
                            <label for="paciente"><strong>Paciente</strong></label>
                            <input type="text" placeholder="Digite o nome do paciente" name="paciente" class="form-control" id="paciente" required>
                        </div>

                        <div class="col-md-6 text-center" id="cartao_sus_div" style="display: none;">
                            <label for="cartao_sus"><strong>Cartão Nacional do SUS (CNS)</strong></label>
                            <input type="text" name="cartao_sus" maxlength="15" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o número do cartão do SUS do paciente" class="form-control" id="cartao_sus" required>
                        </div>

                        <div class="col-md-6 text-center" id="pep_div" style="display: none;">
                            <label for="pep"><strong>Guia de Solicitação de Exame</strong></label>
                            <input type="file" name="pep" class="form-control" id="pep" required>
                        </div>

                        <div class="col-md-4 text-center" id="sexo_div">
                            <label for="sexo"><strong>Sexo</strong></label>
                            <select title="Selecione o sexo do paciente" name="sexo" class="form-select" id="sexo" required>
                                <option value="" disabled selected>Selecionar</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Não informado">Não informado</option>
                            </select>
                        </div>

                        <div class="col-md-4 text-center" id="data_nascimento_div">
                            <label for="data_nascimento"><strong>Data de Nascimento</strong></label>
                            <input type="date" min="1900-01-01" max="<?= date('Y-m-d') ?>" name="data_nascimento" class="form-control" id="data_nascimento" required oninput="calcularIdade()">
                        </div>

                        <div class="col-md-4 text-center" id="idade_div">
                            <label for="idade"><strong>Idade</strong></label>
                            <input type="number" min="0" name="idade" placeholder="Digite a idade do paciente" class="form-control" id="idade" required readonly>
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
                            <input type="text" name="telefone" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o telefone do paciente (apenas números)" class="form-control" id="telefone" required>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="endereco"><strong>Endereço</strong></label>
                            <input type="text" name="endereco" placeholder="Digite o endereço do paciente" class="form-control" id="endereco" required>
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="bairro"><strong>Bairro</strong></label>
                            <input type="text" name="bairro" placeholder="Digite o bairro do paciente" class="form-control" id="bairro" required>
                        </div>

                        <div class="col-md-4 text-center">
                            <label for="cep"><strong>CEP</strong></label>
                            <input type="text" name="cep" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Digite o CEP do paciente" class="form-control" id="cep" required>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <label for="cidade_estado"><strong>Cidade/Estado</strong></label>
                            <input type="text" name="cidade_estado" placeholder="Digite a cidade e o estado do paciente" class="form-control" id="cidade_estado" required>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="cor_pele"><strong>Cor da Pele</strong></label>
                            <select title="Selecione a cor da pele" name="cor_pele" class="form-select" id="cor_pele" required>
                                <option value="" disabled selected>Selecione a cor da pele</option>
                                <option value="Leucoderma">Leucoderma</option>
                                <option value="Feoderma">Feoderma</option>
                                <option value="Melanoderma">Melanoderma</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="profissao"><strong>Profissao</strong></label>
                            <input type="text" name="profissao" placeholder="Digite a profissão do paciente" class="form-control" id="profissao" required>
                        </div>

                        <div class="col-md-6 text-center">
                            <label for="fumante"><strong>Fumante</strong></label>
                            <select title="O paciente é fumante?" name="fumante" class="form-select" id="fumante" required>
                                <option value="" disabled selected>Selecionar</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                                <option value="Ex-fumante">Ex-fumante</option>
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
                            <select title="O paciente é etilista?" name="etilista" class="form-select" id="etilista" required>
                                <option value="" disabled selected>Selecionar</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                                <option value="Ex-etilista">Ex-etilista</option>
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
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #831D1C">Cadastrar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<script src="../../../public/js/camposEscondidosPaciente.js"></script>

<style>
/* Para animação de fade-in e fade-out */
#especificacao_etilista_div, #quantidade_etilista_div, #tempo_etilista_div, #tempo_foi_estilista_div, #tempo_etilista_parou_div, 
#especificacao_fumante_div, #quantidade_fumante_div, #tempo_fumante_div, #tempo_foi_fumante_div, #tempo_fumante_parou_div,
#pep_div, #cartao_sus_div, #especificacao_procedencia_div
{
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
</style>

<?php
include_once('../../partials/footer.php');
?>