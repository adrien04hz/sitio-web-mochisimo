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

$blogs = "SELECT id, titulo, resumen, contenido, DATE(fecha) as fecha FROM Blog";


$consultaBlog = $pdo->query($blogs);
$blogs = $consultaBlog->fetchAll(PDO::FETCH_ASSOC);


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
    <link rel="stylesheet" href="../css/blog.css">
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
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['blog_id'])){
            $idBlog = $_POST['blog_id'];
            $blogs1 = "SELECT id, titulo, resumen, contenido, DATE(fecha) as fecha FROM Blog WHERE id=$idBlog";


            $consultaBlog1 = $pdo->query($blogs1);
            $blog1 = $consultaBlog1->fetchAll(PDO::FETCH_ASSOC);

            echo "    <div id='contenidoBlog' class='opaco-blog'></div>";

            echo "    <div id='contenidoSel' class='contenido-blog-seleccionado'>
                        <div class='titulo-fecha-blog'>
                            <div class='titulo-contenido'>
                                <h1>". $blog1[0]['titulo'] ."</h1>
                            </div>
                            <div class='fecha-contenido'>
                                <p>". $blog1[0]['fecha'] ."</p>
                            </div>
                        </div>

                        <div class='contenido-contenido'>
                            <p>". $blog1[0]['contenido'] ."</p>
                        </div>
                    </div>";

        }


        
        include '../CarritoPedido/cart.php';

        if(isset($_SESSION['id_cliente'])){
            include '../CarritoPedido/pedido.php';
        }else{
            include '../CarritoPedido/pedidoSesion.php';
        }
    ?>

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
        <div class="principal-blog">

        <?php



            if($blogs){
                foreach($blogs as $row){
                    echo       "            
                    <!-- empieza nota del blog -->
                    <div class='blog-card'>
                        <div class='fecha-blog'>
                            <p>". $row['fecha'] ."</p>
                        </div>

                        <div class='titulo-blog'>
                            <h1>". $row['titulo'] ."</h1>
                        </div>

                        <div class='resumen-blog'>
                            <p>". $row['resumen'] ."</p>
                        </div>

                        <div class='leer-mas'>
                            <form action='' method='post'>
                                <input name='blog_id' type='text' value='". $row['id']."' style='display: none'>
                                <button id='leerMas' type='submit' class='read-more1'>Leer Mas</button>
                            </form>
                        </div>
                    </div>
                    <!-- termina nota del blog -->";

                }

            }else{
                echo "<p>No se encuentran por el momento articulos disponibles</p>";
            }
        ?>
        </div>

            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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