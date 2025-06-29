<?php

session_start();

$dsn = "mysql:host=mysql;dbname=my_database;charset=utf8mb4";
$user = "mysql_user";
$password = "mysql_password";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
    $queryResenias = "SELECT Re.id_cliente, Re.comentario, Re.calificacion, Cl.email, Cl.username FROM Resenia AS Re INNER JOIN Clientes Cl ON Re.id_cliente= Cl.id";
    $stmt = $pdo->query($queryResenias);
    $resenias = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>