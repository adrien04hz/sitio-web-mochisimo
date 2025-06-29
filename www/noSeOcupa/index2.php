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
    $id = $_POST['id'];
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $frase = $_POST['frase'];
    $imagen = $_POST['imagen'];

    $stmt = $pdo->prepare("INSERT INTO productos (nombre_producto, descripcion, frase, imagen) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre_producto, $descripcion, $frase, $imagen]);
}
// ... Código de conexión

// Consulta para obtener productos
$productos = $pdo->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all estudiantes
//$estudiantes = $pdo->query("SELECT * FROM estudiantes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Productos CR</h1>

        <!-- Agregar producto -->
        <div class="card mb-4">
            <div class="card-header">Agregar producto</div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="id" class="form-label">ID</label>
                        <input type="number" class="form-control" id="id" name="id" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="frase" class="form-label">Frase</label>
                        <input type="text" class="form-control" id="frase" name="frase" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="text" class="form-control" id="imagen" name="imagen" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar producto</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Productos</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Producto</th>
                            <th>Descripción</th>
                            <th>Frase</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['id']); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($producto['frase']); ?></td>
                                <td><?php echo htmlspecialchars($producto['imagen']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include 'datos.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Estudiantes CR</h1>
        
        <!-- Agregar estudiante  -->
        <div class="card mb-4">
            <div class="card-header">Agregar estudiante</div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="grupo" class="form-label">grupo</label>
                        <input type="text" class="form-control" id="grupo" name="grupo" required>
                    </div>
                    <div class="mb-3">
                        <label for="f_nac" class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" id="f_nac" name="f_nac" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar estudiante</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Estudiantes</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Grupo</th>
                            <th>Fecha de nacimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($estudiante['id']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['grupo']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['f_nac']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<form method="POST">
    <input type="hidden" name="accion" value="eliminar">
    <input type="hidden" name="producto_id" value="<?= $producto['producto_id']; ?>">
    <button type="submit">Eliminar Producto</button>
</form>
