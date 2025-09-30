<?php
include_once('../../config/db.php');

if(isset($_POST['id'])) {
    $acesso_id = $_POST['id'];

    date_default_timezone_set('America/Sao_Paulo');
    $liberado = date('Y-m-d H:i:s');
    
    try {
        $query =
                "UPDATE
                    AcessoAluno
                SET
                    status = 'aprovada', Liberado = :Liberado
                WHERE
                    AcessoAluno_id = :AcessoAluno_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':AcessoAluno_id', $acesso_id, PDO::PARAM_INT);
        $stmt->bindParam(':Liberado', $liberado, PDO::PARAM_STR);
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