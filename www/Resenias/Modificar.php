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
    if(isset($_SESSION['id_cliente'])){
        $id_cliente = $_SESSION['id_cliente'];
        
        $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
        $calificacion = isset($_POST['calificacion']) ? intval($_POST['calificacion']) : 0;
        
        if (empty($comentario)) {
            $_SESSION['errorcito']= "El comentario no puede estar vacío.";
        }

        if ($calificacion < 1 || $calificacion > 5) {
            $_SESSION['errorcito'] = "La calificación debe ser entre 1 y 5.";
        }
        if (empty($_SESSION['errorcito'])) {
            $EditarResenia = $pdo->prepare("UPDATE Resenia SET calificacion = ?, comentario = ? WHERE id_cliente= ?");
            $EditarResenia->execute([$calificacion, $comentario, $id_cliente]);
            
            
            header("Location: Resenias.php"); 
            exit(); 
        
        }
        
        
   } else {
       echo "No se puede.";
   }
  

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/modi_resenias.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/social.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>

    
    <title>Apartado de Resenias</title>
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
</head>
<body>
   
    
    <main>
        <div class="container">
            <div class="contenido">
                <div class="datos-usuario">
                    <p>!Hola <?php echo $_SESSION['username'] ?? 'Invitado'; ?></p>
                    
                        <?php
                        // Mostrar mensaje de errorcito si existe
                        if (isset($_SESSION['errorcito'])) {
                            echo '<br><p style="color: red;">' . $_SESSION['errorcito'] . '</p>'; // Salto de línea antes del mensaje de errorcito
                            unset($_SESSION['errorcito']); // Borrar el errorcito después de mostrarlo
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