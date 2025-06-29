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


    $categoria = $_POST['productos_categ']; 

    $queryProducto = "SELECT p.id, p.nombre AS producto, p.descripcion, p.precio, 
            s.cantidad AS stock, i.imagen, f.frase
        FROM Producto p
        JOIN prod_categ pc ON p.id = pc.id_prod
        JOIN Categorias c ON pc.id_categ = c.id
        LEFT JOIN stock s ON p.id = s.id_prod
        LEFT JOIN prod_img pi ON p.id = pi.id_prod
        LEFT JOIN Imagenes i ON pi.id_img = i.id
        LEFT JOIN prod_frase pf ON p.id = pf.id_prod
        LEFT JOIN Frases f ON pf.id_frase = f.id
        WHERE c.id = $categoria ORDER BY p.nombre ASC";

        $stmt = $pdo->query($queryProducto);


$productos = "";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
 
    $productos .= "<div class='producto-card'>";
    
    // Imagen del producto alineada a la izquierda
    $base64Imagen = base64_encode($row['imagen']);
    $productos .= "<div class='producto-imagen'>";
    $productos .= "<img src='data:image/jpeg;base64," . $base64Imagen . "' alt='Imagen del producto'>";
    $productos .= "</div>";

    // Contenedor de información
    $productos .= "<div class='producto-detalles'>";
    $productos .= "<h3>" . htmlspecialchars($row['producto']) . "</h3>";
    $productos .= "<p><strong>Descripción:</strong> " . htmlspecialchars($row['descripcion']) . "</p>";
    $productos .= "<p><strong>Frase:</strong> " . htmlspecialchars($row['frase']) . "</p>";
    $productos .= "<p><strong>Precio:</strong> $" . htmlspecialchars($row['precio']) . "</p>";
    $productos .= "<p><strong>Stock:</strong> " . htmlspecialchars($row['stock']) . " unidades</p>";

   
    $productos .= "<form action='Admin/Consultas/eliminar.php' method='POST'>";
    $productos .= "<input type='hidden' name='producto_id' value='" . htmlspecialchars($row['id']) . "'>";
    $productos .= "<button type='submit' class='btn-eliminar'>Eliminar</button>";
    $productos .= "</form>";

    $productos .= "</div>"; // Cierre de detalles
    $productos .= "</div>";
}


echo $productos;
?>    
 