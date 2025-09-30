<?php

include_once('../../config/db.php');

if(isset($_POST['id'])) {
    $solicitacao_id = $_POST['id'];

    echo $solicitacao_id;

    try {
        $query =
                "UPDATE
                    SolicitacaoCadastro
                SET
                    status = 'revogado'
                WHERE
                    SolicitacaoCadastro_id = :SolicitacaoCadastro_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':SolicitacaoCadastro_id', $solicitacao_id, PDO::PARAM_INT);

        if($stmt->execute()) {
            echo
                "<script>
                    alert('Acesso revogado com sucesso.');
                </script>";
            exit();
        }
    } catch(Exception $e) {
        echo
            "<script>
                alert('Erro ao acessar o banco de dados: " . $e->getMessage() ."');
                window.location.href = '../visualizar/visualizar_users.php';
            </script>";
        exit();
    }
}

?>