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
    <title>Comenzar el proceso de Registro</title>
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
    <meta name="google" content="notranslate">
    <link rel="stylesheet" href="../css/iniciar.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/social.css">
    <link rel="stylesheet" href="../css/bt.css">
    <link rel="stylesheet" href="../css/close.css">
    <link rel="stylesheet" href="../css/rm_cart.css">
    <link rel="stylesheet" href="../css/pedido.css">
    <link rel="stylesheet" href="../css/iniciarPedido.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">
</head>
<body>
    <?php
        include '../CarritoPedido/cart.php';

        if(isset($_SESSION['id_cliente'])){
            include '../CarritoPedido/pedido.php';
        }else{
            include '../CarritoPedido/pedidoSesion.php';
        }
    ?>
    
    <nav>
        <?php
            include '../html/nav.html';
        ?>

    </nav>
    

    <main>
        <div class="container">
            <div class="modal">
                <div class="modal-container card-body">
                    <div class="modal-left">
                    
                            <form method="POST" action="./IniciarSesion.php">
                                <h1 class="modal-title" style="font-size: 40px;">REGISTRATE</h1>

                                <div class="enter">
                                    <div class="position">
                                        <div class="group">
                                                <input required type="text" class="input" name="nombre">
                                                <span class="highlight"></span>
                                                <span class="bar"></span>
                                                <label>Nombre (s)</label>
                                        </div>
                                        <div class="group">
                                                <input required type="text" class="input" name="apellido">
                                                <span class="highlight"></span>
                                                <span class="bar"></span>
                                                <label>Apellidos</label>
                                        </div>
                                    </div>

                                    <div class="group">
                                        <input required type="text" class = "input" pattern="[0-9]{10}"  name="telefono" title="El teléfono debe tener exactamente 10 digitos numericos"  oninput="digitos(this)">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Teléfono</label>
                                        <?php if(isset($_SESSION['error']['telefono'])): ?>
                                            <div class= "errores" style="color: red;"><?php echo $_SESSION['error']['telefono']; ?></div>
                                            <?php unset($_SESSION['error']['telefono']); ?> 
                                        <?php endif; ?>
                                    </div>

                                    <script>
                                        function digitos(input) {
                                        
                                            input.value = input.value.replace(/\D/g, '');

                                            if (input.value.length > 10) {
                                                input.value = input.value.substring(0, 10);  
                                            }
                                        }

                                    </script>


                                    <div class="group">
                                        <input required type="text" class="input" name="username">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Usuario</label>
                                         <!-- Mostrar mensaje de error -->
                                        <?php if (isset($_SESSION['error']['username'])): ?>
                                            <div class= "errores" style="color: red;"><?php echo $_SESSION['error']['username']; ?></div>
                                            <?php unset($_SESSION['error']['username']); ?> 
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="group">
                                        <input required type="email" class="input" name="email" title="Ingrese su correo.">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Correo electrónico</label>
                                        <?php if(isset($_SESSION['error']['email'])): ?>
                                            <div class= "errores" style="color: red;">
                                                <?php echo $_SESSION['error']['email']; ?>
                                            </div>
                                            <?php unset($_SESSION['error']['email']); ?> 
                                        <?php endif; ?>
                                    </div>

                                    <div class="position">
                                        <div class="contrasenia">
                                            <div class="group">
                                                <input required type="password" class="input" name="password" id="password">
                                                <span class="highlight"></span>
                                                <span class="bar"></span>
                                                <label>Contraseña</label>
                                                
                                            </div>
                                            
                                            <div>
                                                <i class="bi bi-eye" id="togglePassword" style="cursor: pointer;"></i>
                                                
                                            </div>
                                        </div>
                                        <div class="contrasenia">
                                            <div class="group">
                                                <input required type="password" class="input" name="verificar_password" id="password2">
                                                <span class="highlight"></span>
                                                <span class="bar"></span>
                                                <label>Corfirmar Contraseña</label>
                                            </div>
                                            <div>
                                                <i class="bi bi-eye" id="togglePassword2" style="cursor: pointer;"></i>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="ingresar">
                                    <!-- <button type="submit" class="styled-button">Ingresar</button> -->
                                    <button class="boton-signup"  type="submit">
                                        <div class="svg-wrapper-1">
                                            <div class="svg-wrapper">
                                                <svg
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
                                        <span>Confirmar</span>
                                    </button>
                                </div>
                            </form>
                    </div>
                </div>
                
            </div>
            
        </div>


        <br><br><br><br><br><br><br><br><br><br><br>
    </main>

    <?php
        include '../html/footer.html';
    ?>

    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>
    <script src="../js/iniciar.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/stock.js"></script>
    <script src="../js/cart-stock.js"></script>
</body>
</html>