

        <div>
            <?php if (isset($_SESSION['admin_id'])) { ?>
                <div class="lateral">
                    <br><br><br><br><br><br><br><br><br><br>
                    <ul>                    
                        <li>
                            <div class="btn-productos" onclick="toggleMenu('menuProductos')">
                                Productos <i class="bi bi-chevron-down"></i>
                            </div>
                            <ul id="menuProductos" class="submenu">
                                <li data-id="agregarProducto">Agregar Producto</li>
                                <li data-id="modificarProducto">Modificar Producto</li>
                                <li data-id="eliminarProducto">Eliminar Producto</li>
                            </ul>
                        </li>

                        <li>
                            <div class="btn-productos" onclick="toggleMenu('menuResenias')">
                                Reseñas <i class="bi bi-chevron-down"></i>
                            </div>
                            <ul id="menuResenias" class="submenu">
                                <li data-id="mostrarResenias">Moderar Reseñas</li>
                            </ul>
                        </li>

                        <li>
                            <div class="btn-productos" onclick="toggleMenu('menuPedidos')">
                                Pedidos <i class="bi bi-chevron-down"></i>
                            </div>
                            <ul id="menuPedidos" class="submenu">
                                <li data-id="pendientesPedidos">Pedidos Pendientes</li>
                                <li data-id="entregadosPedidos">Pedidos Entregados</li>
                                <li data-id="estadoPedidos">Actualizar estado de pedido</li>
                            </ul>
                        </li>
                    </ul>
                </div>


            <?php } ?>
        </div>
    
        <div class="content-general">
            
            <div id="agregarProducto" class="hidden">
                <div class="agregar">
                    <form method="POST" action="Admin/Agregar.php" enctype="multipart/form-data">
                            <div class="container-ajuste">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="categoria">
                                            <label for="categoria">Selecciona una Categoría:</label>
                                            <select id="categoriaSelect" name="categoria" onchange="verificarNuevaCategoria()" required>
                                                <option value="" disabled selected>Selecciona una categoría</option>
                                                <?php
                                                foreach ($resultados as $fila) {
                                                    echo "<option value='" . htmlspecialchars($fila['nombre']) . "'>" . htmlspecialchars($fila['nombre']) . "</option>";
                                                }
                                                ?>
                                                <option value="nueva_categoria">Nueva categoría</option>
                                            </select>
                                        </div>

                                        <div id="nuevaCategoriaInput" style="display: none; margin-top: 10px;">
                                            <label for="nuevaCategoria">Nueva Categoría:</label>
                                            <input type="text" id="nuevaCategoria" name="nueva_categoria" oninput="validarLetras(event)" placeholder="Escribe una nueva categoría" style="width: 100%; max-width: 300px; height: 40px; border-radius: 5px;">
                                        </div>
                                    </div>

                                    <script>
                                        function verificarNuevaCategoria() {
                                            var select = document.getElementById("categoriaSelect");
                                            var nuevaCategoriaDiv = document.getElementById("nuevaCategoriaInput");
                                            var nuevaCategoriaInput = document.getElementById("nuevaCategoria");

                                            if (select.value === "nueva_categoria") {
                                                nuevaCategoriaDiv.style.display = "block";
                                                nuevaCategoriaInput.setAttribute("name", "categoria"); // Enviar la nueva categoría
                                            } else {
                                                nuevaCategoriaDiv.style.display = "none";
                                                nuevaCategoriaInput.removeAttribute("name"); // Evitar que se envíe vacío
                                            }
                                        }
                                    </script>


                                
                                    <div class="col-8">
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
                                </div>  
                            </div>
                        <div class="container-ajuste">
                            <div class="row">
                                <div class="col-4">
                                    <label for="producto" class="form-label">Nombre Producto:</label>
                                    <input type="text" class="form-control" id="producto" name="producto" oninput="validarLetras(event)" required>
                                    <?php
                                        if(isset($_GET['exist']) && $_GET['exist'] == 1){
                                            echo "<small>*El producto ya existe</small>";
                                        }
                                    ?>
                                </div>
                            
                                <div class="col-8">
                                    <label for="imagen" class="form-label">Subir Imagen producto:</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                                </div>
                            </div>  
                        </div>
                        <div class="container-ajuste">
                            <div class="row">
                                <div class="col-12">
                                    <label for="descripcion" class="form-label">Descripción:</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2" oninput="validarLetras(event)" required></textarea>

                                </div>
                            </div>
                        </div>
                        <div class="container-ajuste">
                            <div class="row">
                                <div class="col-12">
                                    <label for="frases" class="form-label">Frase:</label>
                                    <input type="text" class="form-control" id="frases" name="frases" oninput="validarLetras(event)" required>
                                </div>
                            </div>
                        
                        </div>

                        <div class="container-ajuste">
                            <div class="row">
                                <div class="col-4">
                                    <label for="precio" class="form-label">Precio:</label>
                                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                                </div>
                                <div class="col-2">
                                    <label for="stock" class="form-label">Stock:</label>
                                    <input type="number" class="form-control" id="stock" name="stock" min="0" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="btn-productos-pagina">
                            <button type="submit" class="custom-button2">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
                                    

            <div id="modificarProducto" class="hidden">
                <div class="modificarProducto">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="modificar">
                            <label for="categoria">Categorías:</label>
                            <select id="categoria_producto" name="categoria">
                                <option value="" disabled selected>Selecciona una categoría</option>
                                <?php
                                foreach ($resultados as $fila) {
                                    echo "<option value='" . htmlspecialchars($fila['id']) . "'>" . htmlspecialchars($fila['nombre']) . "</option>";
                                }
                                ?>
                            </select>
                            <div class="col-8">
                                <label for="producto_categoria"></label>
                                <select id="producto_categoria" name="producto_categoria">
                                    <option value="" disabled selected></option>
                                    <option value=""></option>
                                </select>
                            </div>
                            

                        </div>
                        
                        <div class="container-ajuste">
                            <div class="row">
                                <div class="col-12">
                                    <label for="descripcion">Detalles de Producto</label>
                                    <p id="descripcion_producto" name="descripcion_producto"></p> 
                                  
                                </div>
                            </div>
                        
                        </div>

                    </form>
                </div>
                <div class="modificarProducto">
                    <form method="POST" action="Admin/Consultas/Modificar.php" enctype="multipart/form-data">
                        <!-- Campo oculto con el id_producto -->
                        <div class="container-ajuste">
                            <div class="row">
                                <div class="col-8">
                                    
                                    <label for="nueva_categoria" class="form-label">Nueva Categoría:</label>
                                    <input type="text" class="form-control" id="nueva_categoria" name="nueva_categoria"  oninput="validarLetras(event)">
                                </div>
                                <div class="col-12">
                                    <label for="nuevaImagenCateg" class="form-label">Nueva Imagen Categoria:</label>
                                    <?php
                                            if(isset($_GET['imgnueva_cat']) && $_GET['imgnueva_cat']==1){
                                                echo "<input type='file' class='form-control' id='nuevaImagenCateg' name='nuevaImagenCateg' required>";
                                                echo "<small> *Al insertar nueva categoria, selecciona una imagen</small>";
                                            }else{
                                                echo "<input type='file' class='form-control' id='nuevaImagenCateg' name='nuevaImagenCateg'>";
                                            }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="container-ajuste">
                            <div class="row">
                                <div class="col-2">
                                    <label for="nuevo_producto" class="form-label">Nuevo Nombre de Producto:</label>
                                    <input type="text" class="form-control" id="nuevo_producto" name="nuevo_producto"  oninput="validarLetras(event)">
                                </div>
                                <div class="col-8">
                                    <label for="nueva_descripcion" class="form-label">Nueva Descripción:</label>
                                    <textarea class="form-control" id="nueva_descripcion" name="nueva_descripcion" rows="2"  oninput="validarLetras(event)"></textarea>
                                </div>
                                <div class="col-4">
                                    <label for="nueva_frase" class="form-label">Nueva Frase:</label>
                                    <input type="text" class="form-control" id="nueva_frase" name="nueva_frase">
                                </div>
                                
                            </div>
                        </div> 
                        <div class="container-ajuste">
                            <div class="row">
                                <div class="col-8">
                                    <label for="nueva_imagen" class="form-label">Nueva Imagen:</label>
                                    <input type="file" class="form-control" id="nueva_imagen" name="nueva_imagen" accept="image/*">
                                </div>
                                <div class="col-2">
                                    <label for="nuevo_precio" class="form-label">Nuevo Precio:</label>
                                    <input type="number" step="0.01" class="form-control" id="nuevo_precio" name="nuevo_precio">
                                </div>
                                <div class="col-2">
                                    <label for="nuevo_stock" class="form-label">Nuevo Stock:</label>
                                    <input type="number" class="form-control" id="nuevo_stock" name="nuevo_stock">
                              
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="btn-pagina-productos">
                            <button type="submit" class="custom-button2">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="eliminarProducto" class="hidden">
                <h2>Eliminar Producto</h2>
                <div class="eliminarProducto">
                    
                    <div class="eliminar">
                        <label for="categoria">Categorías:</label>
                        <select id="productos_categ" name="categoria">
                            <option value="" disabled selected>Selecciona una categoría</option>
                            <?php
                            foreach ($resultados as $fila) {
                                echo "<option value='" . htmlspecialchars($fila['id']) . "'>" . htmlspecialchars($fila['nombre']) . "</option>";
                            }
                            ?>
                        </select>
                        
                    </div>
                    <div class="col-8">
                        <label for="productos_categ1">Productos de la Categoria: </label>
                        <div id="productos_categ1" name="productos_categ1">
                            
                        </div> 
                        
                    </div>
                    
                </div>
            </div>

            <div id="mostrarResenias" class="hidden">
                
                <div class="mostrarResenias">
                     <div class="envolver">  
                            <?php 
                            if ($resenias) {
                                foreach ($resenias as $fila) { ?>
                                <div class="resenias">
                                    <div class = "contenido">
                                        <div class="datos">
                                            <div class="ajustar">
                                                <p class="estilizando"><?php echo htmlspecialchars($fila['username']); ?></p>     
                                                 <?php if(isset($_SESSION['admin_id'])): ?>                       
                                                    <div class="menu-container">
                                                        <button class="menu-btn">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                            <div class="menu">
                                                                <!-- <form action="../Admin/Consultas/eliminarResenia.php" method="POST"> -->
                                                                <form action="../Admin/Consultas/eliminarResenia.php" method="POST">
                                                                        <!-- Campo oculto con el id de la reseña -->
                                                                        <input type="hidden" name="id_resenia" value="<?php echo htmlspecialchars($fila['id']); ?>">

                                                                        <button type="submit" class="delete">Eliminar</button>
                                                                    </form>
                                                                </form> 
                                                            </div>
                                                        
                                                    </div>
                                                <?php endif; ?>
                                               
                                            </div>
            
                                            <p class="email"><?php echo nl2br(htmlspecialchars($fila['email'])); ?></p>
                                            <p class="start">
                                                <?php
                                                
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $fila['calificacion']) {
                                                        echo '<i class="fas fa-star"></i>';  
                                                    } else {
                                                        echo '<i class="far fa-star"></i>';  
                                                    }
                                                }
                                                ?>
                                            </p>
                            
                                            <div class="comentario">
                                                <p><?php echo nl2br(htmlspecialchars($fila['comentario'])); ?></p>
                                            </div>
                                            <div class="cont">
                                                <div class="img ">
                                                    <img src="/images/logo.webp">
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                    <?php 
                                } 
                            } else {
                                echo "<p>No hay reseñas disponibles.</p>";
                            }
                            ?>
                        </div>


                    
                    
                </div>
            </div>

            <div id="pendientesPedidos" class="hidden">
                <div class="pedidos-actuales">
                    <div class="pedidos-pendientes">
                        <div class="titulo-pendientes21">
                            <h2>Pendientes:</h2>
                        </div>
                    </div>
                    <div class="pendientes-descripciones">
                            <?php
                                if($pedidosPendientes){
                                    foreach($pedidosPendientes as $fila){
                                        $idPendiente = $fila['id'];
                                        $idDireccion = $fila['id_direccion'];
                                        $fecha = $fila['fecha'];

                                        $queryProd = "
                                            SELECT 
                                                Producto.nombre as producto_name, 
                                                Imagenes.imagen as prod_imagen, 
                                                detalles.cantidad as seleccionados,
                                                Direccion.direccion as entrega

                                            FROM
                                                Direccion, Pedido,
                                                detalles, Producto,
                                                prod_img, Imagenes
                                                
                                            WHERE
                                                Pedido.id = $idPendiente AND Pedido.id_direccion = $idDireccion AND Pedido.id_direccion = Direccion.id
                                                AND Pedido.id = detalles.id_pedido AND detalles.id_prod = Producto.id AND 
                                                Producto.id = prod_img.id_prod AND prod_img.id_img = Imagenes.id;
                                        
                                        ";

                                        $stt = $pdo->query($queryProd);
                                        $productosPedido = $stt->fetchAll(PDO::FETCH_ASSOC);


                                        $lugarDeEntrega = $productosPedido[0]['entrega'];
                                        // empieza la tarjeta del pedido
                                        echo "
                                                    <!-- empieza pedido card -->
                                                    <div class='tarjeta-pedido'>
                                                        <div class='datos-procesos'>
                                                            <p>Número de pedido: $idPendiente</p>
                                                            <p>Fecha: $fecha</p>
                                                            <p>Entrega: $lugarDeEntrega</p>
                                                        </div>

                                                        <div class='det-pedidos'>
                                                            <div class='des-detalles-pedido'>
                                                                <p>Detalles del pedido:</p>
                                                            </div>";
                                        
                                        echo "<div class='items-pedido-detalles1'>";

                                        foreach($productosPedido as $row){
                                            echo "                                
                                                    <!-- inicia item -->
                                                    <div class='item-detalle-pedido1'>
                                                        <div class='img-item-pedido1'>
                                                            <div class='img-item-pedido21'>
                                                                <img src='data:image/jpeg;base64," . base64_encode($row['prod_imagen']) ."' alt='" . $row['producto_name'] ."'>"."
                                                            </div>
                                                        </div>

                                                        <div class='datos-item-pedido-detalle1'>
                                                            <h3>". $row['producto_name'] ."</h3>
                                                            <p>Cantidad: ". $row['seleccionados'] ." pzs.</p>
                                                        </div>
                                                    </div>
                                                    <!-- termina item -->";
                                        }
                                        echo "</div></div>";

                                        echo "<div class='estado-pedido1'>
                                                <p>Estado: Pendiente</p>
                                                <p>Por pagar: $". $fila['monto'].".00</p>
                                            </div>
                                        </div>";
                                    }
                                }else{
                                    echo "No hay pedidos pendientes.";
                                }
                            ?>           

                        
                    </div>

                </div>
            </div>

            <div id="entregadosPedidos" class="hidden">
                <div class="pedidos-pendientes">
                    <div class="titulo-pendientes-admin">
                        <h2>Entregados</h2>
                    </div>

                    <div class="pendientes-contenedor-ad">
                        <?php
                            if($pedidosEntregados){
                                foreach($pedidosEntregados as $fila){
                                    $idPendiente = $fila['id'];
                                    $idDireccion = $fila['id_direccion'];
                                    $fecha = $fila['fecha'];

                                    $queryProd = "
                                        SELECT 
                                            Producto.nombre as producto_name, 
                                            Imagenes.imagen as prod_imagen, 
                                            detalles.cantidad as seleccionados,
                                            Direccion.direccion as entrega

                                        FROM
                                            Direccion, Pedido,
                                            detalles, Producto,
                                            prod_img, Imagenes
                                            
                                        WHERE
                                            Pedido.id = $idPendiente AND Pedido.id_direccion = $idDireccion AND Pedido.id_direccion = Direccion.id
                                            AND Pedido.id = detalles.id_pedido AND detalles.id_prod = Producto.id AND 
                                            Producto.id = prod_img.id_prod AND prod_img.id_img = Imagenes.id;
                                    
                                    ";

                                    $stt = $pdo->query($queryProd);
                                    $productosPedido = $stt->fetchAll(PDO::FETCH_ASSOC);


                                    $lugarDeEntrega = $productosPedido[0]['entrega'];
                                    // empieza la tarjeta del pedido
                                    echo "
                                                <!-- empieza pedido card -->
                                                <div class='pedido-card2-ad'>
                                                    <div class='datos-miPedido-ad'>
                                                        <p>Número de pedido: $idPendiente</p>
                                                        <p>Fecha: $fecha</p>
                                                        <p>Entrega: $lugarDeEntrega</p>
                                                    </div>

                                                    <div class='detalles-pedido-contenedor-ad'>
                                                        <div class='titulo-detalles-pedido-ad'>
                                                            <p>Detalles del pedido:</p>
                                                        </div>";
                                    
                                    echo "<div class='items-pedido-detalles-ad'>";

                                    foreach($productosPedido as $row){
                                        echo "                                
                                                <!-- inicia item -->
                                                <div class='item-detalle-pedido-ad'>
                                                    <div class='img-item-pedido-ad'>
                                                        <div class='img-item-pedido2-ad'>
                                                            <img src='data:image/jpeg;base64," . base64_encode($row['prod_imagen']) ."' alt='" . $row['producto_name'] ."'>"."
                                                        </div>
                                                    </div>

                                                    <div class='datos-item-pedido-detalle-ad'>
                                                        <h3>". $row['producto_name'] ."</h3>
                                                        <p>Cantidad: ". $row['seleccionados'] ." pzs.</p>
                                                    </div>
                                                </div>
                                                <!-- termina item -->";
                                    }
                                    echo "</div></div>";

                                    echo "<div class='estado-pedido-ad'>
                                            <p>Estado: Recibido</p>
                                            <p>Pagado: $". $fila['monto'].".00</p>
                                        </div>
                                    </div>";
                                }
                            }else{
                                echo "No hay pedidos recibidos.";
                            }
                        ?>           

                    </div>
                </div>
            </div>

            <div id="estadoPedidos" class="hidden">
                <div class="estadoPedidos">
                    <form method="POST" action="">
                        <div class="seleccion-usuario">
                            <label for="usuarioss">Seleccione algun cliente:</label>
                            <select id="usuarios" name="usuarioss">
                                <option value="" disabled selected>Selecciona una categoría</option>
                                <?php
                                foreach ($clientes as $fila) {
                                    echo "<option value='" . htmlspecialchars($fila['id']) . "'>" . htmlspecialchars($fila['nombre']) . " " . htmlspecialchars($fila['apellido']) . "</option>";
            
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="container-ajuste4">
                            <div class="row">
                                <div class="col-12">
                                    <label for="descripcion">Detalles de Producto</label>
                                    <div id="usuarios_pedidos" name="descripcion_producto" class="pedidos-contenedor-principal-usuario"></div> 
                                  
                                </div>
                            </div>
                        
                        </div>

                    </form>

                </div>

            </div>
           
        </div>
        
    
    
   

    
 
