<?php
    

    $query = "SELECT  Prod.id as id_proc,Prod.precio as precio, Prod.nombre AS producto_nombre, 
    Img.imagen as imagen_prod, St.cantidad AS stock, Carrito.cantidad AS car_stock
    FROM Producto Prod
    INNER JOIN Carrito ON Prod.id = Carrito.id_prod
    INNER JOIN prod_img Pro_Img ON Prod.id = Pro_Img.id_prod
    INNER JOIN Imagenes Img ON Pro_Img.id_img = Img.id
    INNER JOIN stock St ON Prod.id = St.id_prod";
    $stmt3 = $pdo->query($query);
    $items2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    //Selecciona todas las direcciones disponibles
    $query2 = "SELECT * FROM Direccion";
    $stmt6 = $pdo->query($query2);
    $dirs = $stmt6->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="opaco2" class="bd-bg2"></div>


<div id='confirm_card' class="pedido-confirm">
    <div class="titulo-confirmar">
        <h1>CONFIRMAR PEDIDO</h1>
    </div>
    <form id="dirForm" action="../CarritoPedido/pedidoHecho.php" method="post">
        <div class="datos-cliente">
            <div class="datos-titulo">
                <h3>DATOS PERSONALES</h3>
            </div>

            <div class="datos-personales">
                <div class="dato-nombre">
                    <p>Nombre: <?php echo $_SESSION['nombre'];?></p>
                </div>

                <div class="dato-tel">
                    <p>Tel.: <?php echo $_SESSION['tel']; ?></p>
                </div>

                <div class="dato-correo">
                    <p>Correo: <?php echo $_SESSION['email']; ?></p>
                </div>

                <!-- <div class="dato-entrega">
                    <p>Entrega: se asigna el lugars</p>
                </div> -->
                <div class="dato-entrega">
                    
                    <label for="dirs">Selecciona una direccion: </label>
                    <select name="direccion" id="dirs">
                        <?php
                            $sePuedeRealizarPedido = 1;
                            if($dirs){
                                foreach($dirs as $fila){
                                    echo "<option value='". $fila['id'] ."'>". $fila['direccion'] ."</option>";
                                }
                            }else{
                                echo "<option value='-1' disabled selected>No hay entrega disponible</option>";
                                $sePuedeRealizarPedido = 0;
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="descripcion-items">
            <h3>DESCRIPCIÓN DEL PEDIDO</h3>
        </div>

        <div class="items-seleccionados">
            <div class="items-container">
                <?php
                    $total = 0;
                    foreach($items2 as $row){
                        $precio = $row['car_stock'] * $row['precio'];
                        $total += $precio;

                        echo " <!-- empieza item -->
                                <div class='item-confirm'>
                                    <div class='img-seleccionado'>
                                        <div class='item-imagen-seleccionada'>
                                            <img src='data:image/jpeg;base64,". base64_encode($row['imagen_prod']) ."' alt='". $row['producto_nombre'] ."'>
                                        </div>
                                    </div>

                                    <div class='datos-seleccionado'>
                                        <div class='titulo-item-seleccionado'>
                                            <h3>". $row['producto_nombre'] ."</h3>
                                        </div>

                                        <div class='cantidad-item-seleccionada'>
                                            <p>Por pedir: ". $row['car_stock'] ." piezas.</p>
                                        </div>

                                        <div class='precio-item-seleccionado'>
                                            <p>$". $precio .".00</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- termina item -->";
                    }
                ?>
            </div>

            <div class="total-container">
                <input type="number" name="montoTotal" value="<?php echo $total; ?>" style="display: none;">
                <p>Monto total:<br><?php echo "$". $total . ".00";?></p>
            </div>
        </div>
    </form>

    <div class="btns-confirmar">
        <div class="btn-cancelar">
            <button id='cerrar_confirmar' class="cancelarcito2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 74 74"
                    height="34"
                    width="34"
                >
                <circle stroke-width="3" stroke="white" r="35.5" cy="37" cx="37"></circle>
                <path 
                    fill="white"
                    

                    d="M49 35.5C49.8284 35.5 50.5 36.1716 50.5 37C50.5 37.8284 49.8284 38.5 49 38.5V35.5ZM24.9393 38.0607C24.3536 37.4749 24.3536 36.5251 24.9393 35.9393L34.4853 26.3934C35.0711 25.8076 36.0208 25.8076 36.6066 26.3934C37.1924 26.9792 37.1924 27.9289 36.6066 28.5147L28.1213 37L36.6066 45.4853C37.1924 46.0711 37.1924 47.0208 36.6066 47.6066C36.0208 48.1924 35.0711 48.1924 34.4853 47.6066L24.9393 38.0607ZM49 38.5L26 38.5V35.5L49 35.5V38.5Z"

                    ></path>
                </svg>
                <span>Cancelar</span>
            </button>
                
        </div>

        <div class="btn-confirmar">

            <?php
                if($sePuedeRealizarPedido == 0){
                    echo "<button onclick=\"window.location.href='catalogo.php'\" class='cancelarcito' disabled>";
                }else{
                    echo "<button form='dirForm' type='submit' onclick=\"window.location.href='catalogo.php'\" class='cancelarcito'>";
                }
            ?>
            <!-- <button onclick="window.location.href='catalogo.php'" class="cancelarcito"> -->
                <span>Confirmar</span>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 74 74"
                    height="34"
                    width="34"
                >
                <circle stroke-width="3" stroke="white" r="35.5" cy="37" cx="37"></circle>
                <path
                    fill="white"
                    d="M25 35.5C24.1716 35.5 23.5 36.1716 23.5 37C23.5 37.8284 24.1716 38.5 25 38.5V35.5ZM49.0607 38.0607C49.6464 37.4749 49.6464 36.5251 49.0607 35.9393L39.5147 26.3934C38.9289 25.8076 37.9792 25.8076 37.3934 26.3934C36.8076 26.9792 36.8076 27.9289 37.3934 28.5147L45.8787 37L37.3934 45.4853C36.8076 46.0711 36.8076 47.0208 37.3934 47.6066C37.9792 48.1924 38.9289 48.1924 39.5147 47.6066L49.0607 38.0607ZM25 38.5L48 38.5V35.5L25 35.5V38.5Z"
                    ></path>
                </svg>
            </button>
        </div>
    </div>
    
</div>