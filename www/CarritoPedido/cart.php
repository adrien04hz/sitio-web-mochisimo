<?php

    // // Conexión a la BD
    // $dsn = "mysql:host=mysql;dbname=my_database;charset=utf8mb4";
    // $user = "mysql_user";
    // $password = "mysql_password";

    // try {
    //     $pdo = new PDO($dsn, $user, $password);
    //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // } catch (PDOException $e) {
    //     die("Connection failed: " . $e->getMessage());
    // }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        //echo "<p style='z-index:9999999;'>". $_POST['id_prod'] . " " . $_POST['cantidad_ped']. " ". $_POST['stockReal']."</p>";
        if(isset($_POST['id_prod']) && isset($_POST['cantidad_ped']) && isset($_POST['stockReal'])){
            $id_selec = (int)$_POST['id_prod'];
            $cantidad = (int)$_POST['cantidad_ped'];
            $stock = (int)$_POST['stockReal'];
            
            try{
                //solo se inserta si no hay problemas
                $sql3 = "INSERT INTO Carrito (id_prod,cantidad) VALUES ($id_selec,$cantidad)";
                $stm = $pdo->prepare($sql3);
                $stm->execute();

            }catch(PDOException $e){
                //recuperar la cantidad dentro del carrito
                $sql3 = "SELECT * FROM Carrito WHERE id_prod = $id_selec";

                $stm = $pdo->query($sql3);
                $resultados3 = $stm->fetchAll(PDO::FETCH_ASSOC);


                //se suma la cantidad nueva añadida
                $cantidad = (int)$cantidad + (int)$resultados3[0]['cantidad'];

                //verifica que no sea rebasado el stock
                if($cantidad <= $stock){
                    // Actualizar la cantidad nueva
                    $stm = $pdo->prepare("UPDATE Carrito SET cantidad = $cantidad WHERE id_prod = $id_selec");
                    $stm->execute();
                }else{
                    // solo actualizar al limite de stock
                    $stm = $pdo->prepare("UPDATE Carrito SET cantidad = $stock WHERE id_prod = $id_selec");
                    $stm->execute();
                }
                
            }
            
        }

        if(isset($_POST['eliminar'])){
            $idecito = $_POST['eliminar'];
            $del = "DELETE FROM Carrito WHERE id_prod = $idecito";

            $stm2 = $pdo->prepare($del);
            $stm2->execute();
        }
    }

    //consulta para obtener todos los items dentro del carrito
    
    $query = "SELECT  Prod.id as id_proc,Prod.precio as precio, Prod.nombre AS producto_nombre, 
    Img.imagen as imagen_prod, St.cantidad AS stock, Carrito.cantidad AS car_stock
    FROM Producto Prod
    INNER JOIN Carrito ON Prod.id = Carrito.id_prod
    INNER JOIN prod_img Pro_Img ON Prod.id = Pro_Img.id_prod
    INNER JOIN Imagenes Img ON Pro_Img.id_img = Img.id
    INNER JOIN stock St ON Prod.id = St.id_prod";
    $stmt1 = $pdo->query($query);
    $items = $stmt1->fetchAll(PDO::FETCH_ASSOC);


?>

<div id="opaco" class="bd-bg"></div>

<div id="carrito-prods" class="carrito-prods">
    <div class="btn-close">
        <button id="btn-close"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="productos">

        <?php
            //insertar los items al carrito
            if($items){
                $total2 = 0;
                foreach($items as $row){
                    $precio2 = $row['precio'] * $row['car_stock'];
                    $total2 += $precio2;
                    echo "
                        <div class='cart-prod'>
                            <div class='img-cart'>
                                <div class='img2-cart'>";

                    echo "          <img src='data:image/jpeg;base64," . base64_encode($row['imagen_prod']) ."' alt='" . $row['producto_nombre'] ."'>";

                    echo "</div></div>";


                    echo "<div class='data-cart'>
                            <div class='cart-tt'>
                                <h2>". $row['producto_nombre'] ."</h2>
                            </div>";

                    echo "    <div class='stock-cart'>
                                    <small>Stock: ". $row['stock'] ."</small>
                            </div>";


                    echo "<div class='btns-cart'>
                        <form action='' method='POST'>
                            <div class='count'>
                                <button data-id='". $row['id_proc'] ."' type='submit' class='resta'>-</button>
                                
                                
                                <input type='text' name='id_prod' value='". $row['id_proc'] ."' style='display: none;'>

                                <input name='stockReal' id='rStock-". $row['id_proc'] ."' type='text' value='". $row['stock'] ."' style='display: none;'>

                                <input id='stock-". $row['id_proc'] ."a' type='text' name='cantidad_ped' value='' style='display: none;'>
                                
                                <div class='cantidad-add1'><input type='text'  id='stock-". $row['id_proc']."' class='stockk1' value='". $row['car_stock'] ."' disabled></div>
                                <button data-id='". $row['id_proc'] ."' type='submit' class='suma'>+</button>
                            </div>
                        </form>
                        
                        <form action='' method='POST'>
                            <div class='rm-item'>
                                <button name='eliminar' value='". $row['id_proc'] ."' class='botoncito-rm noselect'><span class='text2'>Quitar</span><span class='icon2'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z'></path></svg></span></button>
                            </div>
                        </form>
                    </div>";

                    echo "<div class='price-cart'><p>$". $row['precio'] .".00</p></div>";

                    
                    echo "</div></div>";
                }
            }else{
                echo "<div class='cart-prod'>
                        <div class='sin-prod'>
                            <h2>Añade tus productos aquí</h2>
                        </div>
                    </div>";
            }

        ?>       
    </div> 

    <?php
        if(!isset($_SESSION['id_cliente']) && $items){
            echo "<div style='text-align: center; color: red; margin-bottom: -7px;'><small>*Inicie sesión para realizar pedido</small></div>";
        }
    ?>
    <div class="btn-pedido">
        <?php
            if($items){
                //si tiene iniciada la sesion permitir realizar pedido
                if(isset($_SESSION['id_cliente'])){
                    echo "   <button id='confirmar' class='btn-pedidito learn-more'>
                            <span class='circle' aria-hidden='true'>
                            <span class='icon1 arrow1'></span>
                            </span>
                            <span class='button-text'>Realizar pedido<br>Total: $$total2.00</span>
                        </button>";
                }else{
                    //mostrar mensaje con iniciar sesion
                    echo "   <button id='iniciar_sesion' class='btn-pedidito learn-more'>
                            <span class='circle' aria-hidden='true'>
                            <span class='icon1 arrow1'></span>
                            </span>
                            <span class='button-text'>Realizar pedido<br>Total: $$total2.00</span>
                        </button>";

                }
                
            }else{
                echo "   <button id='continuar' class='btn-pedidito learn-more'>
                            <span class='circle' aria-hidden='true'>
                            <span class='icon1 arrow1'></span>
                            </span>
                            <span class='button-text' style='font-size: 15px; padding-top: 0.54rem; padding-left: 0.8rem;'>Continuar<br>comprando</span>
                        </button>";   
            }
        ?>
    </div>
</div>