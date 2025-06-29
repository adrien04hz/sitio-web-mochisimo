//Seleccionar todos los botones e inputs
const btnSuma = document.querySelectorAll('.suma');
const btnResta = document.querySelectorAll('.resta');




//evento para aumentar la cantidad
btnSuma.forEach(boton => {
    boton.addEventListener('click', () => {
        //obtener el id del input correspondiente
        const id = boton.getAttribute('data-id');
        const input = document.getElementById(`stock-${id}a`);

        input.value = 1;

    });
});


btnResta.forEach(boton => {
    boton.addEventListener('click', () => {
        //obtener el id del input correspondiente
        const id = boton.getAttribute('data-id');
        const input = document.getElementById(`stock-${id}a`);
        const input2 = document.getElementById(`stock-${id}`);

        if(input2.value > 1){
            input.value = -1;
        }else{
            input.value = 0;
        }
    });
});
