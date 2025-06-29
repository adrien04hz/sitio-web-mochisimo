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
        die("Connection failed: " . $e->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <meta name="google" content="notranslate">
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/social.css">
    <link rel="stylesheet" href="../css/rm_cart.css">
    <link rel="stylesheet" href="../css/close.css">
    <link rel="stylesheet" href="../css/pedido.css">
    <link rel="stylesheet" href="../css/bt.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">


    
    
</head>
<body>
    <?php
        include '../CarritoPedido/cart.php';
        include '../CarritoPedido/pedido.php';
    ?>

    <?php if(isset($_SESSION['id_cliente'])): ?>
        <nav>
            <?php include '../html/nav_user.html'; ?>
        </nav>
    <?php else: ?>
        <nav>
            <?php include '../html/nav.html'; ?>
        </nav>
    <?php endif; ?>


    <main>
        <div class="container">
            <div class="modal">
                <div class="modal-container card-body">
                    <div class="modal-left">
                    
                            <form method="POST" action="Admin_Usuario.php">
                                <h1 class="modal-title" style="font-size: 40px;">INICIAR SESIÓN</h1>
                                 

                                <div class="enter">
                                    <div class="group">
                                        <input required type="text" class="input" name="username">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Usuario</label>
                                    </div>
                                    
                                    <div class="group">
                                        <input required type="email" class="input" name="email">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Correo electrónico</label>
                                    </div>
                                    
                                    <div class="ajuste">
                                        <div class="ajuste1">
                                            <div class="group">
                                                <input required type="password" class="input" name="password" id="password">
                                                <span class="highlight"></span>
                                                <span class="bar"></span>
                                                <label>Contraseña</label>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="ajuste2">
                                            <i class="bi bi-eye" id="togglePassword" style="cursor: pointer; font-size:20px;"></i>
                                            
                                        </div> 
                                    </div>
                                    
                                  
                                </div>
                                
                                <div class="ingresar">
                                    <!-- <button type="submit" class="styled-button">Ingresar</button> -->
                                    <button id="entrar_cuenta" class="boton-login" type="submit">
                                    <div class="svg-wrapper-1">
                                        <div class="svg-wrapper">
                                        <svg class="ss"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            width="24"
                                            height="24"
                                        
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path
                                            fill="currentColor"
                                            d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"
                                            ></path>
                                        </svg>
                                        </div>
                                    </div>
                                    <span class="span">Entrar</span>
                                    </button>
                                </div>
                                
                            </form>
                    </div>
                </div>
                
            </div>
            <div class="sign">
                <small>¿No tienes cuenta? <a href="/Registrar/Registrate.php">Registrate aquí.</a></small>
            </div>
        </div>


        <br><br><br><br><br><br><br><br><br><br><br>
    </main>

    <?php
        include '../html/footer.html';
    ?>

    
    <script src="../js/login.js"></script>
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>
    <script src="../js/script.js"></script>
    <script src="../js/stock.js"></script>
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>
    <script src="../js/cart-stock.js"></script>
</body>
</html>