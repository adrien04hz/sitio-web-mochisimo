<?php
/*
paso 1: a partir del carrito llenar la nueva tabla de productos, incluyendo las imagenes cuidando que no haya productos repetidos, si se produce error de que ya se encuentra ese producto, solo se actualiza los datos excepto el nombre
paso 2: llenar la tabla de pedidos con el nuevo pedido
paso 3: vaciar la tabla de carrit en el de detalles incluyendo el id del pedido.
paso 4: se vacia la tabla de carrito

*/ 
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

    if(isset($_SESSION['id_cliente'])){
        $idCliente = $_SESSION['id_cliente'];
    }

    $queryEntregados = "SELECT 
                            Pedido.id as id ,Pedido.id_cliente as id_cliente, 
                            Pedido.id_direccion as id_direccion, 
                            DATE(Pedido.fecha) as fecha, 
                            Pedido.monto as monto, 
                            Pedido.estado as estado 

                        FROM 
                            Pedido,Clientes

                        WHERE 
                            estado=1 AND Clientes.id = $idCliente AND Clientes.id = Pedido.id_cliente";


    $queryPendientes = "SELECT 
                            Pedido.id as id ,Pedido.id_cliente as id_cliente, 
                            Pedido.id_direccion as id_direccion, 
                            DATE(Pedido.fecha) as fecha, 
                            Pedido.monto as monto, 
                            Pedido.estado as estado 

                            FROM 
                                Pedido,Clientes

                            WHERE 
                                estado=0 AND Clientes.id = $idCliente AND Clientes.id = Pedido.id_cliente";

    $st = $pdo->query($queryEntregados);
    $pedidosEntregados = $st->fetchAll(PDO::FETCH_ASSOC);


    $st2 = $pdo->query($queryPendientes);
    $pedidosPendientes = $st2->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="../css/misPedidos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>
    <title>Mis pedidos</title>
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
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
        <div class="pedidos-contenedor-principal">
            <div class="saludo-cliente">
                <h1>¡HOLA <?php if(isset($_SESSION['name'])){ echo $_SESSION['name'];} ?>!</h1>
                <p>Bienvenido a tus pedidos</p>
            </div>

            <div class="pedidos-pendientes">
                <div class="titulo-pendientes2">
                    <h2>Pendientes:</h2>
                </div>

                <div class="pendientes-contenedor">
                    <?php
                        if($pedidosPendientes){
                            foreach($pedidosPendientes as $fila){
                                $idPendiente = $fila['id'];
                                $idDireccion = $fila['id_direccion'];
                                $fecha = $fila['fecha'];

                                $queryProd = "
                                    SELECT 
                                        detallesProd.nombre as producto_name, 
                                        imagenDetalle.imagen as prod_imagen, 
                                        detalles.cantidad as seleccionados,
                                        Direccion.direccion as entrega

                                    FROM
                                        Direccion, detallesProd,
                                        detalles, Pedido,
                                        prod_imgDet, imagenDetalle
                                        
                                    WHERE
                                        Pedido.id = $idPendiente AND Pedido.id_direccion = $idDireccion AND Pedido.id_direccion = Direccion.id
                                        AND Pedido.id = detalles.id_pedido AND detalles.id_prod = detallesProd.id AND 
                                        detallesProd.id = prod_imgDet.id_prod AND prod_imgDet.id_img = imagenDetalle.id;
                                
                                ";

                                $stt = $pdo->query($queryProd);
                                $productosPedido = $stt->fetchAll(PDO::FETCH_ASSOC);


                                $lugarDeEntrega = $productosPedido[0]['entrega'];
                                // empieza la tarjeta del pedido
                                echo "
                                            <!-- empieza pedido card -->
                                            <div class='pedido-card'>
                                                <div class='datos-miPedido'>
                                                    <p>Número de pedido: $idPendiente</p>
                                                    <p>Fecha: $fecha</p>
                                                    <p>Entrega: $lugarDeEntrega</p>
                                                </div>

                                                <div class='detalles-pedido-contenedor'>
                                                    <div class='titulo-detalles-pedido'>
                                                        <p>Detalles del pedido:</p>
                                                    </div>";
                                
                                echo "<div class='items-pedido-detalles'>";

                                foreach($productosPedido as $row){
                                    echo "                                
                                            <!-- inicia item -->
                                            <div class='item-detalle-pedido'>
                                                <div class='img-item-pedido'>
                                                    <div class='img-item-pedido2'>
                                                        <img src='data:image/jpeg;base64," . base64_encode($row['prod_imagen']) ."' alt='" . $row['producto_name'] ."'>"."
                                                    </div>
                                                </div>

                                                <div class='datos-item-pedido-detalle'>
                                                    <h3>". $row['producto_name'] ."</h3>
                                                    <p>Cantidad: ". $row['seleccionados'] ." pzs.</p>
                                                </div>
                                            </div>
                                            <!-- termina item -->";
                                }
                                echo "</div></div>";

                                echo "<div class='estado-pedido'>
                                        <p>Estado: Pendiente</p>
                                        <p>Por pagar: $". $fila['monto'].".00</p>
                                    </div>
                                </div>";
                            }
                        }else{
                            echo "No hay pedidos pendientes.";
                        }
                    ?>           

                </div>
            </div>

            <!-- pedidos ya entregados -->
            <div class="pedidos-pendientes">
                <div class="titulo-pendientes">
                    <h2>Recibidos:</h2>
                </div>

                <div class="pendientes-contenedor">
                    <?php
                        if($pedidosEntregados){
                            foreach($pedidosEntregados as $fila){
                                $idPendiente = $fila['id'];
                                $idDireccion = $fila['id_direccion'];
                                $fecha = $fila['fecha'];

                                $queryProd = "
                                    SELECT 
                                        detallesProd.nombre as producto_name, 
                                        imagenDetalle.imagen as prod_imagen, 
                                        detalles.cantidad as seleccionados,
                                        Direccion.direccion as entrega

                                    FROM
                                        Direccion, Pedido,
                                        detalles, detallesProd,
                                        prod_imgDet, imagenDetalle
                                        
                                    WHERE
                                        Pedido.id = $idPendiente AND Pedido.id_direccion = $idDireccion AND Pedido.id_direccion = Direccion.id
                                        AND Pedido.id = detalles.id_pedido AND detalles.id_prod = detallesProd.id AND 
                                        detallesProd.id = prod_imgDet.id_prod AND prod_imgDet.id_img = imagenDetalle.id;
                                
                                ";

                                $stt = $pdo->query($queryProd);
                                $productosPedido = $stt->fetchAll(PDO::FETCH_ASSOC);


                                $lugarDeEntrega = $productosPedido[0]['entrega'];
                                // empieza la tarjeta del pedido
                                echo "
                                            <!-- empieza pedido card -->
                                            <div class='pedido-card2'>
                                                <div class='datos-miPedido'>
                                                    <p>Número de pedido: $idPendiente</p>
                                                    <p>Fecha: $fecha</p>
                                                    <p>Entrega: $lugarDeEntrega</p>
                                                </div>

                                                <div class='detalles-pedido-contenedor'>
                                                    <div class='titulo-detalles-pedido'>
                                                        <p>Detalles del pedido:</p>
                                                    </div>";
                                
                                echo "<div class='items-pedido-detalles'>";

                                foreach($productosPedido as $row){
                                    echo "                                
                                            <!-- inicia item -->
                                            <div class='item-detalle-pedido'>
                                                <div class='img-item-pedido'>
                                                    <div class='img-item-pedido2'>
                                                        <img src='data:image/jpeg;base64," . base64_encode($row['prod_imagen']) ."' alt='" . $row['producto_name'] ."'>"."
                                                    </div>
                                                </div>

                                                <div class='datos-item-pedido-detalle'>
                                                    <h3>". $row['producto_name'] ."</h3>
                                                    <p>Cantidad: ". $row['seleccionados'] ." pzs.</p>
                                                </div>
                                            </div>
                                            <!-- termina item -->";
                                }
                                echo "</div></div>";

                                echo "<div class='estado-pedido'>
                                        <p>Estado: Recibido</p>
                                        <p>Pagado: $". $fila['monto'].".00</p>
                                    </div>
                                </div>";
                            }
                        }else{
                            echo "No hay pedidos recibidos.";
                        }
                    ?>           

                </div>
            </div>
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
</body>
</html>