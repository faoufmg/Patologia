document.addEventListener("DOMContentLoaded", function() {
    
    // Valores para Formato
    const formatoSelect = document.getElementById("formato");
    const formatoDiv = document.getElementById("formato_div");
    const formatoOutroDiv = document.getElementById("formato_outro_div");
    const formatoOutroInput = document.getElementById("formato_outro");

    // Valores para Superfície
    const superficieSelect = document.getElementById("superficie");
    const superficieDiv = document.getElementById("superficie_div");
    const superficieOutroDiv = document.getElementById("superficie_outro_div");
    const superficieOutroInput = document.getElementById("superficie_outro");

    // Valores para Coloração
    const coloracaoSelect = document.getElementById("coloracao");
    const coloracaoDiv = document.getElementById("coloracao_div");
    const coloracaoOutroDiv = document.getElementById("coloracao_outro_div");
    const coloracaoOutroInput = document.getElementById("coloracao_outro");

    // Valores para Consistência
    const consistenciaSelect = document.getElementById("consistencia");
    const consistenciaDiv = document.getElementById("consistencia_div");
    const consistenciaOutroDiv = document.getElementById("consistencia_outro_div");
    const consistenciaOutroInput = document.getElementById("consistencia_outro");

    // Valor para Revisão de Lâmina
    const revLaminaInput = document.getElementById("rev_lamina");

    function showDiv(div) {
        div.style.display = "block";
        setTimeout(() => div.style.opacity = "1", 10);
    }

    function hideDiv(div) {
        div.style.opacity = "0";
        setTimeout(() => div.style.display = "none", 10);
    }

    function revLamina() {
        let value = revLaminaInput.value;

        if(value === "Sim") {
            document.getElementById("fragmentos").setAttribute("disabled", "true");
            document.getElementById("tecido").setAttribute("disabled", "true");
            document.getElementById("formato").setAttribute("disabled", "true");
            document.getElementById("formato_outro").setAttribute("disabled", "true");
            document.getElementById("superficie").setAttribute("disabled", "true");
            document.getElementById("superficie_outro").setAttribute("disabled", "true");
            document.getElementById("coloracao").setAttribute("disabled", "true");
            document.getElementById("coloracao_outro").setAttribute("disabled", "true");
            document.getElementById("consistencia").setAttribute("disabled", "true");
            document.getElementById("consistencia_outro").setAttribute("disabled", "true");
            document.getElementById("frag_inclusao").setAttribute("disabled", "true");
            document.getElementById("frag_descalcificacao").setAttribute("disabled", "true");
            document.getElementById("tam_macro").setAttribute("disabled", "true");
            document.getElementById("data").setAttribute("disabled", "true");
            document.getElementById("responsavel").setAttribute("disabled", "true");
            document.getElementById("observacoes").setAttribute("disabled", "true");
        } else {
            document.getElementById("fragmentos").removeAttribute("disabled");
            document.getElementById("tecido").removeAttribute("disabled");
            document.getElementById("formato").removeAttribute("disabled");
            document.getElementById("formato_outro").removeAttribute("disabled");
            document.getElementById("superficie").removeAttribute("disabled");
            document.getElementById("superficie_outro").removeAttribute("disabled");
            document.getElementById("coloracao").removeAttribute("disabled");
            document.getElementById("coloracao_outro").removeAttribute("disabled");
            document.getElementById("consistencia").removeAttribute("disabled");
            document.getElementById("consistencia_outro").removeAttribute("disabled");
            document.getElementById("frag_inclusao").removeAttribute("disabled");
            document.getElementById("frag_descalcificacao").removeAttribute("disabled");
            document.getElementById("tam_macro").removeAttribute("disabled");
            document.getElementById("data").removeAttribute("disabled");
            document.getElementById("responsavel").removeAttribute("disabled");
            document.getElementById("observacoes").removeAttribute("disabled");
        }
    }

    function toggleFormatoOutro() {

        let value = formatoSelect.value;

        if(value === "Outro") {
            showDiv(formatoOutroDiv);
            formatoOutroDiv.classList.remove('col-md-6');
            formatoOutroDiv.classList.add('col-md-3');
            formatoDiv.classList.remove('col-md-6');
            formatoDiv.classList.add('col-md-3');
            formatoOutroInput.setAttribute("required", "true");
        } else {
            hideDiv(formatoOutroDiv);
            formatoOutroDiv.classList.remove('col-md-3');
            formatoOutroDiv.classList.add('col-md-6');
            formatoDiv.classList.remove('col-md-3');
            formatoDiv.classList.add('col-md-6');
            formatoOutroInput.removeAttribute("required");
        }
    }

    function toggleSuperficieOutro() {
        let value = superficieSelect.value;

        if(value === "Outro") {
            showDiv(superficieOutroDiv);
            superficieOutroDiv.classList.remove('col-md-6');
            superficieOutroDiv.classList.add('col-md-3');
            superficieDiv.classList.remove('col-md-6');
            superficieDiv.classList.add('col-md-3');
            superficieOutroInput.setAttribute("required", "true");
        } else {
            hideDiv(superficieOutroDiv);
            superficieOutroDiv.classList.remove('col-md-3');
            superficieOutroDiv.classList.add('col-md-6');
            superficieDiv.classList.remove('col-md-3');
            superficieDiv.classList.add('col-md-6');
            superficieOutroInput.removeAttribute("required");
        }
    }

    function toggleColoracaoOutro() {
        let value = coloracaoSelect.value;

        if(value === "Outro") {
            showDiv(coloracaoOutroDiv);
            coloracaoOutroDiv.classList.remove('col-md-6');
            coloracaoOutroDiv.classList.add('col-md-3');
            coloracaoDiv.classList.remove('col-md-6');
            coloracaoDiv.classList.add('col-md-3');
            coloracaoOutroInput.setAttribute("required", "true");
        } else {
            hideDiv(coloracaoOutroDiv);
            coloracaoOutroDiv.classList.remove('col-md-3');
            coloracaoOutroDiv.classList.add('col-md-6');
            coloracaoDiv.classList.remove('col-md-3');
            coloracaoDiv.classList.add('col-md-6');
            coloracaoOutroInput.removeAttribute("required");
        }
    }

    function toggleConsistenciaOutro() {
        let value = consistenciaSelect.value;

        if(value === "Outro") {
            showDiv(consistenciaOutroDiv);
            consistenciaOutroDiv.classList.remove('col-md-6');
            consistenciaOutroDiv.classList.add('col-md-3');
            consistenciaDiv.classList.remove('col-md-6');
            consistenciaDiv.classList.add('col-md-3');
            consistenciaOutroInput.setAttribute("required", "true");
        } else {
            hideDiv(consistenciaOutroDiv);
            consistenciaOutroDiv.classList.remove('col-md-3');
            consistenciaOutroDiv.classList.add('col-md-6');
            consistenciaDiv.classList.remove('col-md-3');
            consistenciaDiv.classList.add('col-md-6');
            consistenciaOutroInput.removeAttribute("required");
        }
    }

    if(revLaminaInput) {
        revLaminaInput.dispatchEvent(new Event('change'));
    }

    toggleFormatoOutro();
    toggleSuperficieOutro();
    toggleColoracaoOutro();
    toggleConsistenciaOutro();
    revLamina();

    formatoSelect.addEventListener("change", toggleFormatoOutro);
    superficieSelect.addEventListener("change", toggleSuperficieOutro);
    coloracaoSelect.addEventListener("change", toggleColoracaoOutro);
    consistenciaSelect.addEventListener("change", toggleConsistenciaOutro);
    revLaminaInput.addEventListener("change", revLamina);

})