let paso=1; //Variable global que será rescrita al dar click en un tab de la navegación
const pasoInicial=1;//Valor minimo que puede tomar la variable paso
const pasoFinal=3;//Valor máximo que puede tomar la variable paso

const cita={ //Objeto que almacena
    id:'',
    nombre:'',
    fecha:'',
    hora:'',
    servicios:[]
}

document.addEventListener('DOMContentLoaded',function(){
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion(); //Oculta o muestra sección inicialmente, antes de que se de click en algún Tab(por default paso=1)
    tabs(); // Cambia la sección cuando se presionen los Tabs
    botonesPaginador(); //Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();//Consulta la tabla de servicios en MYSQL, denotada mediante una API REST.

    idCliente();
    nombreCliente();//Añade el nombre del cliente al objeto de la cita.
    seleccionarFecha();//Añade la fecha de la cita en el objeto.
    seleccionarHora();//Añade la hora de la cita en el objeto.

    mostrarResumen();//Muestra resumen de la cita

}

function mostrarSeccion() {

    // Se oculta la sección que tiene previamente la clase de .mostrar
    const seccionAnterior=document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');

    }
 
   const seccion= document.querySelector(`#paso-${paso}`); //Obtiene la sección que hay sido seleccionada en los tabs o la cargada por default paso=1
   seccion.classList.add('mostrar'); //Añade clase que aplica un display block, al bloque que antes tenia un display:none

    //Quitar la clase .actual al tab anterior
    const tabAnterior=document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

   //Resalta el tab actual
    const tab=document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');

}

function tabs() {
    const botones=document.querySelectorAll('.tabs button');
    botones.forEach(boton=>{
        boton.addEventListener('click',function(e){
            paso= parseInt(e.target.dataset.paso);   //Obtiene al valor asignado al atributo personalizado data-paso y lo convierte a entero. 
            mostrarSeccion(); //Se manda llamar /de nueva cuenta por que es necesario actuali<ar el valor de la variable paso, para efectuar los correspondientes cambios de sección
            botonesPaginador();
           
        })
    
    })
}

