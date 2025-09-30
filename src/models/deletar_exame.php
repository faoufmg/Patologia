<?php
include_once('../../config/db.php');

if(isset($_POST['id'])) {
    $SolicitacaoExame_id = $_POST['id'];
    
    try {
        $query =
                "UPDATE
                    SolicitacaoExame
                SET
                    Ativo = 0
                WHERE
                    SolicitacaoExame_id = :SolicitacaoExame_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':SolicitacaoExame_id', $SolicitacaoExame_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch(Exception $e) {
        echo
            "<script>
                alert('Erro ao acessar o banco de dados: " . $e->getMessage() ."');
                window.location.href = '../pages/funcionario/visualizar_exame.php';
            </script>";
        exit();
    }
}