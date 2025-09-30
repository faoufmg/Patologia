<?php
include_once('../../config/db.php');

if (isset($_POST['exame_num'])) {
    $exame_num = $_POST['exame_num'];

    $query = "SELECT * FROM Laboratorio WHERE ExameNum = :ExameNum";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ExameNum', $exame_num, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn()) {
        echo 'duplicado';
    } else {
        echo 'ok';
    }
}
?>