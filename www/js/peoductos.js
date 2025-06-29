// const categ = document.getElementById('categoria_producto');
// categ.addEventListener('change', Modificar);

// const producto_categoria = document.getElementById('producto_categoria');
// producto_categoria.addEventListener('change', Descripcion);


// const descripcion_producto = document.getElementById('descripcion_producto');

// const productos_categ = document.getElementById('productos_categ');
// productos_categ.addEventListener('change', Detalles);

// const productos_categ1 = document.getElementById('productos_categ1');



// //aqui vamos a hacer que se mande la informacion del cliente
// const usuario = document.getElementById('usuario');
// usuario.addEventListener('change', MostrarPedidos());

// const usuarios_pedidos = document.getElementById('usuario_pedidos');

// function fetchAndSetData(url, formData, targetElement) {
//     return fetch(url, {
//         method: "POST",
//         body: formData,
//         mode: 'cors'
//     })
//     .then(response => response.json())
//     .then(data => {
//         targetElement.innerHTML = data;
//     })
//     .catch(err => console.log(err));
// }
// function fetchAndSetData2(url, formData, targetElement) {
//     return fetch(url, {
//         method: "POST",
//         body: formData,
//         mode: 'cors'
//     })
//     .then(response => response.text()) // Usar text() ya que es HTML lo que se devuelve
//     .then(data => {
//         targetElement.innerHTML = data; // Se inserta el HTML recibido en el contenedor
//     })
//     .catch(err => console.log(err));
// }

// function Modificar() {
//     let categoriaId = categ.value;
//     let url = 'Admin/Consultas/MMostrarProductos.php';
    
//     let formData = new FormData();
//     formData.append('categoria_producto', categoriaId);

//     fetchAndSetData(url, formData, producto_categoria);
// }


// function Descripcion() {
//     let productoId = producto_categoria.value;
//     let url = 'Admin/Consultas/MMostrarDes.php';

//     let formData = new FormData();
//     formData.append('producto_categoria', productoId);

//     fetchAndSetData(url, formData, descripcion_producto);
// }


// function Detalles() {
//     let detallesId = productos_categ.value;
//     let url = 'Admin/Consultas/MProductos.php';

//     let formData = new FormData();
//     formData.append('productos_categ', detallesId);

//     fetchAndSetData2(url, formData, productos_categ1);
// }


// function MostrarPedidos() {
//     let usuarioId = usuario.value;
//     let url = 'Admin/Pedidos/Usuario.php';

//     let formData = new FormData();
//     formData.append('usuario', usuarioId);

//     fetchAndSetData2(url, formData, usuario_pedidos);
// }

document.addEventListener('DOMContentLoaded', function() {
    const categ = document.getElementById('categoria_producto');
    if(categ)
    categ.addEventListener('change', Modificar);

    const producto_categoria = document.getElementById('producto_categoria');
    if(producto_categoria)
    producto_categoria.addEventListener('change', Descripcion);

    const descripcion_producto = document.getElementById('descripcion_producto');

    const productos_categ = document.getElementById('productos_categ');
    if(productos_categ)
    productos_categ.addEventListener('change', Detalles);

    const productos_categ1 = document.getElementById('productos_categ1');

    // Aquí vamos a hacer que se mande la información del cliente
    const usuario = document.getElementById('usuarios');
    if(usuario)
    usuario.addEventListener('change', MostrarPedidos); // Corrección: pasar la función como referencia, sin los paréntesis

    const usuario_pedidos = document.getElementById('usuarios_pedidos');

    function fetchAndSetData(url, formData, targetElement) {
        return fetch(url, {
            method: "POST",
            body: formData,
            mode: 'cors'
        })
        .then(response => response.json())
        .then(data => {
            targetElement.innerHTML = data;
        })
        .catch(err => console.log(err));
    }

    function fetchAndSetData2(url, formData, targetElement) {
        return fetch(url, {
            method: "POST",
            body: formData,
            mode: 'cors'
        })
        .then(response => response.text()) // Usar text() ya que es HTML lo que se devuelve
        .then(data => {
            targetElement.innerHTML = data; // Se inserta el HTML recibido en el contenedor
        })
        .catch(err => console.log(err));
    }

    function Modificar() {
        let categoriaId = categ.value;

        console.log('ID de usuario enviado:', categoriaId); // Verificar valor en consola
    
        let url = 'Admin/Consultas/MMostrarProductos.php';
        
        let formData = new FormData();
        formData.append('categoria_producto', categoriaId);

        fetchAndSetData(url, formData, producto_categoria);
    }

    function Descripcion() {
        let productoId = producto_categoria.value;
        let url = 'Admin/Consultas/MMostrarDes.php';

        let formData = new FormData();
        formData.append('producto_categoria', productoId);

        fetchAndSetData(url, formData, descripcion_producto);
    }

    function Detalles() {
        let detallesId = productos_categ.value;
        let url = 'Admin/Consultas/MProductos.php';

        let formData = new FormData();
        formData.append('productos_categ', detallesId);

        fetchAndSetData2(url, formData, productos_categ1);
    }

    // function MostrarPedidos() {
    //     let usuarioId = usuario.value;
    //     let url = 'Admin/Pedidos/Usuarios.php';

    //     let formData = new FormData();
    //     formData.append('usuario', usuarioId);

    //     fetchAndSetData2(url, formData, usuarios_pedidos);
    // }
    function MostrarPedidos() {
        let usuarioId = usuario.value;
        console.log('ID de usuario enviado:', usuarioId); // Verificar valor en consola
    
        let url = 'Admin/Pedidos/Usuarios.php';
        let formData = new FormData();
        formData.append('usuario', usuarioId);
    
        fetchAndSetData2(url, formData, usuario_pedidos);
    }
    
});
