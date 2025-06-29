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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
    //echo "<p style='z-index: 99999999'>". $_POST['producto_id'] . "</p>";
    $productoId = $_POST['producto_id'];

    //ver cual es la categoria del producto
    $sql = "SELECT prod_categ.id_categ 
            FROM prod_categ
            WHERE prod_categ.id_prod = $productoId";

    $stmt1 = $pdo->query($sql);
    $resultados = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $categoriaID = $resultados[0]['id_categ'];
    try {
        $pdo->beginTransaction();

        // Eliminar relaciones primero
        $pdo->prepare("DELETE FROM prod_categ WHERE id_prod = ?")->execute([$productoId]);
        $pdo->prepare("DELETE FROM prod_frase WHERE id_prod = ?")->execute([$productoId]);
        $pdo->prepare("DELETE FROM prod_img WHERE id_prod = ?")->execute([$productoId]);
        $pdo->prepare("DELETE FROM stock WHERE id_prod = ?")->execute([$productoId]);

        // Eliminar registros independientes si todavía existen
        $stmt = $pdo->prepare("DELETE FROM Frases WHERE id IN (SELECT id_frase FROM prod_frase WHERE id_prod = ?)");
        $stmt->execute([$productoId]);

        $stmt = $pdo->prepare("DELETE FROM Imagenes WHERE id IN (SELECT id_img FROM prod_img WHERE id_prod = ?)");
        $stmt->execute([$productoId]);

        $stmt = $pdo->prepare("DELETE FROM Categorias WHERE id IN (SELECT id_categ FROM prod_categ WHERE id_prod = ?)");
        $stmt->execute([$productoId]);

        // Eliminar el producto
        $pdo->prepare("DELETE FROM Producto WHERE Producto.id = ?")->execute([$productoId]);

        $pdo->commit();

        //borrar categoria si ya no tiene productos
        $sql = "
            SELECT COUNT(Producto.nombre) as cuenta
            FROM Producto,prod_categ,Categorias
            WHERE Producto.id = prod_categ.id_prod AND prod_categ.id_categ = Categorias.id AND Categorias.id = $categoriaID";
        
        $stmt1 = $pdo->query($sql);
        $resultados = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        if($resultados[0]['cuenta'] == 0){
            try{
                $pdo->beginTransaction();
                $pdo->prepare("DELETE FROM Categorias WHERE Categorias.id = ?")->execute([$categoriaID]);
                $pdo->commit();

            }catch(Exception $e){
                $pdo->rollBack();
            }
        }
        
        header("Location: index1.php"); // Redirige de vuelta a la página principal
       
        //echo "Producto eliminado con éxito.";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error al eliminar el producto: " . $e->getMessage();
    }
}
?>
