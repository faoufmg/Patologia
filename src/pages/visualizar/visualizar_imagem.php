<?php
// Inclui a configuração do banco de dados
include_once('../../../config/db.php');

// Verifica se o ID foi enviado via POST
if (!isset($_POST['DadosLesao_id'])) {
    die("ID da lesão não fornecido.");
}

$dados_lesao_id = $_POST['DadosLesao_id'];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arquivos da Lesão</title>
    <style>
        /* Estilos atualizados para organizar os diferentes tipos de arquivo */
        body { font-family: sans-serif; }
        .galeria { display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-start; }
        .arquivo-container {
            border: 2px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
        .arquivo-container img {
            max-width: 300px;
            height: auto;
            display: block;
        }
        .arquivo-container iframe {
            width: 350px; /* Largura do visualizador de PDF */
            height: 500px; /* Altura do visualizador de PDF */
            border: none;
        }
        .arquivo-container a {
            display: block;
            padding: 20px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="galeria">
        <?php
        try {
            // A query continua a mesma, pois o SELECT * já busca todas as colunas necessárias
            $query = "SELECT * FROM ImagemLesao WHERE DadosLesao_id = :DadosLesao_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':DadosLesao_id', $dados_lesao_id, PDO::PARAM_INT);
            $stmt->execute();

            $arquivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($arquivos) {
                foreach ($arquivos as $arquivo) {
                    $id_unico_arquivo = $arquivo['ImagemLesao_id'];
                    $tipo_arquivo = $arquivo['TipoImagem']; // Ex: 'image/jpeg' ou 'application/pdf'
                    $caminho_arquivo = $arquivo['CaminhoImagem'];

                    // echo $caminho_arquivo;

                    // Define o endpoint que servirá o arquivo
                    $url_arquivo = 'exibir_imagem.php?id=' . htmlspecialchars($id_unico_arquivo);

                    echo '<div class="arquivo-container">';

                    // NOVO: Lógica para decidir qual tag HTML usar
                    // Verifica se o tipo do arquivo começa com 'image/'
                    if (strpos($tipo_arquivo, 'image/') === 0) {
                        echo '<img src="' . $url_arquivo . '" alt="Imagem da Lesão">';
                    
                    // Verifica se é um PDF
                    } elseif ($tipo_arquivo === 'application/pdf') {
                        echo '<iframe src="' . $url_arquivo . '"></iframe>';
                        echo '<p><a href="' . $url_arquivo . '" target="_blank">Abrir PDF em nova aba</a></p>';

                    // Fallback para qualquer outro tipo de arquivo
                    } else {
                        echo '<a href="' . $url_arquivo . '" download="' . htmlspecialchars(basename($caminho_arquivo)) . '">Baixar: ' . htmlspecialchars(basename($caminho_arquivo)) . '</a>';
                    }

                    echo '</div>';
                }
            } else {
                echo "<p>Nenhum arquivo encontrado para esta lesão.</p>";
            }
        } catch (PDOException $e) {
            die("Erro ao buscar arquivos: " . $e->getMessage());
        }
        ?>
    </div>

</body>
</html>