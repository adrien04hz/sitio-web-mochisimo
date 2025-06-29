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

    if(isset($_GET['producto_id'])){
        $prodID = $_GET['producto_id'];

        $query = "SELECT Prod.id AS producto_id, Cat.nombre AS categoria_nombre, Cat.imagen_categ AS categoria_imagen, Prod.precio as precio, Prod.nombre AS producto_nombre, 
        Prod.descripcion as details, Fra.frase as frase, Img.imagen as imagen_prod, St.cantidad AS stock
        FROM Producto Prod
        INNER JOIN prod_categ Pro_Ca ON Prod.id = $prodID
        INNER JOIN Categorias Cat ON Pro_Ca.id_categ = Cat.id
        INNER JOIN prod_frase Pro_Fr ON Prod.id = Pro_Fr.id_prod
        INNER JOIN Frases Fra ON Pro_Fr.id_frase = Fra.id
        INNER JOIN prod_img Pro_Img ON Prod.id = Pro_Img.id_prod
        INNER JOIN Imagenes Img ON Pro_Img.id_img = Img.id
        INNER JOIN stock St ON Prod.id = St.id_prod";

        $stmt = $pdo->query($query);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);


    }else{
        header('Location: catalogo.php?error=1');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles</title>
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/detalles.css">
    <link rel="stylesheet" href="../css/social.css">
    <link rel="stylesheet" href="../css/close.css">
    <link rel="stylesheet" href="../css/rm_cart.css">
    <link rel="stylesheet" href="../css/pedido.css">
    <link rel="stylesheet" href="../css/iniciarPedido.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">
    


</head>
<body>
<?php
        include '../CarritoPedido/cart.php';

        if(isset($_SESSION['id_cliente'])){
            include '../CarritoPedido/pedido.php';
        }else{
            include '../CarritoPedido/pedidoSesion.php';
        }
    ?>

    <?php if(isset($_SESSION['id_cliente'])): ?>
        <nav>
            <?php include '../html/nav_user.html'; ?>
        </nav>
    <?php else: ?>
        <nav>
            <?php include '../html/nav.html'; ?>
        </nav>
    <?php endif; ?>


    <main>
        <div class="detalle-principal">
            <div class="img-detalle">
                <div class="img-contenedor">
                <?php
                    
                    echo "<img src='data:image/jpeg;base64," . base64_encode($resultados[0]['imagen_prod']) ."' alt='" . $resultados[0]['producto_nombre'] ."'>";
                ?>
                </div>
            </div>
            <div class="datos">
                <div class="dtl tt">
                    <?php
                        echo "<h2>". $resultados[0]['producto_nombre'] ."</h2>";
                    ?>
                </div>

                <div class="dtl">
                    <?php
                       echo "<p>". $resultados[0]['details'] ."</p>";
                    ?>
                </div>

                <div class="dtl">
                    <?php
                        echo "<p><i>" . $resultados[0]['frase'] . "</i></p>";
                    ?>
                </div>

                <div class="dtl">
                    <?php
                        echo "<p>$" . $resultados[0]['precio'] . ".00</p>";
                    ?>
                </div>

                
                <div class="q-stock">
                    <form action="" method="POST">
                        <input type="text" name="id_prod" value="<?php echo $resultados[0]['producto_id'];?>" style="display: none">
                        <div class="stock-cantidad">
                            <div class="cantidad">
                                <button id="dcrementar" type="button">-</button>
                                <div class="cantidad-add"><input type="text" name="cantidad_ped" id="stock" class="stockk" value="1" readonly></div>
                                <button id="auincrementar" type="button">+</button>
                            </div>
                            <div class="stock">
                                <div class="st">
                                    <input id="details_limit" name="stockReal" type="text" value="<?php echo $resultados[0]['stock']; ?>" style="display: none;">
                                    <?php
                                        echo "<p>En stock: " . $resultados[0]['stock'] . "</p>";
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="add">
                            <!-- <button class="btn" type="submit">Al carrito</button> -->
                            <?php
                                if($resultados[0]['stock'] == 0){
                                    echo "<button id='add_prod' type='submit' class='button' disabled>";
                                }else{
                                    echo "<button id='add_prod' type='submit' class='button'>";
                                }
                            ?>
                            <!-- <button id="add_prod" type="submit" class="button"> -->
                                <span class="button__text">Añadir</span>
                                <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg"><line y2="19" y1="5" x2="12" x1="12"></line><line y2="12" y1="12" x2="19" x1="5"></line></svg></span>
                            </button>

                            <?php
                                if($resultados[0]['stock'] == 0)
                                echo "<div style='margin-top: 10px;'><small style='color: red;'>*Producto agotado, disculpe las molestias.</small></div>";
                            ?>
                        </div>
                    </form>
                </div>


            </div>
        </div>

        <br><br><br>
    </main>

    <?php
        include '../html/footer.html';
    ?>
    <script src="../js/script.js"></script>
    <script src="../js/stock.js"></script>
    <script src="../js/cart-stock.js"></script>
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>
</body>
</html>