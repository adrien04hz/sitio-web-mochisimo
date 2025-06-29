<?php
/*
paso 1: a partir del carrito llenar la nueva tabla de productos, incluyendo las imagenes cuidando que no haya productos repetidos, si se produce error de que ya se encuentra ese producto, solo se actualiza los datos excepto el nombre
paso 2: llenar la tabla de pedidos con el nuevo pedido
paso 3: vaciar la tabla de carrit en el de detalles incluyendo el id del pedido.
paso 4: se vacia la tabla de carrito

*/ 
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
    
    if(isset($_SESSION['id_cliente'])){   
         if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(isset($_POST['direccion']) && isset($_POST['montoTotal'])){
                $dirSeleccionada = $_POST['direccion'];
                $monto = $_POST['montoTotal'];

                //se realiza la insersion del pedido
                try{
                    $insert = "INSERT INTO Pedido (id_cliente, id_direccion, monto) VALUES (" . $_SESSION['id_cliente'] . ", $dirSeleccionada,$monto)";
                    $ingresando = $pdo->prepare($insert);
                    $ingresando->execute();

                    //se obtiene el ultimo id de pedido insertado
                    $idPedidoNuevo = $pdo->lastInsertId();

                }catch(PDOException $e){
                    $pdo->rollBack();
                    //echo "Error al insertar nuevo pedido: " . $e->getMessage();
                }

                //paso 1: consultar los datos necesarios para poder copiar tabla
                $queyCarritoProd = "SELECT 
                                        Producto.id as id, Producto.precio as precio,
                                        Producto.nombre as nombre, 
                                        Producto.descripcion as descripcion,
                                        Imagenes.id as img_id, Imagenes.imagen as imagen

                                    FROM 
                                        Producto, Carrito, 
                                        prod_img, Imagenes

                                    WHERE 
                                        Carrito.id_prod = Producto.id AND 
                                        Producto.id = prod_img.id_prod AND 
                                        prod_img.id_img = Imagenes.id";

                $copiar = $pdo->query($queyCarritoProd);
                $detallesNuevos = $copiar->fetchAll(PDO::FETCH_ASSOC);


                //paso 1a: insertar en las tablas los datos recopilados del carrito
                foreach($detallesNuevos as $fila){

                    //inserta los datos dentro de la tabla de imagenes
                    try{
                        //insertar en la tabla de imagenes
                        $imagenes = $pdo->prepare("INSERT INTO imagenDetalle (id,imagen) VALUES (:id,:imagen)");
                        $imagenes->execute([
                            ':imagen' => $fila['imagen'],
                            ':id' => $fila['img_id']
                        ]);

                    }catch(PDOException $e){
                        //solo actualizar los datos de las imagenes
                        $imagenes1 = $pdo->prepare("UPDATE imagenDetalle SET imagen= :imagen WHERE imagenDetalle.id = :id");
                        $imagenes1->execute([
                            ':imagen' => $fila['imagen'],
                            ':id' => $fila['img_id']
                        ]);
                    }

                    //inserta los datos dentro de la tabla de productos
                    try{
                        //intertar insrtar los nuevos productos
                        $productosNuevos = $pdo->prepare("INSERT INTO detallesProd (id,precio,nombre,descripcion) VALUES (:id,:precio,:nombre,:des)");
                        $productosNuevos->execute([
                            ':id' => $fila['id'],
                            ':precio' => $fila['precio'],
                            ':nombre' => $fila['nombre'],
                            ':des' => $fila['descripcion']
                        ]);
                    }catch(PDOException $e){
                        //solo actualizar los datos de los productos
                        $productosNuevos1 = $pdo->prepare("UPDATE detallesProd SET precio= :precio,nombre= :nombre,descripcion= :des WHERE detallesProd.id = :id");
                        $productosNuevos1->execute([
                            ':id' => $fila['id'],
                            ':precio' => $fila['precio'],
                            ':nombre' => $fila['nombre'],
                            ':des' => $fila['descripcion']
                        ]);

                    }

                    //inserta la relacion entre productos y sus imagenes
                    try{
                        $nuevaRelacion = $pdo->prepare("INSERT INTO prod_imgDet (id_prod,id_img) VALUES (:id,:img_id)");
                        $nuevaRelacion->execute([
                            ':id' => $fila['id'],
                            ':img_id' => $fila['img_id']
                        ]);

                    }catch(PDOException $e){
                        $pdo->rollBack();
                    }
                }

                //paso 2: se consultan todos los productos del carrito
                $carritoQuery = "SELECT * FROM Carrito";
                $carrito = $pdo->query($carritoQuery);
                $itemsCarrito = $carrito->fetchAll(PDO::FETCH_ASSOC);

                //paso 3: se inserta cada item del carrito en detalles
                if($itemsCarrito){
                    foreach($itemsCarrito as $fila){
                        try{
                            $insertaDetalle = "INSERT INTO detalles (id_prod,id_pedido,cantidad) VALUES (". $fila['id_prod'] .", $idPedidoNuevo,". $fila['cantidad'] .")";

                            $nuevoDetalle = $pdo->prepare($insertaDetalle);
                            $nuevoDetalle->execute();

                        }catch(PDOException $e){
                            $pdo->rollBack();
                            //echo "Error al insertar nuevos detalles: " . $e->getMessage();
                        }
                    }
                    
                    //paso 4: actualizar stock ya solicitado del stock original
                    //paso 4a: consultar el stock original
                    $stockQuery = "SELECT * FROM stock";
                    $stockOriginal = $pdo->query($stockQuery);
                    $stock = $stockOriginal->fetchAll(PDO::FETCH_ASSOC);
                    
                    //paso 4b: realizar las operaciones para actualizar e insertar en stock
                    if($stock){
                        foreach($itemsCarrito as $item){
                            foreach($stock as $original){
                                if($item['id_prod'] == $original['id_prod']){
                                    $nuevoStock = $original['cantidad'] - $item['cantidad'];

                                    try{
                                        $actualizar = "UPDATE stock SET cantidad = $nuevoStock WHERE id_prod =" . $original['id_prod'];
                                        $stockUpdate = $pdo->prepare($actualizar);
                                        $stockUpdate->execute();
                                    }catch(PDOException $e){
                                        $pdo->rollBack();
                                    }
                                }
                            }
                        }
                    }

                    //paso 5: eliminar los datos del carrito
                    try{
                        $eliminarCarrito = "TRUNCATE TABLE Carrito";
                        $elim = $pdo->prepare($eliminarCarrito);
                        $elim->execute();
                    }catch(PDOException $e){
                        $pdo->rollBack();
                    }

                    header('location: ../Resenias/Resenias.php?compra=1');
                }
            }
        }
    }
?>
