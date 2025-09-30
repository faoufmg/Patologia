function CodigoExame() {
    // Obter a data de solicitação e o nome do usuário
    const dataInput = document.getElementById("data_solicitacao").value;
    const nomeInput = document.getElementById("nome_usuario").value;

    if (dataInput && nomeInput) {
        // Formatar a data para ddmmYY
        const data = new Date(dataInput);
        data.setDate(data.getDate() + 1); // Ajusta para o dia correto
        const dia = String(data.getDate()).padStart(2, '0');
        const mes = String(data.getMonth() + 1).padStart(2, '0'); // Mês começa em 0
        const ano = String(data.getFullYear()).slice(-2); // Últimos 2 dígitos do ano

        const codigoData = dia + mes + ano;

        // Obter as 3 primeiras letras do nome do usuário
        const codigoUsuario = nomeInput.substring(0, 3).toUpperCase(); // Converte para letras maiúsculas

        // Gerar três números aleatórios
        const numeroAleatorio = Math.floor(Math.random() * 900) + 100; // Gera um número entre 100 e 999

        // Combinar para formar o código de solicitação
        const codigoSolicitacao = codigoData + codigoUsuario + numeroAleatorio;

        // Atualizar o campo de código de solicitação
        document.getElementById("codigo_solicitacao").value = codigoSolicitacao;
    }
}
