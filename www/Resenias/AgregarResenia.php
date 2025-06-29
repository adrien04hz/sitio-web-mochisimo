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

$id_cliente = $_SESSION['id_cliente'] ?? null;
$username = $_SESSION['username'] ?? "Invitado";
$compraExistente = 0;
$reseniaExistente = 0;

if($id_cliente){
    //Verificamos si ya ha realizado un pedido
    $queryCompra= $pdo->prepare("SELECT COUNT(*) FROM Pedido WHERE id_cliente = ?");
    $queryCompra->execute([$id_cliente]);
    $compraExistente = $queryCompra->fetchColumn();
    $_SESSION['compraExistente'] = $compraExistente;

    //Verificamos si ya ha dejado una reseña

    $queryResenia = $pdo->prepare("SELECT COUNT(*) FROM Resenia WHERE id_cliente = ?");
    $queryResenia->execute([$id_cliente]);
    $reseniaExistente = $queryResenia->fetchColumn();
    $_SESSION['reseniaExistente'] = $reseniaExistente;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_cliente) {
    // Obtener el comentario y validar
    $comentario = trim($_POST['comentario'] ?? '');
    if (empty($comentario)) {
        $_SESSION['error']= "El comentario no puede estar vacío.";
    }
    //Aqui obtendremos la calificacion
    $calificacion = isset($_POST['calificacion']) ? (int)$_POST['calificacion'] : 0;
    // Validar que esté en el rango de 1 a 5
    if ($calificacion < 1 || $calificacion > 5) {
        $_SESSION['error'] = "La calificación debe ser entre 1 y 5.";
    }

    /*if(empty($_SESSION['error'])){
        $insertarResenia = $pdo->prepare("INSERT INTO Resenia (id_cliente, comentario, calificacion) VALUES (?, ?, ?)");
        $insertarResenia->execute([$id_cliente, $comentario, $calificacion]);
    
        $_SESSION['mensaje'] = "¡Gracias por tu reseña!";
        header("Location: Resenias.php");
        exit();

    }*/
    if (empty($_SESSION['error'])) {
        if (!$compraExistente) {
            $_SESSION['error'] = "Debes realizar una compra antes de dejar una resena.";
        } elseif ($reseniaExistente) {
            $_SESSION['error'] = "Ya has dejado una reseña. No puedes agregar otra.";
        } else {
            // Insertar la reseña en la base de datos
                $insertarResenia = $pdo->prepare("INSERT INTO Resenia (id_cliente, comentario, calificacion) VALUES (?, ?, ?)");
                $insertarResenia->execute([$id_cliente, $comentario, $calificacion]);

                $_SESSION['mensaje'] = "¡Gracias por tu reseña!";
                header("Location: Resenias.php");
                exit();
        }
    }
    
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/resenias.css">
    <link rel="stylesheet" href="../css/social.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>

    
    <title>Apartado de Reseñas</title>
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
</head>
<body>
   
   
    <main>
        <div class="container">
                <div class="contenido">
                    <div class="datos-usuario">
                        <p><?php echo $_SESSION['username'] ?? 'Invitado'; ?></p>
                        
                            <?php
                            // Mostrar mensaje de error si existe
                            if (isset($_SESSION['error'])) {
                                echo '<br><p style="color: red;">' . $_SESSION['error'] . '</p>'; // Salto de línea antes del mensaje de error
                                unset($_SESSION['error']); // Borrar el error después de mostrarlo
                            }
                            ?>
                    </div>

                    <form action="" method="POST">
                        
                        <div class="start">
                            <input type="radio" name="calificacion" value="5" id="rate-5">
                            <label for="rate-5" class="fas fa-star"> </label>  
                            <input type="radio" name="calificacion" value="4" id="rate-4">
                            <label for="rate-4" class="fas fa-star"> </label>
                            <input type="radio" name="calificacion" value="3" id="rate-3">
                            <label for="rate-3" class="fas fa-star"> </label>  
                            <input type="radio" name="calificacion" value="2" id="rate-2">
                            <label for="rate-2" class="fas fa-star"> </label>
                            <input type="radio" name="calificacion" value="1" id="rate-1">
                            <label for="rate-1" class="fas fa-star"> </label>            
                        </div>
                        <div class="textarea">
                            <textarea name="comentario" placeholder="Escribe tu reseña aquí..."></textarea>
                            <button class="enviar-btn">Enviar Reseña</button>
                        </div>
                    </form>

                    
                </div>

         
        </div>  
    </main>
    
    <script src="../js/script.js"></script>
    

</body>
</html>