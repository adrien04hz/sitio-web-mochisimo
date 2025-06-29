<?php
session_start();

$dsn = "mysql:host=mysql;dbname=my_database;charset=utf8mb4";
$user = "mysql_user";
$password = "mysql_password";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y sanitizar entradas
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    //Depuración: Verificar los datos recibidos 
   // echo "Username: " . $username . "<br>";
   // echo "Email: " . $email . "<br>";
   // echo "Password: " . $password . "<br>";
  


    // Preparar consulta para Admins
    $stmt = $pdo->prepare("SELECT id FROM Admins WHERE username = :username AND email = :email AND password = :password");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $password  
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location:../CatalogoDetalles/catalogo.php");
        exit();
    } 
    $stmt = $pdo->prepare("SELECT id, nombre, apellido, telefono, email FROM Clientes WHERE username = :username AND email = :email AND password = :password");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $password  
    ]);

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

 

    if ($cliente) {
        $_SESSION['id_cliente'] = $cliente['id'];
        $_SESSION['username'] = $username;
        //se añaden datos del cliente
        $_SESSION['email'] = $cliente['email'];
        $_SESSION['nombre'] = $cliente['nombre'] . " " . $cliente['apellido'];
        $_SESSION['name'] = $cliente['nombre'];
        $_SESSION['tel'] = $cliente['telefono'];
        
        if (isset($_SESSION['mandar_a'])) {
            $mandar_a = $_SESSION['mandar_a']; // Guardamos la URL
    
            // Depuración: Ver los valores de las variables
            //echo "Destino guardado en sesión: " . $mandar_a . "<br>";
            unset($_SESSION['mandar_a']); 
            header("Location: " . $mandar_a);
        } else {
            //echo "No hay destino guardado en sesión. Redirigiendo al index...<br>";
            header("Location: ../index.php"); // Página por defecto
        }
    
        exit(); 
    } else {
        echo "no entra carnal";
        //header("Location: User.php");
    }
    
}
?>
