<?php
include_once('../../config/db.php');

if(isset($_POST['id'])) {
    $acesso_id = $_POST['id'];
    
    date_default_timezone_set('America/Sao_Paulo');
    $revogado = date('Y-m-d H:i:s');

    try {
        $query =
                "UPDATE
                    AcessoAluno
                SET
                    status = 'revogado', Revogado = :Revogado
                WHERE
                    AcessoAluno_id = :AcessoAluno_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Revogado', $revogado, PDO::PARAM_STR);
        $stmt->bindParam(':AcessoAluno_id', $acesso_id, PDO::PARAM_INT);

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
                window.location.href = '../pages/funcionario/aprovar_aluno.php';
            </script>";
        exit();
    }
}