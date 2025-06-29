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


    $producto = $_POST['producto_categoria'];
    $_SESSION['id_producto'] = $producto;
    // Consulta segura usando PDO::prepare()
    $queryDescripcion = "SELECT Prod.id AS producto_id, Cat.nombre AS categoria, Cat.imagen_categ AS categoria_imagen, Prod.precio, Prod.nombre AS producto, 
              Prod.descripcion, Fra.frase, Img.imagen, St.cantidad AS stock
              FROM Producto Prod
              INNER JOIN prod_categ Pro_Ca ON Prod.id = Pro_Ca.id_prod
              INNER JOIN Categorias Cat ON Pro_Ca.id_categ = Cat.id
              INNER JOIN prod_frase Pro_Fr ON Prod.id = Pro_Fr.id_prod
              INNER JOIN Frases Fra ON Pro_Fr.id_frase = Fra.id
              INNER JOIN prod_img Pro_Img ON Prod.id = Pro_Img.id_prod
              INNER JOIN Imagenes Img ON Pro_Img.id_img = Img.id
              INNER JOIN stock St ON Prod.id = St.id_prod WHERE Prod.id = $producto;";

        $stmt = $pdo->query($queryDescripcion);
        
        $respuesta1 = ""; 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  
            $respuesta1 = "<div class='row' style=' with:100%; border: 1px solid #ddd; padding: 15px; border-radius: 5px; background-color: #f9f9f9;'>";
            $respuesta1 .= "<div style='with:60px; text-align:left;'><strong>Categoria:</strong> <br>" . htmlspecialchars($row['categoria']) . "</div>";
           // $respuesta1 .= "<div class='col-12' style='font-size: 20px; font-weight: bold; text-align: center; margin-bottom: 10px;'>Detalles del Producto</div>";
           $base64Imagen1 = base64_encode($row['categoria_imagen']);
            $respuesta1 .= "<div  style='text-align: center; margin-top: 10px; with:80px'>";
            $respuesta1 .= "<strong>Imagen de Categoria</strong> <br><img src='data:image/jpeg;base64," . $base64Imagen1 . "' alt='Imagen del producto' style='max-width:100%;max-height:120px; border-radius: 5px;'>";
            $respuesta1 .= "</div>";
            
            
            $respuesta1 .= "<div style='with:60px; text-align:left;'><strong>Producto:</strong> <br>" . htmlspecialchars($row['producto']) . "</div>";
            $respuesta1 .= "<div style='with:160px; text-align:justify;'><h4>Descripción:</h4> <br> " . htmlspecialchars($row['descripcion']) . "</div>";
            $respuesta1 .= "<div style='with:120px; text-align:justify;'><strong>Frase:</strong> <br>" . htmlspecialchars($row['frase']) . "</div>";
            $respuesta1 .= "<div style='with:40px; text-align:justify;'><strong>Precio:</strong> <br>$" . htmlspecialchars($row['precio']) . "</div>";
            $respuesta1 .= "<div style='with:40px; text-align:justify;'><strong>Stock:</strong> <br> " . htmlspecialchars($row['stock']) . " unidades</div>";
            
            
           // Convertir la imagen binaria a base64 para poder mostrarla
            $base64Imagen = base64_encode($row['imagen']);
            $respuesta1 .= "<div  style='text-align: center; margin-top: 10px; with:80px'>";
            $respuesta1 .= "<strong>Imagen de Producto</strong> <br><img src='data:image/jpeg;base64," . $base64Imagen . "' alt='Imagen del producto' style='max-width: 100%; max-height:120px; border-radius: 5px;'>";
            $respuesta1 .= "</div>";
            $respuesta1 .= "</div>"; // Cierre de la fila
            
        
        }
        echo json_encode($respuesta1, JSON_UNESCAPED_UNICODE);


?>
