$(document).ready(function() {
    var Paciente_id = "<?php echo $Paciente_id; ?>";

    $.ajax({
        url: '../../src/models/tabela_dentista.php',
        type: 'POST',
        data: {
            Paciente_id: Paciente_id
        },
        success: function(response) {
            var data = JSON.parse(response);

            // Exibe a tabela com as informações do paciente
            $("#paciente-dados").html(data.paciente);
            $("#paciente-info").show();

            // Exibe a tabela com as informações da lesão
            $("#lesao-dados").html(data.lesao);
            $("#lesao-info").show();

            // Exibe a tabela com as informações do paciente
            $("#macro-dados").html(data.microscopia);
            $("#macro-info").show();

            // Exibe a tabela com as informações da lesão
            $("#micro-dados").html(data.macroscopia);
            $("#micro-info").show();
        },
        error: function(xhr, status, error) {
            alert('Erro ao buscar as informações: ' + error);
        }
    });
});