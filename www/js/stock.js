const input= document.getElementById('stock');
const autoincrementar= document.getElementById('auincrementar');
const decrementar = document.getElementById('dcrementar');
const limitDetails = document.getElementById('details_limit');


if(autoincrementar){
    autoincrementar.addEventListener('click', ()=>{
        if(input.value < limitDetails.value){
            input.value = parseInt(input.value) + 1;
        }
    });
}


if(decrementar){
    decrementar.addEventListener('click', ()=>{
        if(input.value>1){
            input.value = parseInt(input.value) - 1;
        }
       
    });
}
