<?php
include_once('../../config/db.php');

if(isset($_POST['id'])) {
    $acesso_id = $_POST['id'];
    
    try {
        $query =
                "UPDATE
                    AcessoAluno
                SET
                    Ativo = 0, status = 'revogado'
                WHERE
                    AcessoAluno_id = :AcessoAluno_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':AcessoAluno_id', $acesso_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch(Exception $e) {
        echo
            "<script>
                alert('Erro ao acessar o banco de dados: " . $e->getMessage() ."');
                window.location.href = '../pages/funcionario/aprovar_aluno.php';
            </script>";
        exit();
    }
}