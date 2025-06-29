<?php
// Conexión a la BD
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
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/social.css">
    <link rel="stylesheet" href="../css/bt.css">
    <link rel="stylesheet" href="../css/close.css">
    <link rel="stylesheet" href="../css/rm_cart.css">
    <link rel="stylesheet" href="../css/pedido.css">
    <link rel="stylesheet" href="../css/iniciarPedido.css">
    <link rel="stylesheet" href="../css/admin/agregar.css">
    <link rel="stylesheet" href="../css/admin/resenias.css">
    
    <link rel="stylesheet" href="../css/admin/pedidos_actuales.css">
    <link rel="stylesheet" href="../css/admin/pedidos_entregados.css">
    <link rel="stylesheet" href="../css/admin/estadoPedidos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">
    

    
    <title>Catálogo de productos</title>
</head>
<body <?php echo isset($_SESSION['admin_id']) ? 'class="admin-layout"' : ''; ?>>
    <?php
        include '../CarritoPedido/cart.php';

        if(isset($_SESSION['id_cliente'])){
            include '../CarritoPedido/pedido.php';
        }else{
            include '../CarritoPedido/pedidoSesion.php';
        }
    ?>

    <?php include 'Admin/Menu1.php'; ?>

    <a href="#ctgs">
        <button class="Btn">
            <svg height="1.2em" class="arrow" viewBox="0 0 512 512"><path d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"></path></svg>
            <p class="text">Categorías</p>
        </button>
    </a>

    <?php if(isset($_SESSION['id_cliente']) || isset($_SESSION['admin_id'])): ?>
        <nav>
            <?php include '../html/nav_user.html'; ?>
        </nav>
    <?php else: ?>
        <nav>
            <?php include '../html/nav.html'; ?>
        </nav>
    <?php endif; ?>
    

    <main id="ctgs">
        <div class="categ-title">
            
            <div class="ct-title">
                <h2>Categorías</h2>
            </div>

            <div class="categorias">

                <?php
                    foreach($resultados as $fila){
                        echo "<a href='#". $fila['nombre']. "'>";
                        echo "<div class='mochis ctg'>
                                <div class='img-container'>
                                    <img src='data:image/jpeg;base64," . base64_encode($fila['imagen_categ']) ."' alt='Categoria_" . $fila['nombre'] ."'>
                                </div>
                                <div class='ctg-tl'>
                                    <h3>" . $fila['nombre']  ."</h3>
                                </div>
                            </div>";
                        echo "</a>";
                    }
                ?>         
                
            </div>
        </div>
        

        <!-- Muestra los productos por categoria -->
        <?php
            $colores = ["categ", "categ2", "categ3", "categ4", "categ5", "categ6","categ7","categ8","categ9", "categ10"];
            $i = 0;
            //por cada categoria
            foreach($resultados as $categ){
                $cId = $categ['id'];

                $query2 = "SELECT Prod.id AS producto_id, Cat.nombre AS categoria_nombre, Cat.imagen_categ AS categoria_imagen, Prod.precio as precio, Prod.nombre AS producto_nombre, 
                Prod.descripcion, Fra.frase as frase, Img.imagen as imagen_prod, St.cantidad AS stock
                FROM Producto Prod
                INNER JOIN prod_categ Pro_Ca ON Prod.id = Pro_Ca.id_prod AND Pro_Ca.id_categ = $cId
                INNER JOIN Categorias Cat ON Pro_Ca.id_categ = Cat.id
                INNER JOIN prod_frase Pro_Fr ON Prod.id = Pro_Fr.id_prod
                INNER JOIN Frases Fra ON Pro_Fr.id_frase = Fra.id
                INNER JOIN prod_img Pro_Img ON Prod.id = Pro_Img.id_prod
                INNER JOIN Imagenes Img ON Pro_Img.id_img = Img.id
                INNER JOIN stock St ON Prod.id = St.id_prod";
  
                $stmt2 = $pdo->query($query2);
                $resultados2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                echo "<div id='" . $categ['nombre'] . "' class='" . $colores[$i] ."'>";
                echo"<div class='title'>
                    <h1>" . $categ['nombre'] ."</h1>
                    </div><div class='mochi-prod'>";

                //por cada producto
                foreach($resultados2 as $prod){
                    echo "
                    
                        <a href='./detalles.php?producto_id=". $prod['producto_id'] ."'>
                            <div class='prod ctg'>
                                <div class='img-container'>
                                    <img src='data:image/jpeg;base64," . base64_encode($prod['imagen_prod']) ."' alt='" . $prod['producto_nombre'] ."'>
                                </div>
                                <div class='subtitle'>
                                    <h3>" . $prod['producto_nombre']. "</h3>
                                </div>
                                <div class='frase'>
                                    <p><i>" . $prod['frase'] ."</i></p>
                                </div>
                                <div class='price'>
                                    <p>$" . $prod['precio'] .".00</p>
                                </div>
                            </div>
                        </a>
                    
                    ";
                }
                echo "</div></div>";

                $i++;
            }
        ?>

        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br>
    </main>
    
    <?php
        include '../html/footer.html';
    ?>
    <script src="../js/script.js"></script>
    <script src="../js/stock.js"></script>
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>
    <script src="../js/cart-stock.js"></script>
    <script src="../js/lateral.js"></script>
    <script src="../js/peoductos.js"></script>
</body>
</html>