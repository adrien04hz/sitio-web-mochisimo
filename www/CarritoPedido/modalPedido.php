    <div id="modal" class="exitoso">
        <div class="exito">
            <p>¡Su pedido fue registrado con éxito!</p>
            <p>Comparta su experiencia por medio de una reseña.</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            let modal = document.getElementById("modal");
            modal.style.bottom = "0"; // Mostrar modal automáticamente

            // Ocultar después de 3 segundos
            setTimeout(() => {
                modal.style.bottom = "-150px";
            }, 6000);
        };
    </script>