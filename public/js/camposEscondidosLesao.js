document.addEventListener("DOMContentLoaded", function(){

    // Envolvimento Ósseo
    const envolvimentoOsseo = document.getElementById("envolvimento_osseo");
    const envolvimentoOsseoImgDiv = document.getElementById("envolvimento_osseo_img_div");
    const envolvimentoOsseoImgInput = document.getElementById("envolvimento_osseo_img");

    // Foto Clínica
    const fotoClinica = document.getElementById("foto_clinica");
    const fotoClinicaImgDiv = document.getElementById("foto_clinica_img_div");
    const fotoClinicaImgInput = document.getElementById("foto_clinica_img");

    // Sintomatologia
    const sintomatologia = document.getElementById("sintomatologia");
    const sintomasDiv = document.getElementById("sintomas_div");
    const sintomasInput = document.getElementById("sintomas");

    // Modo de Coleta
    const modoColeta = document.getElementById("modo_coleta");
    const modoColetaOutroDiv = document.getElementById("modo_coleta_outro_div");
    const modoColetaOutroInput = document.getElementById("modo_coleta_outro");

    // Exames de Imagem
    const exameImagem = document.getElementById("exame_imagem");
    const achadosExameImagemDiv = document.getElementById("achados_exame_imagem_div");
    const achadosExameImagemInput = document.getElementById("achados_exame_imagem");

    // Outras divs úteis
    const envolvimentoOsseoDiv = document.getElementById("envolvimento_osseo_div");
    const fotoClinicaDiv = document.getElementById("foto_clinica_div");
    const sintomatologiaDiv = document.getElementById("sintomatologia_div");
    const modoColetaDiv = document.getElementById("modo_coleta_div");
    const exameImageDiv = document.getElementById("exame_imagem_div");

    function showDiv(div) {
        div.style.display = "block";
        setTimeout(() => div.style.opacity = "1", 10);
    }

    function hideDiv(div) {
        div.style.opacity = "0";
        setTimeout(() => div.style.display = "none", 10);
    }

    function toggleEnvolvimentoOsseo() {
        let value = envolvimentoOsseo.value;

        if(value === "Lesão intra-óssea"){
            showDiv(envolvimentoOsseoImgDiv);

            envolvimentoOsseoImgDiv.classList.remove('col-md-6');
            envolvimentoOsseoImgDiv.classList.add('col-md-3');
            envolvimentoOsseoDiv.classList.remove('col-md-6');
            envolvimentoOsseoDiv.classList.add('col-md-3');
        } else{
            hideDiv(envolvimentoOsseoImgDiv);

            envolvimentoOsseoImgDiv.classList.remove('col-md-3');
            envolvimentoOsseoImgDiv.classList.add('col-md-6');
            envolvimentoOsseoDiv.classList.remove('col-md-3');
            envolvimentoOsseoDiv.classList.add('col-md-6');
            envolvimentoOsseoImgInput.removeAttribute("required");
        }
    }

    function toggleFotoClinica() {
        let value = fotoClinica.value;

        if(value === "Sim") {
            showDiv(fotoClinicaImgDiv);

            fotoClinicaImgDiv.classList.remove('col-md-6');
            fotoClinicaImgDiv.classList.add('col-md-3');
            fotoClinicaDiv.classList.remove('col-md-6');
            fotoClinicaDiv.classList.add('col-md-3');
        } else {
            hideDiv(fotoClinicaImgDiv);

            fotoClinicaImgDiv.classList.remove('col-md-3');
            fotoClinicaImgDiv.classList.add('col-md-6');
            fotoClinicaDiv.classList.remove('col-md-3');
            fotoClinicaDiv.classList.add('col-md-6');
            fotoClinicaImgInput.removeAttribute("required");
        }
    }

    function toggleSintomatologiaOutro() {
        let value = sintomatologia.value;

        if(value === "Sintomática"){
            showDiv(sintomasDiv);

            sintomatologiaDiv.classList.remove('col-md-6');
            sintomatologiaDiv.classList.add('col-md-3');
            sintomasDiv.classList.remove('col-md-6');
            sintomasDiv.classList.add('col-md-3');
        } else {
            hideDiv(sintomasDiv);

            sintomatologiaDiv.classList.remove('col-md-3');
            sintomatologiaDiv.classList.add('col-md-6');
            sintomasDiv.classList.remove('col-md-3');
            sintomasDiv.classList.add('col-md-6');
            sintomasInput.removeAttribute("required");
        }
    }

    function toggleModoColetaOutro() {
        let value = modoColeta.value;

        if(value === "Outros") {
            showDiv(modoColetaOutroDiv);
            modoColetaOutroDiv.classList.remove('col-md-6');
            modoColetaOutroDiv.classList.add('col-md-3');
            modoColetaDiv.classList.remove("col-md-6");
            modoColetaDiv.classList.add("col-md-3");
        } else {
            hideDiv(modoColetaOutroDiv);

            modoColetaOutroDiv.classList.remove('col-md-3');
            modoColetaOutroDiv.classList.add('col-md-6');
            modoColetaDiv.classList.remove("col-md-3");
            modoColetaDiv.classList.add("col-md-6");
            modoColetaOutroInput.removeAttribute("required");
        }
    }

    function toggleExameImagemOutro() {
        let value = exameImagem.value;

        if(value === "Sim") {
            showDiv(achadosExameImagemDiv);

            exameImageDiv.classList.remove('col-md-6');
            exameImageDiv.classList.add('col-md-3');
            achadosExameImagemDiv.classList.remove('col-md-6');
            achadosExameImagemDiv.classList.add('col-md-3');
        } else {
            hideDiv(achadosExameImagemDiv);

            exameImageDiv.classList.remove('col-md-3');
            exameImageDiv.classList.add('col-md-6');
            achadosExameImagemDiv.classList.remove('col-md-3');
            achadosExameImagemDiv.classList.add('col-md-6');
            achadosExameImagemInput.removeAttribute("required");
        }
    }

    toggleEnvolvimentoOsseo();
    toggleSintomatologiaOutro();
    toggleModoColetaOutro();
    toggleExameImagemOutro();
    toggleFotoClinica();

    envolvimentoOsseo.addEventListener("change", toggleEnvolvimentoOsseo);
    sintomatologia.addEventListener("change", toggleSintomatologiaOutro);
    modoColeta.addEventListener("change", toggleModoColetaOutro);
    exameImagem.addEventListener("change", toggleExameImagemOutro);
    fotoClinica.addEventListener("change", toggleFotoClinica);
})