document.addEventListener('DOMContentLoaded',function(){
    iniciarApp();
})

function iniciarApp() {
    buscarPorFecha()
}

function buscarPorFecha(params) {
    const fechaInput=document.querySelector('#fecha');
    fechaInput.addEventListener('input',function(e){
        const fechaSeleccionada=e.target.value;
        window.location=`?fecha=${fechaSeleccionada}`; //Pasa la fecha seleccionada a la URL de la página para pueda ser leida por el controlador
    });
}