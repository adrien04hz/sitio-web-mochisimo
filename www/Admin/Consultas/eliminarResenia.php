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
   $id = $_POST['id_resenia'];
   
  
  
    $eliminarResenia = $pdo->prepare("DELETE FROM Resenia WHERE id = ?");
    $eliminarResenia->execute([$id]);
  
   header("Location: /Resenias/Resenias.php");
   exit();
?>