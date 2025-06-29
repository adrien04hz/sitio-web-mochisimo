<?php
session_start();

// Verificar si el cliente ha iniciado sesión
if (!isset($_SESSION['id_cliente'])) {
    die("Debes iniciar sesión para dejar una reseña.");
}

$id_cliente = $_SESSION['id_cliente']; // Obtener el ID del cliente

// Conectar a la BD
$dsn = "mysql:host=localhost;dbname=my_database;charset=utf8mb4";
$user = "usuario";
$password = "contraseña";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener datos del formulario
$resena = $_POST['resena'];
$calificacion = $_POST['calificacion'];

// Insertar la reseña en la base de datos
$queryInsert = $pdo->prepare("INSERT INTO Resenia (id_cliente, resena, calificacion) VALUES (?, ?, ?)");
$queryInsert->execute([$id_cliente, $resena, $calificacion]);

echo "¡Gracias por tu reseña!";
?>
