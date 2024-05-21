let paso = 1;       // Asigno primera sección por defecto

// Limito pasos dando valores max y min
let pasoInicial = 1;
let pasoFinal = 3;

// Objeto para datos de cada cita
const cita = {
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

// Esperar carga del documento
document.addEventListener('DOMContentLoaded', function () {  // Cuando documento cargado ejecuta la fon
    iniciarApp();
});

/********** función inicializadora ******************/

function iniciarApp() {
    mostrarSeccion();       // Muestra y oculta las secciones
    tabs();                 // Cambia la sección cuando se presiona los tabs
    botonesPaginador();     // Da comportamientos a los botones de la paginación
    paginaSiguiente();
    paginaAnterior();
    consultarAPI();         // Consulta la API en el backend de PHP
    idCliente();            // Lee id del campo oculto
    nombreCliente();        // Lee el nombre del input del formulario y lo guarda en objeto cita
    seleccionarFecha();     // "   "
    seleccionarHora();      // "     "
    mostrarResumen();       // muestra resumen de la cita
}

/*********************** Mostrar sección correspondiente *******************/

function mostrarSeccion() {     // Muestra la sección correspondiente a cada botón clicado y esconde las otras

    // Ocultar las secciones con clase mostrar (si no hay es que es el inicio)
    const seccionAnterior = document.querySelector('.mostrar');
    // Comprobamos si hay alguna con la clase mostrar y se la quitamos
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');    // Solo se ejecuta si hay una clase mostrar 
    }

    // Seleccionar la seccion con el dato paso

    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');       // Muestra la sección seleccionada


    // Quita la clase actual al anterior seleccionado
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }
    // Resalta el tab actual (lo hacemos aquí ya que se va a llamar a menudo)
    const tab = document.querySelector(`[data-paso="${paso}"]`);    // El tab actual,leyendo la info de paso e inyectándola
    tab.classList.add('actual');                                    // Cambio aparienecia del botón
}

// Escucha eventos en tabs
function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton => {                         // debo hacerlo uno a uno,ya que usé seletorAll
        boton.addEventListener('click', function (e) {   // Escucho evento por cada nodo 
            paso = parseInt(e.target.dataset.paso);     // Leo el paso que identifica cada botón
            mostrarSeccion();                           // Muestra la sección correspondiente
            botonesPaginador();                         // Muestra u oculta los botones prev y sig
        })
    });
}


//****************** Paginación ********************/ 


function botonesPaginador() {
    // Registro los botones
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if (paso === 1) {
        paginaAnterior.classList.add('ocultar');        // Si es inicio el botón anterior no se ve
        paginaSiguiente.classList.remove('ocultar');    // Vuelve a verse el botón sig
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');     // Se ve el btn anterior
        paginaSiguiente.classList.add('ocultar');       // Si es la última opción oculto botón siguiente
        mostrarResumen();                               // Valida y muestra resumen
    } else {
        paginaAnterior.classList.remove('ocultar');     // Se ven los dos botones
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

// Botón anterior tab
function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function () {
        if (paso <= pasoInicial) return;
        paso--;            // Resto 1
        botonesPaginador();
    });
}

// Botón siguiente tab
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function () {
        if (paso >= pasoFinal) return;
        paso++;            // Resto 1
        botonesPaginador();
    });
}

/********* API **********/

// Consulta la API en el backend de PHP (La hacemos asíncrona ya que no sabemos el tiempo que va a consumir)
async function consultarAPI() {
    // Try catch previene de errores pero sólo lo usamos en cosas críticas
    try {
        const url = '/api/servicios';                       // URL donde está el servicio de la API
        const resultado = await fetch(url);                // Await sólo funciona si es async la fon y viceversa
        // Await espera hasta que acabe para seguir la ejecución del código
        const servicios = await resultado.json();          // Paso de json a array
        mostrarServicios(servicios);                        // 
    } catch (error) {
        console.log(error);
    }
}

/******************* Muestra los servicios en HTML desde la BD ***************/

// Función para mostrar los servicios del resultado
function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;             // Distruption de los objetos del array servicio, lo separo en variables

        // Listo en párrafos los nombres de los servicios
        const nombreServicio = document.createElement('P'); // Creamos un párrafo
        nombreServicio.classList.add('nombre-servicio');    // Damos estilos CSS
        nombreServicio.textContent = nombre;

        // Listo en párrafos los precios
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `${precio}€`;

        // Creo div contenedor
        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        // Añado onclick a cada div de servicio
        servicioDiv.onclick = function () {  // Debo hacerlo con un callback o no funcionará
            seleccionarServicio(servicio);  // Si pongo esta línea sin estar dentro de un callback no funciona,devuelve todos los servicios de la BD
        };

        // Mostrar en pantalla
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        // Selecciono en donde lo muestro (vista index de carpeta cita con el id=servicios)
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

/************* Selección/Deselección de servicios ******************/

// Selección de servicio
function seleccionarServicio(servicio) {
    const { id } = servicio;                        // Extraigo id del objeto servicio
    const { servicios } = cita;                     // Extraigo el array servicios del objeto cita

    // Resaltar el servicio seleccionado
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Comprobar si un servicio ya fué añadido ( array servicios los contiene )
    if (servicios.some(agregado => agregado.id === id)) {    // Si el id del servicio que selecciono ya está es que fué añadido antes
        // Eliminarlo si ya estaba                              // agregado es lo que está en MEMORIA
        cita.servicios = servicios.filter(agregado => agregado.id !== id); // Deja sólo los servicios distintos al seleccionado
        divServicio.classList.remove('seleccionado');           // Quita el resaltado
    } else {
        // Agrego servicio al array servicios del objeto cita
        cita.servicios = [...servicios, servicio];   // Lo que ya hay en servicios(usando el spread operator)  
        // y agrego el nuevo servicio
        divServicio.classList.add('seleccionado');  // Resalta el seleccionado
    }

}

