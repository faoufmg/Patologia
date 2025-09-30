<?php
include_once('../../config/db.php');

if (!isset($_POST['SolicitacaoCadastro'])) {
    die("Solicitação inválida.");
}

$solicitacao_id = intval($_POST['SolicitacaoCadastro']); // Sanitização básica

if (isset($_FILES['assinatura']) && $_FILES['assinatura']['error'] == UPLOAD_ERR_OK) {
    $documento = $_FILES['assinatura'];
    $nome = basename($documento['name']);
    $cro = $_POST['cro'];

    // Sanitização do nome do arquivo
    $nome = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $nome); // Substitui caracteres inválidos por "_"
    $nome_documento = uniqid() . "_" . $nome; // Adiciona um ID único ao nome do arquivo

    $tipo_documento = $documento['type'];
    $tamanho_documento = $documento['size'];
    $arquivo_tmp = $documento['tmp_name'];

    // Validação do tipo de arquivo
    $formatos_doc = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    if (!in_array($tipo_documento, $formatos_doc)) {
        echo 
            "<script>
                alert('Tipo de arquivo não permitido.');
                window.location.href = '../pages/add_assinatura.php';
            </script>";
        exit;
    }

    // Validação do tamanho do arquivo (50MB)
    $tamanho_maximo = 50 * 1024 * 1024; // 50MB em bytes
    if ($tamanho_documento > $tamanho_maximo) {
        echo 
            "<script>
                alert('O arquivo é muito grande. O tamanho máximo permitido é 50MB.');
                window.location.href = '../pages/add_assinatura.php';
            </script>";
        exit;
    }

    // Leitura do arquivo binário
    $assinatura = fopen($arquivo_tmp, 'rb');
    $conteudo = fread($assinatura, $tamanho_documento);
    fclose($assinatura);

    // Inserção no banco de dados
    try {
        $query = 
                "INSERT INTO 
                    Assinaturas(Assinatura, AssinaturaNome, AssinaturaTipo, AssinaturaTamanho, SolicitacaoCadastro_id, CRO)
                VALUES
                    (:Assinatura, :AssinaturaNome, :AssinaturaTipo, :AssinaturaTamanho, :SolicitacaoCadastro_id, :CRO)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Assinatura', $conteudo, PDO::PARAM_LOB);
        $stmt->bindParam(':AssinaturaNome', $nome_documento, PDO::PARAM_STR);
        $stmt->bindParam(':AssinaturaTipo', $tipo_documento, PDO::PARAM_STR);
        $stmt->bindParam(':AssinaturaTamanho', $tamanho_documento, PDO::PARAM_INT);
        $stmt->bindParam(':SolicitacaoCadastro_id', $solicitacao_id, PDO::PARAM_INT);
        $stmt->bindParam(':CRO', $cro, PDO::PARAM_INT);
        $stmt->execute();

        echo 
            "<script>
                alert('Assinatura cadastrada com sucesso.');
                window.location.href = '../pages/add_assinatura.php';
            </script>";
    } catch (PDOException $e) {
        echo 
            "<script>
                alert('Erro ao cadastrar assinatura: " . addslashes($e->getMessage()) . "');
                window.location.href = '../pages/add_assinatura.php';
            </script>";
    }
}
?>