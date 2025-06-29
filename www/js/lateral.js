document.addEventListener("DOMContentLoaded", function () {
    let params = new URLSearchParams(window.location.search);
    
    if (params.has("exist") || params.has("img_cat")) {
        showContent("agregarProducto");  
    } else if (params.has("imgnueva_cat")) {
        showContent("modificarProducto");    
    }else if(params.has("id_estado")){
        showContent("estadoPedidos");   
    }
        else {
        let mainContent = document.querySelector('main');
        if (mainContent) {
            mainContent.classList.remove('hidden'); 
        }
    }

    // Agregar eventos a los elementos del menú
    document.querySelectorAll('.submenu li').forEach(item => {
        item.addEventListener('click', function () {
            let contentId = this.getAttribute('data-id');
            if (contentId) {
                showContent(contentId);
            }
        });
    });
});

// Función para mostrar/ocultar submenús
function toggleMenu(menuId) {
    let menu = document.getElementById(menuId);
    if (!menu) return;
    menu.classList.toggle("show");

    // Evitar que el evento se propague y cierre el menú inmediatamente
    menu.addEventListener("click", function (event) {
        event.stopPropagation();
    });
}

// Función para cambiar el contenido mostrado
function showContent(contentId) {
    let sections = document.querySelectorAll('.content-general > div');
    let content = document.getElementById(contentId);

    if (!content) {
        console.error(`No se encontró el contenido con id: ${contentId}`);
        return;
    }

    sections.forEach(section => section.classList.add('hidden'));
    content.classList.remove('hidden');

    let mainContent = document.querySelector('main');
    if (mainContent) {
        mainContent.classList.add('hidden');
    }

    closeAllMenus();
}

// Función para cerrar todos los menús
function closeAllMenus() {
    let submenus = document.querySelectorAll('.submenu');
    submenus.forEach(menu => {
        menu.classList.remove("show");
    });
}

// Cerrar submenús al hacer clic fuera del menú lateral
document.addEventListener('click', function(event) {
    let sidebar = document.querySelector('.lateral');
    if (!sidebar) return;

    if (!sidebar.contains(event.target)) {
        closeAllMenus();
    }
});
