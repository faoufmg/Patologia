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
    <title>Imagens da Lesão</title>
    <style>
        /* Estilos simples para organizar as imagens */
        body { font-family: sans-serif; }
        .galeria { display: flex; flex-wrap: wrap; gap: 15px; }
        .galeria img { max-width: 300px; height: auto; border: 2px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>

    <!-- <h1>Imagens Associadas à Lesão ID: <?php echo htmlspecialchars($dados_lesao_id); ?></h1> -->

    <div class="galeria">
        <?php
        try {
            // A query continua a mesma
            $query = "SELECT * FROM ImagemLesao WHERE DadosLesao_id = :DadosLesao_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':DadosLesao_id', $dados_lesao_id, PDO::PARAM_INT);
            $stmt->execute();

            // A MUDANÇA PRINCIPAL: Usamos fetchAll para obter todas as linhas
            $imagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($imagens) {
                // Criamos um loop para cada imagem encontrada
                foreach ($imagens as $imagem) {
                    $id_unico_da_imagem = $imagem['ImagemLesao_id']; 

                    // Para cada imagem, criamos uma tag <img>
                    // O 'src' aponta para o nosso novo script 'exibir_imagem.php'
                    echo '<img src="exibir_imagem.php?id=' . htmlspecialchars($id_unico_da_imagem) . '" alt="Imagem da Lesão">';
                }
            } else {
                echo "<p>Nenhuma imagem encontrada para esta lesão.</p>";
            }
        } catch (PDOException $e) {
            die("Erro ao buscar imagens: " . $e->getMessage());
        }
        ?>
    </div>

</body>
</html>