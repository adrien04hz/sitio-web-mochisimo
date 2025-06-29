<div id="btn_requisito" class="pedido-iniciar">
    <?php
        if(!isset($_SESSION['id_cliente'])){
            $_SESSION['mandar_a'] = $_SERVER['REQUEST_URI'];
        }
    ?>
    <button id="btn_dirigeLogin" class="iniSesion" type="button">Para realizar pedido, inicia sesión aquí</button>
</div>