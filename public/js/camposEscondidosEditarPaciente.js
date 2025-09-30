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

    const allProcedenciaDivs = [
        cartaoSusDiv,
        pacienteDiv,
        sexoDiv,
        procedenciaDiv,
        remetenteDiv
    ]; // Lista de divs a modificar

    const allProcedenciaInputs = [
        cartaoSusInput,
        especificacaoProcedenciaInput
    ]; // Lista de divs a modificar

    function showDiv(div) {
        div.style.display = "block";
        setTimeout(() => div.style.opacity = "1", 10);
    }

    function hideDiv(div) {
        div.style.opacity = "0";
        setTimeout(() => div.style.display = "none", 10);
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

    function toggleProcedenciaExame() {
        let value  = procedenciaExameSelect.value;

        console.log(value);

        if(value === "Hospital Municipal Odilon Behrens"){
            resetProcedenciaFields();

            showDiv(cartaoSusDiv);
            showDiv(pacienteDiv);
            showDiv(sexoDiv);
            showDiv(procedenciaDiv);
            showDiv(remetenteDiv);
            hideDiv(especificacaoProcedenciaDiv);

            dataNascimentoDiv.classList.remove('col-md-4');
            dataNascimentoDiv.classList.add('col-md-3');

            pacienteDiv.classList.remove('col-md-6');
            pacienteDiv.classList.add('col-md-4');

            sexoDiv.classList.remove('col-md-6');
            sexoDiv.classList.add('col-md-3');

            idadeDiv.classList.remove('col-md-4');
            idadeDiv.classList.add('col-md-3');

            cartaoSusDiv.classList.remove('col-md-6');
            cartaoSusDiv.classList.add('col-md-3');

            remetenteDiv.classList.remove('col-md-6');
            remetenteDiv.classList.add('col-md-4');

            procedenciaDiv.classList.remove('col-md-6');
            procedenciaDiv.classList.add('col-md-4');
        } else if (value === "Patologia FAO-UFMG" || value === "Hospital das Clínicas" || value === "CEO - PBH") {

            resetProcedenciaFields();

            showDiv(cartaoSusDiv);
            showDiv(pacienteDiv);
            showDiv(sexoDiv);
            showDiv(procedenciaDiv);
            showDiv(remetenteDiv);
            hideDiv(especificacaoProcedenciaDiv);

            pacienteDiv.classList.remove('col-md-6');
            pacienteDiv.classList.add('col-md-4');

            dataNascimentoDiv.classList.remove('col-md-4');
            dataNascimentoDiv.classList.add('col-md-3');

            idadeDiv.classList.remove('col-md-4');
            idadeDiv.classList.add('col-md-3');

            sexoDiv.classList.remove('col-md-6');
            sexoDiv.classList.add('col-md-3');

            cartaoSusDiv.classList.remove('col-md-6');
            cartaoSusDiv.classList.add('col-md-3');

            remetenteDiv.classList.remove('col-md-6');
            remetenteDiv.classList.add('col-md-4');

            procedenciaDiv.classList.remove('col-md-6');
            procedenciaDiv.classList.add('col-md-4');
        } else if (value === "Outros" || value === "FAO-UFMG") {

            resetProcedenciaFields();

            showDiv(especificacaoProcedenciaDiv);
            showDiv(pacienteDiv);
            showDiv(procedenciaDiv);
            showDiv(remetenteDiv);
            showDiv(sexoDiv);

            especificacaoProcedenciaDiv.classList.remove('col-md-6');
            especificacaoProcedenciaDiv.classList.add('col-md-3');

            procedenciaDiv.classList.remove('col-md-6');
            procedenciaDiv.classList.add('col-md-3');

            dataNascimentoDiv.classList.remove('col-md-3');
            dataNascimentoDiv.classList.add('col-md-4');

            idadeDiv.classList.remove('col-md-3');
            idadeDiv.classList.add('col-md-4');

            pacienteDiv.classList.remove('col-md-6');
            pacienteDiv.classList.add('col-md-3');

            sexoDiv.classList.remove('col-md-6');
            sexoDiv.classList.add('col-md-4');

            procedenciaDiv.classList.remove('col-md-4');
            procedenciaDiv.classList.add('col-md-3');

            remetenteDiv.classList.remove('col-md-6');
            remetenteDiv.classList.add('col-md-3');
        } else {
            showDiv(pacienteDiv);
            showDiv(sexoDiv);
            showDiv(procedenciaDiv);
            showDiv(remetenteDiv);
            hideDiv(especificacaoProcedenciaDiv);

            sexoDiv.classList.remove('col-md-6');
            sexoDiv.classList.add('col-md-4');

            dataNascimentoDiv.classList.remove('col-md-3');
            dataNascimentoDiv.classList.add('col-md-4');

            idadeDiv.classList.remove('col-md-3');
            idadeDiv.classList.add('col-md-4');

            pacienteDiv.classList.remove('col-md-6');
            pacienteDiv.classList.add('col-md-4');

            remetenteDiv.classList.remove('col-md-6');
            remetenteDiv.classList.add('col-md-4');

            procedenciaDiv.classList.remove('col-md-6');
            procedenciaDiv.classList.add('col-md-4');
        }
    }

    function toggleFumante() {

        let value = fumanteSelect.value;

        console.log(value);

        if(value === "Sim") {
            
            showDiv(especificacaoFumanteDiv);
            showDiv(quantidadeFumanteDiv);
            showDiv(tempoFumanteDiv);

            quantidadeFumanteDiv.classList.remove('col-md-6');
            quantidadeFumanteDiv.classList.add('col-md-3');

            tempoFumanteDiv.classList.remove('col-md-6');
            tempoFumanteDiv.classList.add('col-md-3');

        } else if (value === "Ex-fumante") {

            showDiv(especificacaoFumanteDiv);
            showDiv(tempoFoiFumanteDiv);
            showDiv(tempoParouFumanteDiv);
            showDiv(quantidadeFumanteDiv);
            hideDiv(tempoFumanteDiv);

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
    }

    function toggleEtilista() {
        let value = etilistaSelect.value;

        console.log(value);

        if (value === "Sim") {

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

            showDiv(especificacaoEtilistaDiv);
            showDiv(tempoFoiEtilistaDiv);
            showDiv(tempoParouEtilistaDiv);
            showDiv(quantidadeEtilistaDiv);
            hideDiv(tempoEtilistaDiv);

            especificacaoEtilistaDiv.classList.remove('col-md-6');
            especificacaoEtilistaDiv.classList.add('col-md-3');

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
    }

    procedenciaExameSelect.addEventListener("change", toggleProcedenciaExame);
    fumanteSelect.addEventListener("change", toggleFumante);
    etilistaSelect.addEventListener("change", toggleEtilista);

    toggleProcedenciaExame();
    toggleFumante();
    toggleEtilista();

});