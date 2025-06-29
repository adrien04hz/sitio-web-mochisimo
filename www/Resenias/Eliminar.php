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
    $id_cliente = $_SESSION['id_cliente'];
   
   //vamos a hacer la eliminacion 
  
    $eliminarResenia = $pdo->prepare("DELETE FROM Resenia WHERE id_cliente = ?");
    $eliminarResenia->execute([$id_cliente]);
    
    header("Location: Resenias.php");
    exit();
?>