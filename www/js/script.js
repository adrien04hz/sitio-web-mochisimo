//se obtiene el boton para abrir carrito
const enlace = document.getElementById("carrito");
//lugar donde se muestra el carrito
const div = document.getElementById("carrito-prods");
//fondo que opaca la pagina
const opaco = document.getElementById('opaco');
//cuerpo del documento
const body = document.body;
//boton para continuar comprando
const continuar = document.getElementById('continuar');
//boton para cerrar el carrito
const btn_close = document.getElementById('btn-close');
//boton para añadir item al carrito
const anadir = document.getElementById('add_prod');
//boton para confirmar pedido
const confirma = document.getElementById('confirmar');
//fondo flores
const flor = document.getElementById('opaco2');
//confirmacion carta
const cuadro = document.getElementById('confirm_card');
//boton para cerrar la confirmacion
const cancelar = document.getElementById('cerrar_confirmar');
//boton cuando la sesion no esta iniciada
const muestra_iniciar_sesion = document.getElementById('iniciar_sesion');
//div para el boton de iniciar sesion
const iniciaSesion = document.getElementById('btn_requisito');
//boton para redirigir al inicio de sesion
const ini = document.getElementById('btn_dirigeLogin');
//boton de login
const log = document.getElementById('entrar_cuenta');

//fondo para contenido de blog
const fondo = document.getElementById('contenidoBlog');
//boton para abrir el contenido
const btnLeer = document.getElementById('leerMas');
//div del contenido a mostrar
const divLeer = document.getElementById('contenidoSel');


if(btnLeer){
	btnLeer.addEventListener('click', () => {
		fondo.style.display = "block";
		body.style.overflow = "hidden";
		divLeer.style.top = "50%";
		divLeer.style.animation = "entrada 5s cubic-bezier(0.25, 0.8, 0.25, 1)";
		// divLeer.style.display = "flex";
	});
}


if(fondo && divLeer){
	fondo.addEventListener('click', (event) => {
		if(event.target === fondo){
			fondo.style.display = "none";
			body.style.overflow = "";
			// divLeer.style.display = "";
			divLeer.style.animation = "salida 0.4s cubic-bezier(0.25, 0.01, 0.5, 1)";
			divLeer.style.top = "-50%";
			
		}
	});

	fondo.style.display = "block";
	body.style.overflow = "hidden";
	divLeer.style.top = "50%";
	divLeer.style.animation = "entrada 0.4s cubic-bezier(0.25, 0.01, 0.5, 1)";
}




document.addEventListener("DOMContentLoaded", () =>{
	const carritoAbierto = localStorage.getItem("cartOpen") === "true";

	if(carritoAbierto){
		div.style.right = "0";
		opaco.style.display = "block";
		body.style.overflow = "hidden";
	}
});


if(continuar){
	continuar.addEventListener('click', () => {
		div.style.right = "-500px";
		opaco.style.display = "none";
		body.style.overflow = "";
		localStorage.setItem("cartOpen","false");
	});
}

if(opaco){
	opaco.addEventListener('click', (event) => {
		if(event.target === opaco){
			if(iniciaSesion){
				iniciaSesion.style.bottom = "-150px";
			}
			div.style.right = "-500px";
			opaco.style.display = "none";
			body.style.overflow = "";
			localStorage.setItem("cartOpen","false");

		}
	});
}


if(enlace){
	
	enlace.addEventListener('click', (event) => {
		event.preventDefault();
		div.style.right = "0";
		opaco.style.display = "block";
		body.style.overflow = "hidden";
		localStorage.setItem("cartOpen","true");
	});
}

if(btn_close){

	btn_close.addEventListener('click', (event) => {
		if(iniciaSesion){
			iniciaSesion.style.bottom = "-150px";
		}
		div.style.right = "-500px";
		opaco.style.display = "none";
		body.style.overflow = "";
		localStorage.setItem("cartOpen","false");
	
	});
}

if(anadir){
	anadir.addEventListener('click', () => {
		localStorage.setItem("cartOpen","true");
	});
}

if(confirma){
	confirma.addEventListener('click', () => {
		flor.style.display = "block";
		cuadro.style.display = "flex";
	});
}

if(cancelar){
	cancelar.addEventListener('click', () => {
		flor.style.display = "none";
		cuadro.style.display = "none";
	});
}

if(muestra_iniciar_sesion){
	muestra_iniciar_sesion.addEventListener('click', () => {
		iniciaSesion.style.bottom = "0";
	});
}


if(ini){
	ini.addEventListener('click', () => {
		localStorage.setItem("cartOpen","false");
		localStorage.setItem("compraProceso", "true");
		window.location.href = "../Login/User.php";
	});
}

if(log){
	log.addEventListener('click', () => {
		const abrir = localStorage.getItem('compraProceso') === "true";

		if(abrir){
			localStorage.setItem('cartOpen', 'true');
			localStorage.removeItem('compraProceso');
		}
	});
}