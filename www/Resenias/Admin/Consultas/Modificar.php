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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productoId = $_SESSION['id_producto'];
    $nuevaCategoria = $_POST['nueva_categoria'];
    $nuevoProducto = $_POST['nuevo_producto'] ;
    $nuevaDescripcion = $_POST['nueva_descripcion'];
    $nuevoPrecio = $_POST['nuevo_precio'];
    $nuevaFrase = $_POST['nueva_frase'];
    $nuevoStock = $_POST['nuevo_stock'] ;

    if (isset($_FILES['nuevaImagenCateg']) && $_FILES['nuevaImagenCateg']['error'] == UPLOAD_ERR_OK) {
        $nuevaImagenCateg = file_get_contents($_FILES['nuevaImagenCateg']['tmp_name']);
    } else {
        $nuevaImagenCateg = null;
    }

    if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] == UPLOAD_ERR_OK) {
        $nueva_imagen = file_get_contents($_FILES['nueva_imagen']['tmp_name']);
    } else {
        $nueva_imagen = null;
    }

    try {
        $pdo->beginTransaction();
            if ($nuevaCategoria || $nuevaImagenCateg) {
          
            $stmt = $pdo->prepare("SELECT id FROM Categorias WHERE nombre = ?");
            $stmt->execute([$nuevaCategoria]);
            $categoriaExistenteId = $stmt->fetchColumn();
        
            if ($nuevaCategoria) { 
                if ($categoriaExistenteId) {
                    $categoriaId = $categoriaExistenteId;
                } else {
                    // Si la categoría no existe, la creamos (es necesario la imagen)
                    if (empty($_FILES['nuevaImagenCateg']['name'])) {
                        header("Location: ../../../CatalogoDetalles/catalogo.php?imgnueva_cat=1#modificarProducto");
                        exit(); 
                    }
                    $insertarCateg = $pdo->prepare("INSERT INTO Categorias(nombre, imagen_categ) VALUES (?, ?)");
                    $insertarCateg->execute([$nuevaCategoria, $nuevaImagenCateg]);
                    $categoriaId = $pdo->lastInsertId();
                }
            }
        
            // Aqui si solo se quiere actualizar la imagen de al categoria
            if ($nuevaImagenCateg && !$nuevaCategoria) {
                // Obtener la categoría actual del producto
                $stmt = $pdo->prepare("SELECT id_categ FROM prod_categ WHERE id_prod = ?");
                $stmt->execute([$productoId]);
                $categoriaActualId = $stmt->fetchColumn();
        
                if ($categoriaActualId) {
                    // Actualizar la imagen de la categoría existente
                    $stmt = $pdo->prepare("UPDATE Categorias SET imagen_categ = ? WHERE id = ?");
                    $stmt->execute([$nuevaImagenCateg, $categoriaActualId]);
                } else {
                    die("Error: No se puede actualizar la imagen porque el producto no tiene una categoría asignada.");
                }
            }
        
            if ($nuevaCategoria && isset($categoriaId)) {
                // Obtener la categoría actual del producto
                $stmt = $pdo->prepare("SELECT id_categ FROM prod_categ WHERE id_prod = ?");
                $stmt->execute([$productoId]);
                $categoriaActualId = $stmt->fetchColumn();
        
                if ($categoriaId !== $categoriaActualId) { 
                    // Si el producto tenía una categoría anterior, cambiarlo a la nueva
                    if ($categoriaActualId) {
                        $stmt = $pdo->prepare("UPDATE prod_categ SET id_categ = ? WHERE id_prod = ?");
                        $stmt->execute([$categoriaId, $productoId]);
        
                        
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM prod_categ WHERE id_categ = ?");
                        $stmt->execute([$categoriaActualId]);
                        $productosEnCategoria = $stmt->fetchColumn();
        
                        if ($productosEnCategoria == 0) {
                            // Si no hay productos en la categoría, eliminarla
                            $stmt = $pdo->prepare("DELETE FROM Categorias WHERE id = ?");
                            $stmt->execute([$categoriaActualId]);
                        }
                    } else {
                        // Si no tenía categoría antes, insertarla directamente
                        $stmt = $pdo->prepare("INSERT INTO prod_categ (id_prod, id_categ) VALUES (?, ?)");
                        $stmt->execute([$productoId, $categoriaId]);
                    }
                }
            }
            }
        
            if ($nuevoProducto || $nuevoStock || $nuevaFrase || $nuevaCategoria || $nuevaImagenCateg) {

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
                $stmt = $pdo->prepare("UPDATE stock SET cantidad = cantidad + ? WHERE id_prod = ?");
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
                }
                
            }
            header("Location:../../detalles.php?producto_id=$productoId"); 
            }

        $pdo->commit();
       
        
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

?>
