// Obtem o código do exame
function CodigoExame() {
    var paciente = $("#paciente").val();
    var codigoExameInput = $("#cod_exame");

    if(!paciente || paciente === "Selecione o paciente") {
        codigoExameInput.val('Selecione o paciente');
        codigoExameInput.removeClass('bg-warning bg-danger text-white');
        return;
    }

    $.ajax({
        type: 'POST',
        url: '/src/models/informacoes/informacao.php',
        dataType: 'json',
        data: {
            paciente: paciente
        },
        success: function(data) {
            codigoExameInput.val(data.displayText);
            codigoExameInput.removeClass('bg-warning bg-danger text-white');
        },
        error: function(xhr, status, error) {
            alert("Erro: " + error);
            codigoExameInput.val("Erro ao carregar o código do exame.");
            codigoExameInput.removeClass("bg-warning bg-danger text-white");
        },
    });
}

$(document).ready(function () {
    $('#cod_exame').val('Selecione o paciente.');
    $('#paciente').change(CodigoExame);
});