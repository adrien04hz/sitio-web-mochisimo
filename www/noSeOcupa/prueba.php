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

$query = "SELECT * FROM Categorias";

$stmt = $pdo->query($query);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="principal">
        <?php foreach($categorias as $fila): ?>
            <a href='./index1.php'><div  class="card " style="width: 18rem;">
                <div>
                    <?php if (!empty($fila['imagen_categ'])): ?>
                        <img class="card-img-top" src="data:image/jpeg;base64,<?= base64_encode($fila['imagen_categ']) ?>" alt="Imagen Categoría" width="100">
                    <?php else: ?>
                        Sin imagen
                    <?php endif; ?>

                </div>
                <div class="card-body">
                    <?php echo htmlspecialchars($fila['nombre']) ?>
                </div>
            </div></a>
        <?php endforeach; ?>
        
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>