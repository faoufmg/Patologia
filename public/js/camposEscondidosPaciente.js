document.addEventListener("DOMContentLoaded", function() {

    // Valores para etilista
    const etilistaSelect = document.getElementById("etilista");
    const especificacaoEtilistaDiv = document.getElementById("especificacao_etilista_div");
    const quantidadeEtilistaDiv = document.getElementById("quantidade_etilista_div");
    const tempoEtilistaDiv = document.getElementById("tempo_etilista_div");
    const tempoFoiEtilistaDiv = document.getElementById("tempo_foi_etilista_div");
    const tempoParouEtilistaDiv = document.getElementById("tempo_etilista_parou_div");

    const especificacaoEtilistaInput = document.getElementById("especificacao_etilista");
    const quantidadeEtilistaInput = document.getElementById("quantidade_etilista");
    const tempoEtilistaInput = document.getElementById("tempo_etilista");
    const tempoFoiEtilistaInput = document.getElementById("tempo_foi_etilista");
    const tempoParouEtilistaInput = document.getElementById("tempo_etilista_parou");
    
    // Valores para fumante
    const fumanteSelect = document.getElementById("fumante");
    const especificacaoFumanteDiv = document.getElementById("especificacao_fumante_div");
    const quantidadeFumanteDiv = document.getElementById("quantidade_fumante_div");
    const tempoFumanteDiv = document.getElementById("tempo_fumante_div");
    const tempoFoiFumanteDiv = document.getElementById("tempo_foi_fumante_div");
    const tempoParouFumanteDiv = document.getElementById("tempo_fumante_parou_div");

    const especificacaoFumanteInput = document.getElementById("especificacao_fumante");
    const quantidadeFumanteInput = document.getElementById("quantidade_fumante");
    const tempoFumanteInput = document.getElementById("tempo_fumante");
    const tempoFoiFumanteInput = document.getElementById("tempo_foi_fumante");
    const tempoParouFumanteInput = document.getElementById("tempo_fumante_parou");

    // Procedência do exame
    const procedenciaExameSelect = document.getElementById("procedencia");
    const cartaoSusDiv = document.getElementById("cartao_sus_div");
    const especificacaoProcedenciaDiv = document.getElementById("especificacao_procedencia_div");

    const cartaoSusInput = document.getElementById("cartao_sus");
    const especificacaoProcedenciaInput = document.getElementById("especificacao_procedencia");

    // Divs extras
    const procedenciaDiv = document.getElementById("procedencia_div");
    const pacienteDiv = document.getElementById("paciente_div");
    const sexoDiv = document.getElementById("sexo_div");
    const dataNascimentoDiv = document.getElementById("data_nascimento_div");
    const idadeDiv = document.getElementById("idade_div");
    const remetenteDiv = document.getElementById("remetente_div");
    
    const allEtilistaDivs = [
        especificacaoEtilistaDiv,
        quantidadeEtilistaDiv,
        tempoEtilistaDiv,
        tempoFoiEtilistaDiv,
        tempoParouEtilistaDiv,
    ]; // Lista de divs a modificar

    const allFumanteDivs = [
        especificacaoFumanteDiv,
        quantidadeFumanteDiv,
        tempoFumanteDiv,
        tempoFoiFumanteDiv,
        tempoParouFumanteDiv
    ]; // Lista de divs a modificar

    const allProcedenciaDivs = [
        cartaoSusDiv,
        pacienteDiv,
        sexoDiv,
        procedenciaDiv,
        remetenteDiv
    ]; // Lista de divs a modificar

    const allEtilistaInputs = [
        especificacaoEtilistaInput,
        quantidadeEtilistaInput,
        tempoEtilistaInput,
        tempoFoiEtilistaInput,
        tempoParouEtilistaInput,
    ]; // Lista de inputs a modificar

    const allFumanteInputs = [
        especificacaoFumanteInput,
        quantidadeFumanteInput,
        tempoFumanteInput,
        tempoFoiFumanteInput,
        tempoParouFumanteInput
    ]; // Lista de divs a modificar

    const allProcedenciaInputs = [
        cartaoSusInput,
        especificacaoProcedenciaInput
    ]; // Lista de divs a modificar

    function resetEtilistaFields() {

        allEtilistaDivs.forEach(div => {
            div.style.display = "none"; // Esconde todas as divs extras
            div.style.opacity = "0"; // Garante transição suave
            div.className = div.className.replace(/\bcol-md-\d+\b/g, "").trim(); // Remove qualquer col-md-* existente
            div.classList.add("col-md-6"); // Garante que volta ao tamanho padrão
        });

        allEtilistaInputs.forEach(input => {
            input.value = "";
            input.removeAttribute("required");
        })

    }

    function resetFumanteFields() {

        allFumanteDivs.forEach(div => {
            div.style.display = "none"; // Esconde todas as divs extras
            div.style.opacity = "0"; // Garante transição suave
            div.className = div.className.replace(/\bcol-md-\d+\b/g, "").trim(); // Remove qualquer col-md-* existente
            div.classList.add("col-md-6"); // Garante que volta ao tamanho padrão
        });

        allFumanteInputs.forEach(input => {
            input.value = "";
            input .removeAttribute("required");
        })

    }

    function resetProcedenciaFields() {

        allProcedenciaDivs.forEach(div => {
            div.style.display = "none"; // Esconde todas as divs extras
            div.style.opacity = "0"; // Garante transição suave
            div.className = div.className.replace(/\bcol-md-\d+\b/g, "").trim(); // Remove qualquer col-md-* existente
            div.classList.add("col-md-6"); // Garante que volta ao tamanho padrão
        });

        allProcedenciaInputs.forEach(input => {
            input.value = "";
            input .removeAttribute("required");
        })

    }

    function showDiv(div) {
        div.style.display = "block";
        setTimeout(() => div.style.opacity = "1", 10);
    }

    function hideDiv(div) {
        div.style.opacity = "0";
        setTimeout(() => div.style.display = "none", 10);
    }

procedenciaExameSelect.addEventListener("change", function() {

    let value = this.value;

    // Lógica para o campo "Especificação"
    if (value === "Outros" || value === 'FAO-UFMG') {
        showDiv(especificacaoProcedenciaDiv); // Apenas mostra, não mexe no valor
    } else {
        hideDiv(especificacaoProcedenciaDiv);
        especificacaoProcedenciaInput.value = ""; // Limpa o valor APENAS quando for esconder
    }

    // Lógica para o campo "Cartão do SUS"
    if (value === "Hospital Municipal Odilon Behrens" || value === "Hospital das Clínicas" || value === "CEO - PBH" || value === "Patologia FAO-UFMG" || value === "FAO-UFMG") {
        showDiv(cartaoSusDiv); // Apenas mostra
    } else {
        hideDiv(cartaoSusDiv);
        cartaoSusInput.value = ""; // Limpa o valor APENAS quando for esconder
    }

    if (value === "Hospital Municipal Odilon Behrens") {
        showDiv(pacienteDiv);
        showDiv(sexoDiv);
        showDiv(procedenciaDiv);
        showDiv(remetenteDiv);
        
        dataNascimentoDiv.classList.remove('col-md-4', 'col-md-6');
        dataNascimentoDiv.classList.add('col-md-3');

        pacienteDiv.classList.remove('col-md-4', 'col-md-6');
        pacienteDiv.classList.add('col-md-4');

        sexoDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        sexoDiv.classList.add('col-md-3');

        idadeDiv.classList.remove('col-md-4', 'col-md-6');
        idadeDiv.classList.add('col-md-3');

        cartaoSusDiv.classList.remove('col-md-3', 'col-md-6');
        cartaoSusDiv.classList.add('col-md-3');

        remetenteDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        remetenteDiv.classList.add('col-md-4');

        procedenciaDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        procedenciaDiv.classList.add('col-md-4');

    } else if (value === "FAO-UFMG") {
        showDiv(pacienteDiv);
        showDiv(sexoDiv);
        showDiv(procedenciaDiv);
        showDiv(remetenteDiv);

        pacienteDiv.classList.remove('col-md-3', 'col-md-6');
        pacienteDiv.classList.add('col-md-4');

        dataNascimentoDiv.classList.remove('col-md-4');
        dataNascimentoDiv.classList.add('col-md-6');

        idadeDiv.classList.remove('col-md-4');
        idadeDiv.classList.add('col-md-6');

        sexoDiv.classList.remove('col-md-3', 'col-md-6');
        sexoDiv.classList.add('col-md-4');

        cartaoSusDiv.classList.remove('col-md-3', 'col-md-6');
        cartaoSusDiv.classList.add('col-md-4');

        remetenteDiv.classList.remove('col-md-3', 'col-md-6');
        remetenteDiv.classList.add('col-md-4');

        procedenciaDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        procedenciaDiv.classList.add('col-md-4');

        especificacaoProcedenciaDiv.classList.remove('col-md-6');
        especificacaoProcedenciaDiv.classList.add('col-md-4');
    } else if (value === "Hospital das Clínicas" || value === "CEO - PBH" || value === "Patologia FAO-UFMG") {
        showDiv(pacienteDiv);
        showDiv(sexoDiv);
        showDiv(procedenciaDiv);
        showDiv(remetenteDiv);

        pacienteDiv.classList.remove('col-md-3', 'col-md-6');
        pacienteDiv.classList.add('col-md-4');

        dataNascimentoDiv.classList.remove('col-md-4', 'col-md-6');
        dataNascimentoDiv.classList.add('col-md-3');

        idadeDiv.classList.remove('col-md-4', 'col-md-6');
        idadeDiv.classList.add('col-md-3');

        sexoDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        sexoDiv.classList.add('col-md-3');

        cartaoSusDiv.classList.remove('col-md-3', 'col-md-6');
        cartaoSusDiv.classList.add('col-md-3');

        remetenteDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        remetenteDiv.classList.add('col-md-4');

        procedenciaDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        procedenciaDiv.classList.add('col-md-4');

    } else if (value === "Outros" || value === 'FAO-UFMG') {
        showDiv(pacienteDiv);
        showDiv(procedenciaDiv);
        showDiv(remetenteDiv);
        showDiv(sexoDiv);

        especificacaoProcedenciaDiv.classList.remove('col-md-3', 'col-md-6');
        especificacaoProcedenciaDiv.classList.add('col-md-3');

        procedenciaDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        procedenciaDiv.classList.add('col-md-3');

        dataNascimentoDiv.classList.remove('col-md-3');
        dataNascimentoDiv.classList.add('col-md-4');

        idadeDiv.classList.remove('col-md-3');
        idadeDiv.classList.add('col-md-4');

        pacienteDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        pacienteDiv.classList.add('col-md-3');

        sexoDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        sexoDiv.classList.add('col-md-4');

        remetenteDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        remetenteDiv.classList.add('col-md-3');

    } else { // Caso padrão (ex: "Particular")
        showDiv(pacienteDiv);
        showDiv(sexoDiv);
        showDiv(procedenciaDiv);
        showDiv(remetenteDiv);

        sexoDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        sexoDiv.classList.add('col-md-4');

        dataNascimentoDiv.classList.remove('col-md-3');
        dataNascimentoDiv.classList.add('col-md-4');

        idadeDiv.classList.remove('col-md-3');
        idadeDiv.classList.add('col-md-4');

        pacienteDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        pacienteDiv.classList.add('col-md-4');

        remetenteDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        remetenteDiv.classList.add('col-md-4');

        procedenciaDiv.classList.remove('col-md-3', 'col-md-4', 'col-md-6');
        procedenciaDiv.classList.add('col-md-4');
    }
});

    fumanteSelect.addEventListener("change", function() {
        resetFumanteFields();

        let value = this.value;
        if(value === "Sim") {
            
            resetFumanteFields();

            showDiv(especificacaoFumanteDiv);
            showDiv(quantidadeFumanteDiv);
            showDiv(tempoFumanteDiv);

            quantidadeFumanteDiv.classList.remove('col-md-6');
            quantidadeFumanteDiv.classList.add('col-md-3');

            tempoFumanteDiv.classList.remove('col-md-6');
            tempoFumanteDiv.classList.add('col-md-3');

        } else if (value === "Ex-fumante") {

            resetFumanteFields();

            showDiv(especificacaoFumanteDiv);
            showDiv(quantidadeFumanteDiv);
            showDiv(tempoFoiFumanteDiv);
            showDiv(tempoParouFumanteDiv);

            especificacaoFumanteDiv.classList.remove('col-md-6');
            especificacaoFumanteDiv.classList.add('col-md-3');

            quantidadeFumanteDiv.classList.remove('col-md-6');
            quantidadeFumanteDiv.classList.add('col-md-3');

            tempoFoiFumanteDiv.classList.remove('col-md-6');
            tempoFoiFumanteDiv.classList.add('col-md-3');

            tempoParouFumanteDiv.classList.remove('col-md-6');
            tempoParouFumanteDiv.classList.add('col-md-3');

        } else {

            hideDiv(especificacaoFumanteDiv);
            hideDiv(quantidadeFumanteDiv);
            hideDiv(tempoFumanteDiv);
            hideDiv(tempoFoiFumanteDiv);
            hideDiv(tempoParouFumanteDiv);
            
        }
    })

    etilistaSelect.addEventListener("change", function() {
        resetEtilistaFields();

        let value = this.value;
        if (value === "Sim") {

            resetEtilistaFields();

            showDiv(especificacaoEtilistaDiv);
            showDiv(quantidadeEtilistaDiv);
            showDiv(tempoEtilistaDiv);

            especificacaoEtilistaDiv.classList.remove('col-md-6');
            especificacaoEtilistaDiv.classList.add('col-md-4');

            quantidadeEtilistaDiv.classList.remove('col-md-6');
            quantidadeEtilistaDiv.classList.add('col-md-4');

            tempoEtilistaDiv.classList.remove('col-md-6');
            tempoEtilistaDiv.classList.add('col-md-4');
        } else if (value === "Ex-etilista") {

            resetEtilistaFields();

            showDiv(especificacaoEtilistaDiv);
            showDiv(quantidadeEtilistaDiv);
            showDiv(tempoFoiEtilistaDiv);
            showDiv(tempoParouEtilistaDiv);

            especificacaoEtilistaDiv.classList.remove('col-md-6');
            especificacaoEtilistaDiv.classList.add('col-md-3');

            quantidadeEtilistaDiv.classList.remove('col-md-6');
            quantidadeEtilistaDiv.classList.add('col-md-3');

            tempoFoiEtilistaDiv.classList.remove('col-md-6');
            tempoFoiEtilistaDiv.classList.add('col-md-3');

            tempoParouEtilistaDiv.classList.remove('col-md-6');
            tempoParouEtilistaDiv.classList.add('col-md-3');
        } else {
            hideDiv(especificacaoEtilistaDiv);
            hideDiv(quantidadeEtilistaDiv);
            hideDiv(tempoEtilistaDiv);
            hideDiv(tempoFoiEtilistaDiv);
            hideDiv(tempoParouEtilistaDiv);
        }
    });

    if (procedenciaExameSelect) {
        procedenciaExameSelect.dispatchEvent(new Event('change'));
    }
    if (fumanteSelect) {
        fumanteSelect.dispatchEvent(new Event('change'));
    }
    if (etilistaSelect) {
        etilistaSelect.dispatchEvent(new Event('change'));
    }
});