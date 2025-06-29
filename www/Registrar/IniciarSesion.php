<?php

session_start();

// Conexión a la BD
$dsn = "mysql:host=mysql;dbname=my_database;charset=utf8mb4";
$user = "mysql_user";
$password = "mysql_password";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = array();  // Iniciar el array si no existe
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir 
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $verificar_password = $_POST['verificar_password'];
    $email = $_POST['email'];

    // Validar que las contraseñas coincidan
    if ($password !== $verificar_password) {
         echo "Las contraseñas no coinciden. Por favor, intenta de nuevo. o el email y telefono ya han sido registrados antes";
    } else {
       
        $queryUserRegis = $pdo->prepare("SELECT username, email, telefono FROM Clientes WHERE email = ? OR username = ? OR telefono = ?");
        $queryUserRegis->execute([$email, $username, $telefono]);
        $UserExi = $queryUserRegis->fetch(PDO::FETCH_ASSOC);

        if ($UserExi) {
            if ($UserExi['username'] === $username) {
                $_SESSION['error']['username'] = "El nombre de usuario ya está en uso.";

            } if ($UserExi['email'] === $email) {
                $_SESSION['error']['email'] = "El correo electrónico ya está registrado.";

            } if ($UserExi['telefono'] === $telefono) {
                $_SESSION['error']['telefono'] = "El número de teléfono ya está registrado.";
            }

            header("Location:Registrate.php"); 
            exit();
        } 

                $stmt = $pdo->prepare("INSERT INTO Clientes (nombre, apellido, telefono, username, password, email) VALUES (?, ?, ?, ?, ?, ?)");
                
            
                $stmt->execute([$nombre, $apellido, $telefono, $username, $password, $email]);

            
                $lastInsertId = $pdo->lastInsertId();

            
                $_SESSION['id_cliente'] = $lastInsertId;

              header ("Location: ../index.php");
              exit();
            
            echo "TUS DATOS FUERON INGRESADOS CORRECTAMENTE";
        

}}

//echo "no se pudo";
?>
