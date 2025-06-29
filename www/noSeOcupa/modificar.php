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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productoId = $_POST['producto_id'];
    $nuevaCategoria = $_POST['nueva_categoria'];
 
  //  $nuevaImagenCateg = $_POST['nuevaImagenCateg'];
    $nuevoProducto = $_POST['nuevo_producto'] ;
    $nuevaDescripcion = $_POST['nueva_descripcion'];
    $nuevoPrecio = $_POST['nuevo_precio'];
    $nuevaFrase = $_POST['nueva_frase'];
    $nuevoStock = $_POST['nuevo_stock'] ;
   // $nueva_imagen = $_POST['nueva_imagen'];

    
     // Manejar imagen de la categoría
    if (isset($_FILES['nuevaImagenCateg']) && $_FILES['nuevaImagenCateg']['error'] == UPLOAD_ERR_OK) {
        //echo "si tienes imagen";
        $nuevaImagenCateg = file_get_contents($_FILES['nuevaImagenCateg']['tmp_name']);
    } else {
        $nuevaImagenCateg = null;
    }


    // Manejar la imagen del producto 
    if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] == UPLOAD_ERR_OK) {
        $nueva_imagen = file_get_contents($_FILES['nueva_imagen']['tmp_name']);
    } else {
        $nueva_imagen = null;
    }

    try {
        // Inicia una transacción
        $pdo->beginTransaction();
        
        if ($nuevaCategoria || $nuevaImagenCateg) {
            // Buscar o insertar nueva frase
            $stmt = $pdo->prepare("SELECT id_categ FROM prod_categ WHERE id_prod = ?");
            $stmt->execute([$productoId]);
            $categoriaId = $stmt->fetchColumn();
            

            if ($categoriaId) {
              /*  $stmt = $pdo->prepare("INSERT INTO Categorias (nombre) VALUES (?)");
                $stmt->execute([$nuevaCategoria]);
                $categoriaId = $pdo->lastInsertId();*/
                if($nuevaCategoria){
                    $stmt = $pdo->prepare("UPDATE Categorias SET nombre = ? WHERE id = ?");
                    $stmt->execute([$nuevaCategoria, $categoriaId]);
                    //echo "Categoria actualizada.\n";
                }
                if($nuevaImagenCateg){
                    $stmt1 = $pdo->prepare("UPDATE Categorias SET imagen_categ = ? WHERE id = ?");
                    $stmt1->execute([$nuevaImagenCateg, $categoriaId]);
                    //echo "Imagen categoria actualizada";
                }

            }else {
                //echo "errorcito";
                
            }

            // Actualizar relación con el producto
            $stmt = $pdo->prepare("UPDATE prod_categ SET id_categ = ? WHERE id_prod = ?");
            $stmt->execute([$categoriaId, $productoId]);
        }

        // Modificar Producto
        if ($nuevoProducto || $nuevaDescripcion || $nuevoPrecio) {
            $queryProducto = "UPDATE Producto SET ";
            $parametro = [];

            if ($nuevoProducto) {
                $queryProducto .= "nombre = ?, ";
                $parametro[] = $nuevoProducto;
            }
            if ($nuevaDescripcion) {
                $queryProducto .= "descripcion = ?, ";
                $parametro[] = $nuevaDescripcion;
            }
            if ($nuevoPrecio) {
                $queryProducto .= "precio = ?, ";
                $parametro[] = $nuevoPrecio;
            }

            $queryProducto = rtrim($queryProducto, ', ') . " WHERE id = ?";
            $parametro[] = $productoId;

            $stmt = $pdo->prepare($queryProducto);
            $stmt->execute($parametro);
        }
        
        // Modificar Frase
        if ($nuevaFrase) {
            // Buscar o insertar nueva frase
            $stmt = $pdo->prepare("SELECT id FROM Frases WHERE frase = ?");
            $stmt->execute([$nuevaFrase]);
            $fraseId = $stmt->fetchColumn();

            if (!$fraseId) {
                $stmt = $pdo->prepare("INSERT INTO Frases (frase) VALUES (?)");
                $stmt->execute([$nuevaFrase]);
                $fraseId = $pdo->lastInsertId();
            }

            // Actualizar relación con el producto
            $stmt = $pdo->prepare("UPDATE prod_frase SET id_frase = ? WHERE id_prod = ?");
            $stmt->execute([$fraseId, $productoId]);
        }

        // Modificar Stock
        if ($nuevoStock) {
            $stmt = $pdo->prepare("UPDATE stock SET cantidad = ? WHERE id_prod = ?");
            $stmt->execute([$nuevoStock, $productoId]);
        }

        
        if ($nueva_imagen) {
            // Verificar si existe una relación entre el producto y una imagen
            $stmt = $pdo->prepare("SELECT id_img FROM prod_img WHERE id_prod = ?");
            $stmt->execute([$productoId]);
            $imagenId = $stmt->fetchColumn();
        
            if ($imagenId) {
                // Si ya existe una imagen asociada, actualizar la imagen
                $stmt = $pdo->prepare("UPDATE Imagenes SET imagen = ? WHERE id = ?");
                $stmt->execute([$nueva_imagen, $imagenId]);
            } else {
                // Si no existe una imagen asociada, lanzar un error o manejar la lógica según tu requerimiento
                ////echo "Error: No se encontró una imagen asociada para este producto.";
            }
        }

        $pdo->commit();
     ///   header("Location: index.php"); // Redirige de vuelta a la página principal
       
        ////echo "Producto eliminado con éxito.";
    } catch (Exception $e) {
        $pdo->rollBack();
       //// echo "Error al eliminar el producto: " . $e->getMessage();
    }
}

//header('Location: index.php');
?>
