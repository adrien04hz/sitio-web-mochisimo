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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el usuario está autenticado
    if (isset($_SESSION['id_cliente'])) {
        $id_cliente = $_SESSION['id_cliente'];

        // Obtener el comentario y la calificación desde el formulario
        $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
        $calificacion = isset($_POST['calificacion']) ? intval($_POST['calificacion']) : 0;

        // Validar que los campos no estén vacíos
        if (empty($comentario)) {
            die("El comentario no puede estar vacío.");
        }

        if ($calificacion < 1 || $calificacion > 5) {
            die("La calificación debe ser entre 1 y 5.");
        }

        // Insertar la reseña en la base de datos
        $insertarResenia = $pdo->prepare("INSERT INTO Resenia (id_cliente, comentario, calificacion) VALUES (?, ?, ?)");
        $insertarResenia->execute([$id_cliente, $comentario, $calificacion]);

        echo "Reseña guardada con éxito.";
    } else {
        echo "Debes iniciar sesión para dejar una reseña.";
    }
}
?>