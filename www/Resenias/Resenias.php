<?php
error_reporting(E_ALL & ~E_WARNING);

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

    //vamos a hacer consultas de si ya se realizo un pedido o si ya se realizo una resena
    $id_cliente = $_SESSION['id_cliente'] ?? null;
    $compraExistente = 0;
    $reseniaExistente = 0;
    $mensajeError = "";

   //aqui si es que ya ha dejado una resena o ha realizado un pedido
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

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['escribir_resenia'])) {
        if (!$id_cliente) {
            $mensajeError = "Debes iniciar sesión para escribir una reseña.";
        } elseif ($compraExistente == 0) {
            $mensajeError = "Debes realizar una compra antes de dejar una reseña.";
        } elseif ($reseniaExistente > 0) {
            $mensajeError = "Ya has dejado una reseña. ¡Gracias por tu aporte!";
        } else {
            // Si no hay errores, redirigir al formulario de reseñas
            header("Location: AgregarResenia.php");
            exit;
        }
    }

    

    // $queryResenias = "SELECT Re.id_cliente, Re.comentario, Re.calificacion, Cl.email, Cl.username FROM Resenia AS Re INNER JOIN Clientes Cl ON Re.id_cliente= Cl.id";
    // $stmt = $pdo->query($queryResenias);
    // $resenias = $stmt->fetchAll(PDO::FETCH_ASSOC);




//consulta para ver las categorias
$query = "SELECT * FROM Categorias";

$stmt = $pdo->query($query);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$queryResenias = "SELECT Re.id, Re.id_cliente, Re.comentario, Re.calificacion, Cl.email, Cl.username FROM Resenia AS Re INNER JOIN Clientes Cl ON Re.id_cliente= Cl.id";
$stmt = $pdo->query($queryResenias);
$resenias = $stmt->fetchAll(PDO::FETCH_ASSOC);

//consulta para ver las categorias
$query = "SELECT * FROM Categorias ORDER BY nombre";
//consulta de producto 

$stmt = $pdo->query($query);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);



$st = $pdo->query("SELECT id,id_cliente,id_direccion, DATE(fecha) as fecha, monto, estado FROM Pedido WHERE estado=1");
    $pedidosEntregados = $st->fetchAll(PDO::FETCH_ASSOC);
$st2 = $pdo->query("SELECT id,id_cliente,id_direccion, DATE(fecha) as fecha, monto, estado FROM Pedido WHERE estado=0");
$pedidosPendientes = $st2->fetchAll(PDO::FETCH_ASSOC);


///consulta de usuarios

$queryUsuarios = "SELECT Clientes.id, Clientes.nombre as nombre, Clientes.apellido as apellido FROM Clientes;";
$stmt3 = $pdo->query($queryUsuarios);
$clientes = $stmt3->fetchAll(PDO::FETCH_ASSOC);

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <link rel="stylesheet" href="../css/prin_resenias.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/social.css">
    <link rel="stylesheet" href="../css/bt.css">
    <link rel="stylesheet" href="../css/close.css">
    <link rel="stylesheet" href="../css/rm_cart.css">
    <link rel="stylesheet" href="../css/pedido.css">
    <link rel="stylesheet" href="../css/iniciarPedido.css">
    <link rel="stylesheet" href="../css/pedidoHecho.css">

    <link rel="stylesheet" href="../css/admin/agregar.css">
    <link rel="stylesheet" href="../css/admin/resenias.css">
    
    <link rel="stylesheet" href="../css/admin/pedidos_actuales.css">
    <link rel="stylesheet" href="../css/admin/pedidos_entregados.css">
    <link rel="stylesheet" href="../css/admin/estadoPedidos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>
    <title>Apartado de Reseñas</title>
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
</head>

