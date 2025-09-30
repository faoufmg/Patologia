document.addEventListener("DOMContentLoaded", function() {

    // Divs para Doença de Sjogren
    const diagnosticoDiv = document.getElementById("diagnostico_div");
    const diagnosticoSelect = document.getElementById("diagnostico_select_div");
    const diagnosticoTexto = document.getElementById("diagnostico_texto_div");
    const areaAmostra = document.getElementById("area_amostra_div");
    const numFocos = document.getElementById("num_focos_div");
    const focusScore = document.getElementById("focus_score_div");
    const grauInflamacao = document.getElementById("grau_inflamacao_div");
    const centrosGerminativos = document.getElementById("centro_germinativo_div");
    const atrofiaAcinar = document.getElementById("atrofia_acinar_div");
    const dilatacaoAcinar = document.getElementById("dilatacao_acinar_div");
    const lesaoLinfoepitelial = document.getElementById("lesao_linfoepitelial_div");
    const dilatacaoDuctal = document.getElementById("dilatacao_ductal_div");
    const fibrose = document.getElementById("fibrose_div");
    const infiltracaoAdiposa = document.getElementById("infiltracao_adiposa_div");

    // Valores dos campos
    const diagnosticoInput = document.getElementById("diagnostico");
    const diagnosticoSelectInput = document.getElementById("diagnostico_select");
    const diagnosticoTextoInput = document.getElementById("diagnostico_texto");
    const areaAmostraInput = document.getElementById("area_amostra");
    const numFocosInput  = document.getElementById("num_focos");
    const focusScoreInput = document.getElementById("focus_score");
    const grauInflamacaoInput = document.getElementById("grau_inflamacao");
    const centrosGerminativosInput = document.getElementById("centros_germinativos");
    const atrofiaAcinarInput = document.getElementById("atrofia_acinar");
    const dilatacaoAcinarInput = document.getElementById("dilatacao_acinar");
    const lesaoLinfoepitelialInput = document.getElementById("lesao_linfoepitelial");
    const dilatacaoDuctalInput = document.getElementById("dilatacao_ductal");
    const fibroseInput = document.getElementById("fibrose");
    const infiltracaoAdiposaInput = document.getElementById("infiltracao_adiposa");

    const allDivs = [
        diagnosticoSelect, diagnosticoTexto, areaAmostra, numFocos, focusScore,
        grauInflamacao, centrosGerminativos, atrofiaAcinar, dilatacaoAcinar,
        lesaoLinfoepitelial, dilatacaoDuctal, fibrose, infiltracaoAdiposa
    ];

    const allInputs = [
        diagnosticoSelectInput, diagnosticoTextoInput, areaAmostraInput, numFocosInput, focusScoreInput,
        grauInflamacaoInput, centrosGerminativosInput, atrofiaAcinarInput, dilatacaoAcinarInput,
        lesaoLinfoepitelialInput, dilatacaoDuctalInput, fibroseInput, infiltracaoAdiposaInput
    ];

    function resetFields() {
        allDivs.forEach(div => {
            div.style.display = 'none';
            div.style.opacity = '0';
            div.className = div.className.replace(/\bcol-md-\d+\b/g, "").trim();
            div.classList.add("col-md-12");
        });

        allInputs.forEach(input => {
            input.value = '';
        });
    }

    function showDiv(div) {
        div.style.display = 'block';
        setTimeout(() => div.style.opacity = "1", 10);
    }

    function hideDiv(div) {
        div.style.opacity = "0";
        setTimeout(() => div.style.display = "none", 10);
    }

    diagnosticoInput.addEventListener("change", function() {
        let value = this.value;

        if(value === "Sialodenite crônica discreta e inespecífica" || value === "Sialodenite crônica linfocítica focal (achados consistentes aos observados em Doença de Sjögren)") {
            showDiv(areaAmostra);
            showDiv(numFocos);
            showDiv(focusScore);
            showDiv(grauInflamacao);
            showDiv(centrosGerminativos);
            showDiv(atrofiaAcinar);
            showDiv(dilatacaoAcinar);
            showDiv(lesaoLinfoepitelial);
            showDiv(dilatacaoDuctal);
            showDiv(fibrose);
            showDiv(infiltracaoAdiposa);

            hideDiv(diagnosticoTexto);

            // diagnosticoDiv.classList.remove('col-md-12');
            // diagnosticoDiv.classList.add('col-md-6');

            areaAmostra.classList.remove('col-md-12');
            areaAmostra.classList.add('col-md-3');

            numFocos.classList.remove('col-md-12');
            numFocos.classList.add('col-md-3');

            focusScore.classList.remove('col-md-12');
            focusScore.classList.add('col-md-3');

            grauInflamacao.classList.remove('col-md-12');
            grauInflamacao.classList.add('col-md-3');

            centrosGerminativos.classList.remove('col-md-12');
            centrosGerminativos.classList.add('col-md-3');

            atrofiaAcinar.classList.remove('col-md-12');
            atrofiaAcinar.classList.add('col-md-3');

            dilatacaoAcinar.classList.remove('col-md-12');
            dilatacaoAcinar.classList.add('col-md-3');

            lesaoLinfoepitelial.classList.remove('col-md-12');
            lesaoLinfoepitelial.classList.add('col-md-3');

            dilatacaoDuctal.classList.remove('col-md-12');
            dilatacaoDuctal.classList.add('col-md-4');

            fibrose.classList.remove('col-md-12');
            fibrose.classList.add('col-md-4');

            infiltracaoAdiposa.classList.remove('col-md-12');
            infiltracaoAdiposa.classList.add('col-md-4');

        } else {
            showDiv(diagnosticoTexto);

            hideDiv(areaAmostra);
            hideDiv(numFocos);
            hideDiv(focusScore);
            hideDiv(grauInflamacao);
            hideDiv(centrosGerminativos);
            hideDiv(atrofiaAcinar);
            hideDiv(dilatacaoAcinar);
            hideDiv(lesaoLinfoepitelial);
            hideDiv(dilatacaoDuctal);
            hideDiv(fibrose);
            hideDiv(infiltracaoAdiposa);
            hideDiv(diagnosticoSelect);

            diagnosticoDiv.classList.remove('col-md-12');
            diagnosticoDiv.classList.add('col-md-6');

            diagnosticoTexto.classList.remove('col-md-12');
            diagnosticoTexto.classList.add('col-md-6');
            
        }

    })

    if(diagnosticoInput) {
        diagnosticoInput.dispatchEvent(new Event("change"));
    }

})