<?php
include_once('../../../config/db.php');

$paciente_id = $_POST['Paciente_id'];

try {
    $query =
            "SELECT
                Laudo
            FROM
                Laudos
            WHERE
                Laudos_id = (SELECT MAX(Laudos_id) FROM Laudos WHERE Paciente_id = :Paciente_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdf = $resultado['Laudo'];

    if($pdf) {
        echo
            '<embed src="data:application/pdf;base64,' . base64_encode($pdf) . '" width="100%" height="100%" style="position: absolute; top: 0; left: 0;" />';
    }
} catch(Exception $e) {
    echo
        "<script>
            alert('Erro ao conectar ao banco de dados: " . $e->getMessage() . "');
            window.location.href = '../../pages/professor/visualizar_exames.php';
        </script>";
    exit();
}
?>