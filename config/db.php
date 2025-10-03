<?php
header('Content-Type: text/html; charset=utf-8');

$db = "BDHC";
$host = "localhost";
$user = "odonto";
$password = "qwe321@AZ";

try {
    // Cria a conexão PDO
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
    $pdo = new PDO($dsn, $user, $password);

    // Configurações de erros e exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Comandos para modificar o CHARSET para UTF8
    $pdo->exec("SET NAMES 'utf8'");
    $pdo->exec("SET character_set_connection=utf8");
    $pdo->exec("SET character_set_client=utf8");
    $pdo->exec("SET character_set_results=utf8");

} catch (PDOException $e) {
    // Captura e exibe erros de conexão
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>