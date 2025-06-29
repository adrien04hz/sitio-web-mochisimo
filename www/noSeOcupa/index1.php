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

$resultados = [];

// Manejador de método POST
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
        header('Location: index1.php?exist=1');
    }else{
        try {
            $pdo->beginTransaction();
        
            //No se estaba considerando si el producto estaba en la misma categoria, ademas de que como tenemos otro componente que es imagen, se considera en la consulta
            /*if ($categoriaExistente) {
                $categoriaId = $categoriaExistente['id'];
            
                // Actualizar la imagen de la categoría si ya existe
                if ($imagen_categ) {
                    $queryActualizarImgCateg = $pdo->prepare("SELECT imagen_categ FROM Categorias WHERE id = ?");
                    $queryActualizarImgCateg->execute([$imagen_categ, $categoriaId]);
                }
            } else {
                // Insertar la categoría con la imagen
                $queryCategoria = $pdo->prepare("INSERT INTO Categorias (nombre, imagen_categ) VALUES (?, ?)");
                $queryCategoria->execute([$categoria, $imagen_categ]);
                $categoriaId = $pdo->lastInsertId();
            }
            */
            // Verificar si la categoría ya existe
            $queryCategoriaExistente = $pdo->prepare("SELECT id, imagen_categ FROM Categorias WHERE nombre = ?");
            $queryCategoriaExistente->execute([$categoria]);
            $categoriaExistente = $queryCategoriaExistente->fetch(PDO::FETCH_ASSOC);

            if ($categoriaExistente) {
                $categoriaId = $categoriaExistente['id'];
                
                // Usar la imagen existente de la categoría si es igual a NULL o no se proporciona una nueva
                $imagen_categ = ($imagen_categ === null || $imagen_categ === $categoriaExistente['imagen_categ']) ? $categoriaExistente['imagen_categ'] : $imagen_categ;

                // Actualizar imagen solo si es diferente
                if ($imagen_categ !== $categoriaExistente['imagen_categ']) {
                    $queryActualizarImgCateg = $pdo->prepare("UPDATE Categorias SET imagen_categ = ? WHERE id = ?");
                    $queryActualizarImgCateg->execute([$imagen_categ, $categoriaId]);
                }
            } else {
                
                if((isset($_GET['img_cat']) && $_GET['img_cat'] == 1) || $imagen_categ != null){
                    // Insertar nueva categoría con imagen
                    $queryCategoria = $pdo->prepare("INSERT INTO Categorias (nombre, imagen_categ) VALUES (?, ?)");
                    $queryCategoria->execute([$categoria, $imagen_categ]);
                    $categoriaId = $pdo->lastInsertId();
                    header('Location: index1.php');
                }else{
                    header('Location: index1.php?img_cat=1');
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
        
            //echo "Producto eliminado con éxito.";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error al eliminar el producto: " . $e->getMessage();
        }
    }
}

    $query = "SELECT Prod.id AS producto_id, Cat.nombre AS categoria_nombre, Cat.imagen_categ AS categoria_imagen, Prod.precio, Prod.nombre AS producto_nombre, 
              Prod.descripcion, Fra.frase, Img.imagen, St.cantidad AS stock
              FROM Producto Prod
              INNER JOIN prod_categ Pro_Ca ON Prod.id = Pro_Ca.id_prod
              INNER JOIN Categorias Cat ON Pro_Ca.id_categ = Cat.id
              INNER JOIN prod_frase Pro_Fr ON Prod.id = Pro_Fr.id_prod
              INNER JOIN Frases Fra ON Pro_Fr.id_frase = Fra.id
              INNER JOIN prod_img Pro_Img ON Prod.id = Pro_Img.id_prod
              INNER JOIN Imagenes Img ON Pro_Img.id_img = Img.id
              INNER JOIN stock St ON Prod.id = St.id_prod";

    $stmt = $pdo->query($query);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserta/modifica producto</title>
    <link rel="stylesheet" href="estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body >
    <div  class="container mt-5">
        <h1 class="text-center"> Productos</h1>

        <div class="row g-5 mt-3">
            <!-- Agregar producto  -->
            <div class="col-md-6">

                <div class="card">
                    <div class="card-header text-center"> Agregar Producto</div>
                    <div class="card-body">

                        <form method="POST" action="" class="row g-3" enctype="multipart/form-data">
                        
                            <div class="col-md-4">
                                <label for="categoria" class="form-label">Nombre Categoria:</label>
                                <input type="text" class="form-control" id="categoria" name="categoria" required>
                            </div>
                            <div class="mb-2">
                                <label for="imagen_categ" class="form-label">Subir Imagen Categoria:</label>
                                

                                <?php
                                    if(isset($_GET['img_cat']) && $_GET['img_cat']==1){
                                        echo "<input type='file' class='form-control' id='imagen_categ' name='imagen_categ' required>";
                                        echo "<small> *Al insertar nueva categoria, selecciona una imagen</small>";
                                    }else{
                                        echo "<input type='file' class='form-control' id='imagen_categ' name='imagen_categ'>";
                                    }
                                ?>
                                
                            </div>
                            <div class="col-md-4">
                                <label for="producto" class="form-label">Nombre Producto:</label>
                                <input type="text" class="form-control" id="producto" name="producto" required>

                                <?php
                                    if(isset($_GET['exist']) && $_GET['exist'] == 1){
                                        echo "<small>*El producto ya existe</small>";
                                    }
                                ?>
                            </div>
                            <div class="col-md-4">
                                <label for="precio" class="form-label">Precio:</label>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio">
                            </div>
                            <div class="col-md-12">
                                <label for="descripcion" class="form-label">Descripción:</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>   
                            </div>
                            <div class="col-md-5">
                                <label for="frases" class="form-label">Frase:</label>
                                <input type="text" class="form-control" id="frases" name="frases">
                            </div>
                            <div class="col-md-2">
                                <label for="stock" class="form-label">Stock:</label>
                                <input type="number" class="form-control" id="stock" name="stock">
                            </div>
                            <div class="mb-2">
                                <label for="imagen" class="form-label">Subir Imagen producto:</label>
                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                            </div>
                        
                            <button type="submit" class="btn btn-primary ">Agregar Producto</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <!-- Formulario para modificar producto -->
                <div class="card">
                    <div class="card-header text-center">Modificar Producto</div>
                        <div class="card-body">
                            <form method="POST" action="modificar.php" class="row g-3" enctype="multipart/form-data">
                                <div class="col-md-4">
                                    <label for="producto_id" class="form-label">ID Producto:</label>
                                    <input type="number" class="form-control" id="producto_id" name="producto_id" required>
                                    <small class="text-muted">* Campo obligatorio para identificar el producto.</small>
                                </div>
                                <div class="col-md-12">
                                    <label for="nuevaImagenCateg" class="form-label">Nueva Imagen:</label>
                                    <input type="file" class="form-control" id="nuevaImagenCateg" name="nuevaImagenCateg" accept="image/*">
                                </div>
                                <div class="col-md-4">
                                    <label for="nueva_categoria" class="form-label">Nueva Categoría:</label>
                                    <input type="text" class="form-control" id="nueva_categoria" name="nueva_categoria">
                                </div>
                                <div class="col-md-4">
                                    <label for="nuevo_producto" class="form-label">Nuevo Nombre:</label>
                                    <input type="text" class="form-control" id="nuevo_producto" name="nuevo_producto">
                                </div>
                                <div class="col-md-12">
                                    <label for="nueva_descripcion" class="form-label">Nueva Descripción:</label>
                                    <textarea class="form-control" id="nueva_descripcion" name="nueva_descripcion" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="nueva_frase" class="form-label">Nueva Frase:</label>
                                    <input type="text" class="form-control" id="nueva_frase" name="nueva_frase">
                                </div>
                                <div class="col-md-4">
                                    <label for="nuevo_precio" class="form-label">Nuevo Precio:</label>
                                    <input type="number" step="0.01" class="form-control" id="nuevo_precio" name="nuevo_precio">
                                </div>

                                <div class="col-md-4">
                                    <label for="nuevo_stock" class="form-label">Nuevo Stock:</label>
                                    <input type="number" class="form-control" id="nuevo_stock" name="nuevo_stock">
                                </div>

                                <div class="col-md-12">
                                    <label for="nueva_imagen" class="form-label">Nueva Imagen:</label>
                                    <input type="file" class="form-control" id="nueva_imagen" name="nueva_imagen" accept="image/*">
                                </div>

                                
                                    <button type="submit" class="btn btn-primary">Modificar Producto</button>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          
        
        <br><br>
            <div class="card">
                <div class="card-header">Todos los Productos</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>ID Producto</th>
                            <th>Nombre Categoría</th>
                            <th>Imagen Categoría</th>
                            <th>Nombre Producto</th>
                            <th>Precio</th>
                            <th>Descripción</th>
                            <th>Frase</th>
                            <th>Imagen</th>
                            <th>Stock</th>   
                            <th>Eliminar</th>                  
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($resultados as $fila): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fila['producto_id']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['categoria_nombre']); ?></td>
                                    <td>
                                    <?php if (!empty($fila['categoria_imagen'])): ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($fila['categoria_imagen']) ?>" alt="Imagen Categoría" width="100">
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>

                                    </td>
                                    <td><?php echo htmlspecialchars($fila['producto_nombre']); ?></td>
                                    <td><?= htmlspecialchars($fila['precio']) ?></td>
                                    <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                                    <td><?= htmlspecialchars($fila['frase']) ?></td>
                                    <td>
                                        <?php if (!empty($fila['imagen'])): ?>
                                            <img src="data:image/jpeg;base64,<?= base64_encode($fila['imagen']) ?>" alt="Imagen Producto" width="100">

                                          
                                        <?php else: ?>
                                            Sin imagen
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($fila['stock']) ?></td>
                                    <td>
                                        <form method="POST" action="eliminar.php" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                            <input type="hidden" name="producto_id" value="<?= htmlspecialchars($fila['producto_id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>