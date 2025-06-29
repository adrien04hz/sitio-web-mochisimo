<?php

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


    $categoria = $_POST['categoria_producto']; 


    
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
        
        $respuesta = "<option value='' disabled selected> Seleccionar</option>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  

            $respuesta .= "<option value='".$row['id']."'>" . $row['producto'] . "</option>";

        
        }

    echo json_encode($respuesta);

?>