function botonesPaginador() {
    const paginaAnterior=document.querySelector('#anterior');
    const paginaSiguiente=document.querySelector('#siguiente');

    if(paso===1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar')
    }else if(paso===3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion(); //Para que se vincule con las funciones de páginaAnterior y paginaSiguiente, de manera que está a su vez va ser responsiva al evento click de ambas.

}

function paginaAnterior() {
    const paginaAnterior=document.querySelector('#anterior');

    paginaAnterior.addEventListener('click',function () {

        if(paso <= pasoInicial) return;
        paso--;
        

        botonesPaginador(); //Para hacer que se muestre los botones pertinentes en base al valor del paso dado por esta función
    })
}

function paginaSiguiente() {
    const paginaSiguiente=document.querySelector('#siguiente');

    paginaSiguiente.addEventListener('click',function () {

        if(paso >= pasoFinal) return;
        paso++;

        botonesPaginador(); //Para hacer que se muestre los botones pertinentes en base al valor del paso dado por esta función
    })
}

async function consultarAPI(){
    try {
        const url='http://localhost:3000/api/servicios';
        resultado= await fetch(url); //Obtiene objeto con multiples objetos y métodos referente al estado de la conexión
        servicios= await resultado.json(); //Convierte el String Json, en un objeto manipulable en JavaScript
        mostrarServicios(servicios)
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio=>{

        const {id, nombre, precio}=servicio;

        //Se crea parrafo con el nombre del servicio
        const nombreServicio=document.createElement('p');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent=nombre;

        //Se crea parrafo con el precio del servicio
        const precioServicio=document.createElement('p');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent=`$${precio}`;

        //Se crea el contenedor para cada servicio
        const servicioDiv=document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio=id;

        //Se agrupan los precios y nombres en sus contenedores
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        //Se añade evento de click para seleccionar los servicios
        servicioDiv.onclick=function () { //Con esta notación es necesario definir un callBack para llamar una funcion dentro de esta
            seleccionarServicio(servicio);
        }
        

        document.querySelector('#servicios').appendChild(servicioDiv);


    });
}


function seleccionarServicio(servicio) {
    const {id}=servicio;
    const {servicios}=cita;

    
    //Identificar elemento que se le da click
    const divServicio=document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio ya fue agregado
    if(servicios.some(agregado=> agregado.id===id)){
        //Eliminarlo
        cita.servicios=servicios.filter(agregado=>agregado.id!==id);

        divServicio.classList.remove('seleccionado');
    }else{
        //Agregarlo

        cita.servicios=[...servicios,servicio];

        divServicio.classList.add('seleccionado');
    }


    
}

function idCliente() {
    cita.id=document.querySelector('#id').value;
}
function nombreCliente(){
     cita.nombre=document.querySelector('#nombre').value;
}

function seleccionarFecha() {
    const inputFecha=document.querySelector('#fecha');
    inputFecha.addEventListener('input',function (e){
        const dia=new Date(e.target.value).getUTCDay(); //Obtiene dia de la semana en función de la fecha ingresada en el input( formato 0= domingo, 1=lunes, etc..)
        if([6,0].includes(dia)){
            e.target.value=''; //Evita que el usuario almacene la fecha de los dias sabados y domingos(6,0)
            mostrarAlerta('Fines de semana no permitidos','error','.formulario');
        }else{
            cita.fecha=e.target.value;
        }

    });
}

function seleccionarHora() {
    const inputHora=document.querySelector('#hora');
    inputHora.addEventListener('input',function(e){
        const horaCita=e.target.value;
        const hora=horaCita.split(":")[0];

        if(hora<10 || hora>18){
            e.target.value='';
            mostrarAlerta('Hora no Válida','error','.formulario');
        }else{
            cita.hora=e.target.value;
        }
    });
}

function mostrarAlerta(mensaje,tipo,elemento,desaparece=true){

    //Previene que se genere más de 1 alerta
    const alertaPrevia=document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }


    //Scripting para crear una alerta
    const alerta=document.createElement('DIV');
    alerta.textContent=mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia=document.querySelector(elemento);
    referencia.appendChild(alerta);

    //Eliminar alerta
    if(desaparece){
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

}

function mostrarResumen() {
    const resumen=document.querySelector('.contenido-resumen');

    //Limpiar contenido del resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length===0){  
        mostrarAlerta('Faltan datos de servicios,Fecha u Hora','error','.contenido-resumen',false);
        return;
    }

    const {nombre, fecha, hora, servicios}=cita;

    //Heading para servicios en resumen
    const headingServicios=document.createElement('H3');
    headingServicios.textContent='Resumen de Servicios';
    resumen.appendChild(headingServicios);


    //Iterando y mostrando los servicios
    servicios.forEach(servicio=>{
        const {id,precio,nombre}=servicio;
        const contenedorServicio=document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio=document.createElement('P');
        textoServicio.textContent=nombre;

        const precioServicio=document.createElement('P');
        precioServicio.innerHTML=`<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        
        resumen.appendChild(contenedorServicio);
    });

    //Heading para cita en resumen
    const headingCita=document.createElement('H3');
    headingCita.textContent='Resumen de Cita';
    resumen.appendChild(headingCita);

    
    const nombreCliente=document.createElement('P');
    nombreCliente.innerHTML=`<span>Nombre:</span> ${nombre}`;

    //-----------Formatear la fecha en español-----------
    const fechaObj=new Date(fecha);
    
    const mes=fechaObj.getMonth();
    const dia=fechaObj.getDate() +2; //Dado a que al instancia el objeto Date se tiene un desfase de un día dado a que es un arreglo que empieza en 0.
    const year=fechaObj.getFullYear();

    const fechaUTC=new Date(Date.UTC(year,mes,dia)); //Fecha en formato UNIX (segunda instanciación del objeto date())

    const opciones={weekday:'long',year:'numeric',month:'long',day:'numeric'};
    const fechaFormateada=fechaUTC.toLocaleDateString('es-MX',opciones); //Este método solo es aplicable en fecha en formato UNIX

    const fechaCliente=document.createElement('P');
    fechaCliente.innerHTML=`<span>Fecha:</span> ${fechaFormateada}`;

    const horaCliente=document.createElement('P');
    horaCliente.innerHTML=`<span>Hora:</span> ${hora} Horas`;

    //Botón para crear una cita
    const botonReservar=document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent="Reservar cita";
    botonReservar.onclick=reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);

    resumen.appendChild(botonReservar);



}

async function reservarCita() {
    const datos=new FormData(); //Objeto que permitirá enviar datos a la API previamente creada.

    const {id,nombre,fecha,hora,servicios}=cita;

    const idServicios= servicios.map(servicios=>servicios.id)

    datos.append('usuarioId',id); //"append(llave:valor)"
    datos.append('fecha',fecha); //"append(llave:valor)"
    datos.append('hora',hora); //"append(llave:valor)"
    datos.append('servicios',idServicios); //"append(llave:valor)" solo se mandan las llaves de los servicios, puesto que están son las que se inserta en la tabla pivote citaServicios de la BD

    
    try {
          
        //Petición hacia la API
        const url="http://localhost:3000/api/citas";

        const respuesta=await fetch(url,{
            method:"POST",
            body:datos
        });

        const resultado=await respuesta.json();
           
    
        if(resultado.resultado) {//Dado que la operación de guardar() de activeRecord retorna un arreglo asociativo con [resultado y id , que se convierte a un objeto JSON, 
            Swal.fire({
            icon: 'success',
            title: 'Cita Creada',
            text: 'Tu cita fue creada correctamente',
            button:'Ok'
          }).then(()=>{
            setTimeout(() => {
                window.location.reload()});
            }, 3000);
            
        }
        //console.log([...datos]); Forma de corroborar los datos que serán enviados a la API
    } catch (error) {
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita',
            
          })
    }

}