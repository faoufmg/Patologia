document.addEventListener('DOMContentLoaded', () => {
    const campoCep = document.getElementById('cep');
    const campoEndereco = document.getElementById('endereco');
    const campoBairro = document.getElementById('bairro');
    const campoCidadeEstado = document.getElementById('cidade_estado');

    campoCep.addEventListener('blur', function() {
        const cepValue = this.value.replace(/\D/g, '');

        if (cepValue.length === 8) {
            
            // Bloqueia campos enquanto busca
            campoEndereco.value = "Buscando...";
            campoBairro.value = "Buscando...";
            campoCidadeEstado.value = "Buscando...";

            // Envia os dados para o script PHP
            fetch('../../models/buscar_cep.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cep: cepValue })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor.');
                }
                return response.json();
            })
            .then(data => {
                if (data && !data.erro) {
                    campoEndereco.value = data.logradouro || '';
                    campoBairro.value = data.bairro || '';
                    campoCidadeEstado.value = (data.localidade && data.uf) ? `${data.localidade}/${data.uf}` : '';
                } else {
                    campoEndereco.value = "";
                    campoBairro.value = "";
                    campoCidadeEstado.value = "";
                    alert("CEP não encontrado.");
                }
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
                campoEndereco.value = "";
                campoBairro.value = "";
                campoCidadeEstado.value = "";
                alert("Ocorreu um erro ao buscar o CEP.");
            });
        }
    });
});