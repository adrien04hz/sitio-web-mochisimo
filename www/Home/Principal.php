<?php
// Conexión a la BD
session_start();
$dsn = "mysql:host=mysql;dbname=my_database;charset=utf8mb4";
$user = "mysql_user";
$password = "mysql_password";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


//consulta para ver las categorias
$query = "SELECT * FROM Categorias";

$stmt = $pdo->query($query);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <link rel="stylesheet" href="../css/carrusel.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/social.css">
    <link rel="stylesheet" href="../css/bt.css">
    <link rel="stylesheet" href="../css/close.css">
    <link rel="stylesheet" href="../css/rm_cart.css">
    <link rel="stylesheet" href="../css/pedido.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tenor+Sans&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>

    
    <title>Home</title>
    <link rel="icon" href="../images/logo.webp" type="image/x-icon">
</head>
<body>
    <?php
        include '../CarritoPedido/cart.php';
        include '../CarritoPedido/pedido.php';
    ?>

    <?php if(isset($_SESSION['id_cliente']) || isset($_SESSION['admin_id'])): ?>
    
        <nav>
            <?php include '../html/nav_user.html'; ?>
        </nav>
    <?php else: ?>
        <nav>
            <?php include '../html/nav.html'; ?>
        </nav>
    <?php endif; ?>

    <main id="principal">
    <div class="carrousel">
        <div class="conteCarrousel">
            <div class="itemCarrousel" id="itemCarrousel-1">
                <div class="itemCarrouselTarjeta">
                    <div class="figura">
                        <svg  width="1800" height="1110" viewBox="0 0 1600 700" xmlns="http://www.w3.org/2000/svg">
                        <!-- Figura -->
                        
                        <path 
                            d="M 200 100 C 300 100 300 50 400 50 C 500 50 500 100 600 100 L 600 500 C 550 500 550 550 550 550 L 250 550 C 250 550 250 500 200 500 L 200 100" 
                            fill="skyblue" 
                            transform="scale(1.25, 0.9)" 
                            stroke-width="2" />
                        </svg>
                        <div class="contenido">
                            <div class="text-container">
                                Este es el contenido.
                            </div>
                            <div class="btn-pagina">
                                <button class="custom-button">PUCHAME</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="itemCarrouselArrows">
                    <a href="#itemCarrousel-3">
                        <i class="bi bi-arrow-left-circle-fill"></i>
                    </a>
                    <a href="#itemCarrousel-2">
                        <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
            </div>
            <div class="itemCarrousel" id="itemCarrousel-2">
                    <div class="itemCarrouselTarjeta">
                        <div>                     
                            <svg  width="1800" height="1110" viewBox="0 0 1600 800" xmlns="http://www.w3.org/2000/svg">
                            <path 
                                d="M 200 100 C 300 100 300 50 400 50 C 500 50 500 100 600 100 C 550 200 600 250 600 300 C 600 350 550 400 600 500 C 500 500 500 550 400 550 C 300 550 300 500 200 500 C 250 400 200 350 200 300 C 200 250 250 200 200 100 "
                                fill="red" 
                                transform="scale(1.25, 1.09)" 
                                stroke-width="2" />
                            </svg>
                            <div class="contenido">
                                <div class="text-container">
                                    Este es el contenido.
                                </div>
                                <div class="btn-pagina">
                                    <button class="custom-button">PUCHAME</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="itemCarrouselArrows">
                        <a href="#itemCarrousel-1">
                            <i class="bi bi-arrow-left-circle-fill"></i>
                        </a>
                        <a href="#itemCarrousel-3">
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
            
            </div>  
            <div class="itemCarrousel" id="itemCarrousel-3">
                    <div class="itemCarrouselTarjeta">
                        <div>
                            
                            <svg  width="1800" height="1100" viewBox="0 0 1600 800" xmlns="http://www.w3.org/2000/svg">
                            <path 
                                d="M 200 100 L 550 100 C 525 125 575 125 550 150 C 525 175 575 175 550 200 C 525 225 575 225 550 250 C 525 275 575 275 550 300 C 525 325 575 325 550 350 C 525 375 575 375 550 400 C 525 425 575 425 550 450 C 525 475 575 475 550 500 L 200 500 C 175 475 225 475 200 450 C 175 425 225 425 200 400 C 175 375 225 375 200 350 C 175 325 225 325 200 300 C 175 275 225 275 200 250 C 175 225 225 225 200 200 C 175 175 225 175 200 150 C 175 125 225 125 200 100 " 
                                    fill="skyblue" 
                                    transform="scale(1.35, 1.12)" 
                                    stroke-width="2" />
                            </svg>
                            <div class="contenido">
                                <div class="text-container">
                                    Este es el contenido.
                                </div>
                                <div class="btn-pagina">
                                    <button class="custom-button">PUCHAME</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="itemCarrouselArrows">
                        <a href="#itemCarrousel-2">
                            <i class="bi bi-arrow-left-circle-fill"></i>
                        </a>
                        <a href="#itemCarrousel-1">
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
            
            </div>  

            
        </div>

        <div class="conteCarrouselController">
            <a href="#itemCarrousel-1">
                <i class="bi bi-record-fill"></i>
            </a>
            <a href="#itemCarrousel-2">
                <i class="bi bi-record-fill"></i>
            </a>
            <a href="#itemCarrousel-3">
                <i class="bi bi-record-fill"></i>
            </a>
        </div>
        
    </div>
    </main>
        
    <?php
        include '../html/footer.html';
    ?>
    <script src="../js/script.js"></script>
    <script src="../js/stock.js"></script>
    <script src="https://kit.fontawesome.com/47b83cb62d.js" crossorigin="anonymous"></script>
    <script src="../js/cart-stock.js"></script>
    <script src="../js/lateral.js"></script>
    <script src="../js/peoductos.js"></script>
</body>
</html>