/******* Leer y asignar nombre del cliente ********/

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;     // Leo el nombre desde el input del formulario
}

//***** Igual pero con el ID ******/

function idCliente() {
    cita.id = document.querySelector('#id').value;     // Leo el  desdeid el input del formulario
}

/************ Comprobar fecha es válida y guardarla ****************/

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function (e) {
        // Comprobar que son días válidos en que se abre
        const dia = new Date(e.target.value).getUTCDay();   // getUTCDay nos dice del 0 al 6 que dia de la semana es
        if ([6, 0].includes(dia)) {    // Si es Domingo(0) o sábado(6),
            e.target.value = '';
            mostrarAlerta('Fines de semana no abrimos', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;        // Es válida y la guardo
        }
    })
}

/**** mostrar alerta *****/
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    // Vemos si ya hay una alerta
    const alertaPrevia = document.querySelector('.alerta');
    // Si hay la quitamos
    if (alertaPrevia) {
        alertaPrevia.remove();
    }
    // Si no había alerta scripting para crearla
    const alerta = document.createElement('DIV');   // Creamos el div
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);;
    // Mostrarlo en pantalla
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);
    // Eliminar pasados 3 sg
    if (desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

}

// Selección de fecha
function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function (e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];      // separa un string en un array con las partes a un lado y otro del carácter dado
        // Comprobamos
        if (hora < 10 || hora > 18) {
            // Hora no válida
            e.target.value = '';        // Para que no salga en el input
            mostrarAlerta("Hora no válida", "error", ".formulario");
        } else {
            // Válida
            cita.hora = horaCita; // asignamos hora
        }
    })
}

/*********** Mostrar resumen de la cita *************/

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiar el contenido de resumen, incluida la alerta
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    // Compruebo que todos los datos se rellenaron y se seleccionó al menos un servicio
    if (Object.values(cita).includes('') || cita.servicios.length === 0) {  // Object values itera todos los valores de un objeto por separado
        // Mensaje de error
        mostrarAlerta("faltan datos o no se seleccionó ningún servicio", "error", ".contenido-resumen", false);
        return;
    };

    // Scripting muestra de resumen de cita en un DIV
    const { nombre, fecha, hora, servicios } = cita;

    // Heading para servicios en resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = "Resumen de servicios";
    resumen.appendChild(headingServicios);

    // Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = document.createElement('DIV');   // Un div para cada servicio
        contenedorServicio.classList.add('contenedor-servicio');   // CSS
        const textoServicio = document.createElement('P');          // creo p de servicio/s
        textoServicio.textContent = nombre;                         // Nombre servicio
        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> ${precio}€`;

        // Mostramos en HTML
        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    // Heading para cita en resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = "Resumen de cita";
    resumen.appendChild(headingCita);

    // Scripting cita
    // Nombre
    const nombreCliente = document.createElement('P');        // creo p del nombre
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;
    // Fecha (Formateada a español)
    const fechaObj = new Date(fecha);                      // Creo objeto con los datos de la fecha que introdujo el cliente,tiene un desfase de un día
    const mes = fechaObj.getMonth();                     // Leo mes,que tiene un dia menos pues comienza en cero el Date
    const dia = fechaObj.getDate() + 2;                  // Leo dia  "       "    y sumo los 2 de desfase (los 2 Date que uso)
    const year = fechaObj.getFullYear();                 // Este no tiene desfase

    const fechaUTC = new Date(Date.UTC(year, mes, dia));
    // Formateando a Español
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' } // Digo como mostrar los datos de la fecha
    const fechaFormateada = fechaUTC.toLocaleDateString('es-ES', opciones);       // es-ES es el formato para España    

    // Creando HTML
    const fechaCita = document.createElement('P');                  // creo p del fecha
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;
    // Hora
    const horaCita = document.createElement('P');                   // creo p del hora
    horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;

    // Botón para crear y almacenar la cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar cita';
    botonReservar.onclick = reservarCita;

    // Mostrar en HTML

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}


/**** Reservar la cita y guardar en BD ******/

async function reservarCita() {

    const { fecha, hora, servicios, id } = cita;

    // Lo que guardamos en servicios por su id
    const idServicios = servicios.map(servicio => servicio.id);

    // Crear petición para almacenar mediante la API fetch
    const datos = new FormData();               // Forma de pasar clave y valor en petición (objeto)
    // Añadimos datos al formData
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    // Por si falla

    try {
        // Petición hacia la API
        const url = '/api/citas';                       // Funciona si tenemos el backend en el mismo dominio
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos                                 // Cuerpo de la petición
        });

        const resultado = await respuesta.json();
        console.log(resultado);
        if (resultado.resultado) {
            console.log("hubo resultado");
            // Alerta con sweetalert
            Swal.fire({
                icon: "success",
                title: "Cita creada",
                text: "Tu cita fué creada correctamente",
                button: "OK"
            }).then(() => {
                setTimeout(() => {
                    window.location.reload();   // así no permite duplicar citas,recarga la cita y borra los datos
                }, 2000);
            })
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "La cita no se pudo crear",
            text: "Hubo un error al guardar la cita"
        });

        // console.log([...datos]);
    }
}