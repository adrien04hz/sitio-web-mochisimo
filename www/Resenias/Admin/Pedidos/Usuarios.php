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

$usuario = $_POST['usuario'];  

// Consulta de pedidos pendientes para el usuario
$queryUsuarios = "SELECT 
        Pedido.id AS id,
        Pedido.id_cliente AS id_cliente,
        Pedido.id_direccion AS id_direccion,
        DATE(Pedido.fecha) AS fecha,
        Pedido.monto AS monto,
        Pedido.estado AS estado 
    FROM 
        Pedido
    JOIN 
        Clientes ON Clientes.id = Pedido.id_cliente
    WHERE 
        Pedido.estado = 0 AND Clientes.id = :usuario";
$stmt = $pdo->prepare($queryUsuarios);
$stmt->bindParam(':usuario', $usuario, PDO::PARAM_INT);
$stmt->execute();

$respuestaUser = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  
    $idPendiente = $row['id'];
    $idDireccion = $row['id_direccion'];
    $fecha = $row['fecha'];
    $monto = $row['monto'];

    // Segunda consulta para obtener detalles de los productos en el pedido
    $queryProd = "
        SELECT 
            detallesProd.nombre AS producto_name, 
            imagenDetalle.imagen AS prod_imagen, 
            detalles.cantidad AS seleccionados,
            Direccion.direccion AS entrega
        FROM
            Direccion, Pedido,
            detalles, detallesProd,
            prod_imgDet, imagenDetalle
        WHERE
            Pedido.id = :idPendiente 
            AND Pedido.id_direccion = :idDireccion 
            AND Pedido.id_direccion = Direccion.id
            AND Pedido.id = detalles.id_pedido 
            AND detalles.id_prod = detallesProd.id 
            AND detallesProd.id = prod_imgDet.id_prod 
            AND prod_imgDet.id_img = imagenDetalle.id;
    ";
    
    $stmtProd = $pdo->prepare($queryProd);
    $stmtProd->bindParam(':idPendiente', $idPendiente, PDO::PARAM_INT);
    $stmtProd->bindParam(':idDireccion', $idDireccion, PDO::PARAM_INT);
    $stmtProd->execute();

    $productosPedido = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

    // Si existen productos en el pedido, mostrar los detalles
    if ($productosPedido) {
        $lugarDeEntrega = $productosPedido[0]['entrega'];
        
        // Generación de la tarjeta del pedido
        $respuestaUser .= "<div class='pedido-card-usuario'>";
        $respuestaUser .= "<div class='datos-pedidos-usuario'>";
        $respuestaUser .= "<p>Número de pedido: $idPendiente</p>";
        $respuestaUser .= "<p>Fecha: $fecha</p>";
        $respuestaUser .= "<p>Entrega: $lugarDeEntrega</p>";
        $respuestaUser .= "</div>";

        $respuestaUser .= "<div class='detalles-pedido-contenedor-usuario'>";
        $respuestaUser .= "<div class='titulo-detalles-pedido-usuario'><p>Detalles del pedido:</p></div>";
        $respuestaUser .= "<div class='items-pedido-detalles-usuario'>";

        // Mostrar los productos del pedido
        foreach ($productosPedido as $producto) {
            $respuestaUser .= "<div class='item-detalle-pedido-usuario'>";
            $respuestaUser .= "<div class='img-item-pedido-usuario'>";
            $respuestaUser .= "<img src='data:image/jpeg;base64," . base64_encode($producto['prod_imagen']) . "' alt='" . htmlspecialchars($producto['producto_name']) . "'>";
            $respuestaUser .= "</div>";
            $respuestaUser .= "<div class='datos-item-pedido-detalle-usuario'>";
            $respuestaUser .= "<h3>" . htmlspecialchars($producto['producto_name']) . "</h3>";
            $respuestaUser .= "<p>Cantidad: " . $producto['seleccionados'] . " pzs.</p>";
            $respuestaUser .= "</div>";
            $respuestaUser .= "</div>";
        }
        $respuestaUser .= "</div></div>";

        $respuestaUser .= "<div class='estado-pedido-usuario'>";
        $respuestaUser .= "<p class='total-pago'>Por pagar: $" . number_format($monto, 2) . "</p>";
        $respuestaUser .= "<form action='Admin/Pedidos/ActEstado.php' method='POST'>";
        $respuestaUser .= "<input style='display=none;' type='text' name='id_estado' value='" . htmlspecialchars($row['id']) . "'>";
        //$respuestaUser .= "<p>ID Pedido: <strong>" . htmlspecialchars($row['id']) . "</strong></p>";
        $respuestaUser .= "<button type='submit' class='boton-estado'>Estado: Pendiente</button>";
        $respuestaUser .= "</form>";
        
        $respuestaUser .= "</div></div>";

    } else {
        $respuestaUser .= "<p>No hay productos en este pedido.</p>";
    }
}

// Mostrar los resultados obtenidos
echo $respuestaUser;

?>           