<body <?php echo isset($_SESSION['admin_id']) ? 'class="admin-layout"' : ''; ?>>
   
    <?php
        if(isset($_GET['compra']) && $_GET['compra'] == 1){
            include '../CarritoPedido/modalPedido.php';
        }

        include '../CarritoPedido/cart.php';

        if(isset($_SESSION['id_cliente'])){
            include '../CarritoPedido/pedido.php';
        }else{
            include '../CarritoPedido/pedidoSesion.php';
        }
    ?>

    <!-- <a href="#ctgs">
        <button class="Btn">
            <svg height="1.2em" class="arrow" viewBox="0 0 512 512">
                <path d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"></path>
            </svg>
            <p class="text">Categorías</p>
        </button>
    </a> -->
    <?php if(isset($_SESSION['id_cliente']) || isset($_SESSION['admin_id'])): ?>
    
        <nav>
            <?php include '../html/nav_user.html'; ?>
        </nav>
    <?php else: ?>
        <nav>
            <?php include '../html/nav.html'; ?>
        </nav>
    <?php endif; ?>

    
    <?php include 'Admin/Menu1.php'; ?>
      

    <main>
        <br><br><br>
        <form action="" method="POST">
            <div class="container">
                <div class="encabezado">
                    <h2>Tu opinión endulza nuestro día, ¡Cuéntanos qué te parecieron nuestros productos o nuestro servicio!</h2>
                    
                
                    <?php if (!isset($_SESSION['id_cliente'])): ?>
                        <?php $_SESSION['mandar_a'] = $_SERVER['REQUEST_URI']; ?>
                        <?php if(!$_SESSION['admin_id']) echo '<a href="../Login/User.php" class="custom-button">Inicia sesión para escribir una reseña</a>' ?>
                    <?php elseif ($_SESSION['compraExistente'] == 0): ?>
                        <p class="mensaje">Hola, <?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?>. Debes realizar una compra antes de dejar una reseña.</p>
                    <?php elseif ($_SESSION['reseniaExistente'] > 0): ?>
                        <p class="mensaje">Hola, <?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?>. Ya has dejado una reseña. ¡Gracias por tu aporte!</p>
                    <?php else: ?>
                        <a href="AgregarResenia.php" class="custom-button">Escribe tu reseña</a>
                    <?php endif; ?>
                </div>

                
            </div>
            
            <div class="envolver">  
                <?php 
                if ($resenias) {
                    foreach ($resenias as $fila) { ?>
                     <div class="resenias">
                        <div class = "contenido">
                            <div class="datos">
                                <div class="ajustar">
                                    <p class="estilizando"><?php echo htmlspecialchars($fila['username']); ?></p>
                                    <!-- <p class="estilizando"><?php //echo $_SESSION['nombre']; ?></p> -->
                                    <?php 
                                        if(isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $fila['id_cliente']): ?>
                          
                                        <div class="menu-container">
                                            <button id="menuResenias" class="menu-btn">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            
                                            <!-- <div class="menu">
                                                <div>
                                                    <form action="Modificar.php" method="POST">
                                                        <a class="modificar" href="Modificar.php?ic=<//?php echo $fila['id_cliente']; ?>">Modificar</a>
                                                    </form>
                                                </div>

                                                <div>
                                                    <form action="Eliminar.php" method="POST">
                                                        <button type="submit" class="delete">Eliminar</button>
                                                    </form>
                                                </div>
                                            </div> -->

                                            <div id="menucito" class="menu">
                                                <div class="modify">
                                                    <a href="Modificar.php?ic= <?php echo $fila['id_cliente']; ?>">Modificar</a>
                                                </div>

                                                <div class="del">
                                                    <a class="delete" href="Eliminar.php">Eliminar</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    <?php endif;?>
                                </div>
            
                                <p class="email"><?php echo nl2br(htmlspecialchars($fila['email'])); ?></p>
                                <!-- <p class="email"><?php //echo $_SESSION['nombre']; ?></p> -->
                                <!-- <p class="email" style="margin-top: -5px;">@<?php //echo htmlspecialchars($fila['username']); ?></p> -->

                                <p class="start">
                                    <?php
                                    
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $fila['calificacion']) {
                                            echo '<i class="fas fa-star"></i>';  
                                        } else {
                                            echo '<i class="far fa-star"></i>';  
                                        }
                                    }
                                    ?>
                                </p>
                            
                                <div class="comentario">
                                    <p><?php echo nl2br(htmlspecialchars($fila['comentario'])); ?></p>
                                </div>
                                <div class="cont">
                                    <div class="img ">
                                        <img src="../images/logo.webp">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        </div>
                    <?php 
                    } 
                } else {
                    echo "<p>No hay reseñas disponibles.</p>";
                }
                ?>
            </div>


        </form>

        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br>
    </main>
    
    <?php include '../html/footer.html'; ?>
    <script src="../js/script.js"></script>
    <script src="../js/cart-stock.js"></script>
    <script src="../js/stock.js"></script>
    <script src="../js/lateral.js"></script>
    <script src="../js/peoductos.js"></script>
</body>
</html>
