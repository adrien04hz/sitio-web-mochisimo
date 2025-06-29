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
