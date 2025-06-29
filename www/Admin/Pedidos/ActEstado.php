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
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_estado'])) {
    $idPedido = $_POST['id_estado'];

    
    $stmt = $pdo->prepare("UPDATE Pedido SET estado = 1 WHERE id = :idPedido");
    $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);

    $stmt->execute();
    // header(Location: '')
    header("Location: /catalogo.php#estadoPedidos");
    exit();


?>
