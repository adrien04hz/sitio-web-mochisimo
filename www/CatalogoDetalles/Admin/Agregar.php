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

$resultados = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria = $_POST['categoria'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $frases = $_POST['frases'];
    $stock = $_POST['stock'];

    //Manejar imagen de la categoria 
    if (isset($_FILES['imagen_categ']) && $_FILES['imagen_categ']['error'] == UPLOAD_ERR_OK) {
        $imagen_categ = file_get_contents($_FILES['imagen_categ']['tmp_name']);
    } else {
        $imagen_categ = null;
    }

    // Manejar la imagen del producto 
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    } else {
        $imagen = null;
    }

            
    //verificar si el producto existe
    $existeProd = $pdo->prepare("SELECT * FROM Producto WHERE nombre = ?");
    $existeProd->execute([$producto]);
    $siHay = $existeProd->fetch(PDO::FETCH_ASSOC);

    if($siHay){
       // header('Location: index1.php?exist=1');
       header("Location: ../catalogo.php?exist=1#agregarProducto");
       exit();
    }else{
        try {
            $pdo->beginTransaction();
            // Verificar si la categoría ya existe
            $queryCategoriaExistente = $pdo->prepare("SELECT id, imagen_categ FROM Categorias WHERE nombre = ?");
            $queryCategoriaExistente->execute([$categoria]);
            $categoriaExistente = $queryCategoriaExistente->fetch(PDO::FETCH_ASSOC);
            
            if ($categoriaExistente) {
                $categoriaId = $categoriaExistente['id'];
            
                // Si la categoría ya tiene una imagen, mantenerla
                if ($categoriaExistente['imagen_categ']) {
                    $imagen_categ = $categoriaExistente['imagen_categ'];
                }
            
                // Actualizar imagen solo si se proporciona una nueva diferente
                if ($imagen_categ !== null && $imagen_categ !== $categoriaExistente['imagen_categ']) {
                    $queryActualizarImgCateg = $pdo->prepare("UPDATE Categorias SET imagen_categ = ? WHERE id = ?");
                    $queryActualizarImgCateg->execute([$imagen_categ, $categoriaId]);
                }
            } else {
                // Si la categoría es nueva, verificar que se subió una imagen antes de insertarla
                if ($imagen_categ !== null) {
                    $queryCategoria = $pdo->prepare("INSERT INTO Categorias (nombre, imagen_categ) VALUES (?, ?)");
                    $queryCategoria->execute([$categoria, $imagen_categ]);
                    $categoriaId = $pdo->lastInsertId();
                } else {
                    header("Location: ../catalogo.php?img_cat=1#agregarProducto");
                    exit();
                }
            }
            
            
        
            // Insertar el producto
            $queryProducto = $pdo->prepare("INSERT INTO Producto (nombre, precio, descripcion) VALUES (?, ?, ?)");
            $queryProducto->execute([$producto, $precio, $descripcion]);
            $productoId = $pdo->lastInsertId();

            // Relación producto-categoría
            $queryProCat = $pdo->prepare("INSERT INTO prod_categ (id_prod, id_categ) VALUES (?, ?)");
            $queryProCat->execute([$productoId, $categoriaId]);

            // Insertar frase
            $queryFrase = $pdo->prepare("INSERT INTO Frases (frase) VALUES (?)");
            $queryFrase->execute([$frases]);
            $fraseId = $pdo->lastInsertId();

            // Relación producto-frase
            $queryProdFrase = $pdo->prepare("INSERT INTO prod_frase (id_prod, id_frase) VALUES (?, ?)");
            $queryProdFrase->execute([$productoId, $fraseId]);

            // Insertar stock
            $queryStock = $pdo->prepare("INSERT INTO stock (id_prod, cantidad) VALUES (?, ?)");
            $queryStock->execute([$productoId, $stock]);

            // Insertar imagen
            $queryImagen = $pdo->prepare("INSERT INTO Imagenes (imagen) VALUES (?)");
            $queryImagen->execute([$imagen]);
            $imagenId = $pdo->lastInsertId();

            // Relación producto-imagen
            $queryProdImg = $pdo->prepare("INSERT INTO prod_img (id_prod, id_img) VALUES (?, ?)");
            $queryProdImg->execute([$productoId, $imagenId]);

            $pdo->commit();
            header("Location: ../catalogo.php#agregarProducto");
            exit();
    
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error al eliminar el producto: " . $e->getMessage();
        }
    }
   
}
