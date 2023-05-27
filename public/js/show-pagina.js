import { Sortable, Swap } from "./sortable.esm.js";


// : Funcionalidad para ir al día anterior/siguiente
let btnDiaAnterior = document.getElementById('btnDiaAnterior');
let btnDiaSiguiente = document.getElementById('btnDiaSiguiente');

btnDiaAnterior.addEventListener('click', evt => {
    window.livewire.emit('cambiar-dia', true);
});

btnDiaSiguiente.addEventListener('click', evt => {
    window.livewire.emit('cambiar-dia', false);
});

document.addEventListener('dia-futuro', evt => {
    alert('¡No se pueden añadir entradas a días futuros!');
});

// .                                                                                   
// . FUNCIONES PARA GESTIONAR ENTRADAS                                                 
// .                                                                                   

// : Funcionalidad de creación de entrada 
// A través de este código JavaScript me propongo a crear un botón dinámico que al ser pulsado se convierta en una nueva entrada.
// Cuando dejamos de editar el campo de texto se guardará la nueva entrada y el botón de añadir entrada reaparecerá debajo.

document.getElementById("btnAñadir").addEventListener("click", () => {
    // Al pulsar el botón de añadir la entrada, lo oculto.
    const btnAñadir = document.getElementById("btnAñadir");
    btnAñadir.classList.add("hidden");

    // A continuación clono mi div de plantilla de entrada vacía.
    // Así me ahorro generarla de nuevo con todos sus hijos y atributos a través de JS.
    const plantillaNuevaEntrada = document.getElementById("plantillaNuevaEntrada");
    const divNuevaEntrada = plantillaNuevaEntrada.childNodes[1].cloneNode(true);

    // Obtengo el div de lista de entradas y añado el clon de mi plantilla a la lista.
    const listaEntradas = document.getElementById("listaEntradas");
    listaEntradas.appendChild(divNuevaEntrada);

    // Revelo exlusivamente el textarea, cuando se guarde la entrada nueva se revelarán los otros botones.
    const handle = document.getElementById("-handle");
    handle.classList.add("hidden");

    const dropdown = document.getElementById("-dropdown");
    dropdown.classList.add("hidden");

    const btnGestor = document.getElementById("btnGestor-");
    btnGestor.classList.add("hidden");

    divNuevaEntrada.classList.remove("hidden");

    // Obtengo el textarea de la entrada nueva y lo enfoco automáticamente.
    const nuevaEntrada = divNuevaEntrada.getElementsByTagName("textarea")[0];
    nuevaEntrada.classList.remove("border-l-0");
    nuevaEntrada.classList.remove("border-r-0");
    nuevaEntrada.classList.add("rounded");
    nuevaEntrada.removeAttribute("readonly");

    nuevaEntrada.focus();

    // Añado event listener para hacer la entrada editable.
    nuevaEntrada.addEventListener('focus', () => {
        editarEntrada(nuevaEntrada);
    });

    // Añado event listener para poder guardar la entrada.
    // El método guardarEntrada() enviará al backend un evento que ejecutará el método para crear una nueva entrada.
    nuevaEntrada.addEventListener('blur', () => {
        guardarEntrada(nuevaEntrada);
    });

    // Cuando el método guardar() del backend termine, enviará de vuelta la id de la entrada creada en la BD, entonces, reemplazaré
    // la id temporal de este div 'nuevaEntrada' por la id de la BD para poder editarla y reordenarla correctamente, luego borraré
    // este event-listener para poder crear nuevas entradas sin que interfieran con las anteriormente creadas.
    window.addEventListener('nueva-id', (evento) => {
        divNuevaEntrada.setAttribute('id', evento.detail.nuevaId);

        handle.setAttribute('id', evento.detail.nuevaId + '-handle');

        if (window.innerWidth > 640)
            handle.classList.remove('hidden');

        nuevaEntrada.setAttribute('id', evento.detail.nuevaId + '-contenido');
        nuevaEntrada.classList.add("border-l-0");
        nuevaEntrada.classList.add("border-r-0");
        nuevaEntrada.classList.remove("rounded");

        dropdown.setAttribute('id', evento.detail.nuevaId + '-dropdown');
        // dropdown.classList.remove('hidden');

        btnGestor.setAttribute('id', 'btnGestor-' + evento.detail.nuevaId);
        btnGestor.setAttribute('wire:click', `$emit('toggleGestor', true, ${evento.detail.nuevaId})`);
        btnGestor.classList.remove("hidden");

        observadores.push(new ResizeObserver(() => { redimensionar(nuevaEntrada, handle, dropdown) }).observe(nuevaEntrada));


        nuevaEntrada.removeEventListener('nueva-id', evento);
    });
});

// : Funcionalidades de edición de entrada 
// Obtengo todas las entradas y les añado sus funcionalidades (edición, guardar, etc..)
// Añado los eventos que llaman a las funciones que hacen a las entradas dinámicas, estos eventos se asignarán a cada entrada al cargar la página.

const entradas = document.getElementsByClassName("entrada");

document.addEventListener('DOMContentLoaded', () => {
    añadirFuncionalidades();
});

function añadirFuncionalidades() {
    Array.from(entradas).forEach((entrada) => {

        // Funcionalidad para editar la entrada.
        let ultimoClick = 0;
        let delay = 300;

        entrada.addEventListener("click", function (e) {
            if (entrada.getAttribute("readonly") != undefined && entrada.getAttribute("readonly") != null) {
                entrada.blur();
            }

            let tiempoClick = new Date().getTime();
            if (tiempoClick - ultimoClick < delay) {
                editarEntrada(entrada);
                e.preventDefault();
            }
            ultimoClick = tiempoClick;
        });

        entrada.addEventListener("touch", function (e) {
            if (entrada.getAttribute("readonly") != undefined && entrada.getAttribute("readonly") != null) {
                entrada.blur();
            }

            let tiempoClick = new Date().getTime();
            if (tiempoClick - ultimoClick < delay) {
                editarEntrada(entrada);

            }
            ultimoClick = tiempoClick;
        });


        entrada.addEventListener('blur', (evento) => {
            guardarEntrada(entrada);
        });
    });
}

function editarEntrada(entrada) {
    entrada.removeAttribute('readonly');
    entrada.setAttribute('autofocus', 'autofocus');
    entrada.focus();
}

function guardarEntrada(entrada) {
    entrada.setAttribute('readonly');
    window.livewire.emit('guardar-entrada', entrada.id, entrada.value);
}

// : Funcionalidad para arrastrar/soltar de entrada, y eliminar/completar entrada 
// Hago uso de la librería SortableJS para crear funcionalidad Drag&Drop para las entradas de la página.
// Creo un objeto Sortable con la listra de entradas y un parámetro adicional "handle" que inidica de dónde se pueden "agarrar" las entradas para desplazarlas.
// Los parámetros onStart y onEnd indican qué acciones ejecutar cuando se empieza o se termina de arrastrar una entrada.

const campoCompletar = document.getElementById("completar");
const campoEliminar = document.getElementById("eliminar");

document.addEventListener('DOMContentLoaded', () => {
    // En el primer elemento Sortable: 
    // onStart: llamo a la función que muestra los campos de complección y eliminación de entradas.
    // onEnd: llamo a la función registrarOrden() para comunicar al back-end si se ha alterado el orden de los elementos y actualizarlo.
    // Además, ejecuto una función setTimeout() para que después de un breve tiempo de haber soltado la entrada, se oculten los campos de completar/eliminar.
    const listaEntradas = document.getElementById("listaEntradas");
    const listaOrdenable = Sortable.create(listaEntradas, {
        animation: 150,
        group: "entradas",
        handle: '.handle',
        draggable: ".contenedor",
        delay: 125,
        delayOnTouchOnly: true,
        touchStartThreshold: 10,
        onStart: function (evento) {
            cambiarOpacidad(campoCompletar, 260, 0, 1);
            cambiarOpacidad(campoEliminar, 260, 0, 1);
        },
        onEnd: function (evento) {
            registrarOrden(evento);
            setTimeout(() => {
                cambiarOpacidad(campoCompletar, 260, 1, 0);
                cambiarOpacidad(campoEliminar, 260, 1, 0);
            }, 250);
        }
    });

    const listaCompletar = document.getElementById("completar");
    const dropCompletar = Sortable.create(listaCompletar, {
        animation: 150,
        group: "entradas",
        onAdd: function (evento) {
            evento.item.remove();
            window.livewire.emit('completar-entrada', evento.item.getAttribute("id"));
        }
    })

    const listaEliminar = document.getElementById("eliminar");
    const dropEliminar = Sortable.create(listaEliminar, {
        animation: 150,
        group: "entradas",
        onAdd: function (evento) {
            evento.item.remove();
            window.livewire.emit('eliminar-entrada', evento.item.getAttribute("id"));
        }
    });
});

// : Funcionalidad para registrar el orden de las entradas 

function registrarOrden(evtListaOrdenable) {
    let elementos = evtListaOrdenable.target.children;
    let ids = [];
    for (let i = 0; i < elementos.length; i++) {
            ids.push(elementos[i].getAttribute('id'));
    }
    window.livewire.emit('ordenar-entradas', ids, vistaMovil);
}

// .                                                                                   
// . FUNCIONES PARA GESTIONAR TAGS                                                     
// .                                                                                   

// : Funcionalidades para soltar/arrastrar tags 
const listasTags = document.getElementsByClassName("listaTags");
Sortable.mount(new Swap());

// Añado un evento que al para la lista de tags que aparece sobre cada entrada; que hace que, al cargar el documento, se crea un objeto Sortable para cada lista.
document.addEventListener('DOMContentLoaded', () => {

    // Itero por el array de listas, creando un Sortable para cada lista.
    Array.from(listasTags).forEach((lista) => {
        // Extraigo la id de la entrada a la que pertenece la lista de la id de la lista.
        const idEntradaDeLaLista = lista.id.split('-')[2];

        // Creo el objeto sortable, de forma que solo los elementos pertenecientes a la entrada en cuestión se puedan intercambiar entre sí.
        const ordenableTags = Sortable.create(lista, {
            animation: 150,
            swap: true,
            swapClass: 'bg-white',
            draggable: ".ordenable",
            group: idEntradaDeLaLista,
            onEnd: (evento) => {
                // Si la tag se está moviendo a la lista exterior de tags, se le aplican las proporciones necesarias.
                if (evento.to.id.split('-')[1] == "tags") {
                    evento.item.classList.remove("w-full");
                    evento.item.classList.remove("my-1");
                    evento.item.classList.add("w-1/4");
                }

                // Si, por otro lado, se está moviendo al popover de listas restantes, a estas se les aplicará una serie de estilos distintos.
                const tagsRestantes = document.getElementById("lista-tagsRestantes-" + idEntradaDeLaLista).getElementsByTagName("div");
                if (tagsRestantes != null) {
                    Array.from(tagsRestantes).forEach((tag) => {
                        tag.classList.add("w-full");
                        tag.classList.add("my-1");
                        tag.classList.remove("w-1/4");
                    });
                }

                // Combino la lista de tags con la lista de tags restantes para obtener el orden en el que debo registrar las tags en la base de datos.
                let listaIdTags = [];
                Array.from(evento.to.getElementsByClassName('ordenable')).forEach((tag) => {
                    let id = tag.getAttribute('id').split('-')[3];
                    listaIdTags.push(id);
                });

                if (tagsRestantes != null) {
                    Array.from(tagsRestantes).forEach((tag) => {
                        let id = tag.getAttribute('id').split('-')[3];
                        listaIdTags.push(id);
                    });
                }

                // Al final del todo, se envía la lista de tags al back-end para registrar el nuevo orden de las mismas.
                window.livewire.emit('ordenar-tags', idEntradaDeLaLista, listaIdTags);
            }
        });
    });
});

// .                                                                                   
// . FUNCIONES GRÁFICAS: animan elementos o ajustan la posición/tamaño de los mismos.  
// .                                                                                   

// : Animaciones para mostrar elementos 
// Usadas para mostrar y ocultar dinámicamente elementos de la vista página.

// Función usada para mostrar drop para la complección o elminación de entrada.
function cambiarOpacidad(elemento, duracion, opacidadInicial, opacidadObjetivo) {
    var intervalo = 20;
    var pasos = duracion / intervalo;
    var pasoActual = 0;

    var intervaloOpacidad = setInterval(function () {
        var nuevaOpacidad = opacidadInicial + ((opacidadObjetivo - opacidadInicial) / pasos) * pasoActual;

        elemento.style.opacity = nuevaOpacidad;

        pasoActual++;

        if (pasoActual > pasos) {
            clearInterval(intervaloOpacidad);
        }
    }, intervalo);
}

// : Funcionalidad para redimensionar iconos 
// Obtengo todos los contenedores de las entradas, que por defecto tienen la id de la entrada como su propia id.
// Obtengo por separado los botones y textarea de cada contenedor y con la API de ResizeObserver, creo un observador de tamaño para cada textarea.
// Cuando el textarea cambie de tamaño, llamará a la función redimensionar y ajustará el tamaño de sus botones al del propio textarea.

window.onload = () => {
    generarRedimensionadores();
}

const contenedores = document.getElementsByClassName("contenedor");
var observadores = [];

function generarRedimensionadores() {
    observadores = [];

    Array.from(contenedores).forEach((contenedor) => {
        if (contenedor.id == "") return;

        let btnHandle = document.getElementById(contenedor.id + `-handle`);
        let btnDropDown = document.getElementById(contenedor.id + `-dropdown`);
        let contenido = document.getElementById(contenedor.id + `-contenido`);

        btnHandle.style.height = `${contenido.offsetHeight}px`;
        btnDropDown.style.height = `${contenido.offsetHeight}px`;

        observadores.push(new ResizeObserver(() => { redimensionar(contenido, btnHandle, btnDropDown) }).observe(contenido));
    });
}

function redimensionar(contenido, ...elementos) {
    elementos.forEach((elemento) => {
        elemento.style.height = `${contenido.offsetHeight}px`;
    });
}

// : Funcionalidad para mostrar entradas completadas 
let toggleMostrarCompletadas = document.getElementById("toggleMostrarCompletadas");

toggleMostrarCompletadas.addEventListener('change', evt => {
    Array.from(contenedores).forEach(entrada => {
        if (toggleMostrarCompletadas.checked)
        
            entrada.classList.remove('hidden');        
        else {
            if (entrada.classList.contains('completado'))
                entrada.classList.add('hidden');
        };
    });
});

// .                                                                                   
// . FUNCIONES PARA GESTIONAR ENTRADAS (VISTA MÓVIL)                                   
// .                                                                                   

let vistaMovil = false

// : Swipe para borrar o completar una entrada 
let contenedorEnFoco;

let touchstartX = 0
let touchendX = 0

Array.from(contenedores).forEach((contenedor) => {
    contenedor.addEventListener('touchstart', e => {
        touchstartX = e.touches[0].screenX
    })

    // Event listener que registra sí se ha hecho "swipe" (para mostrar los botones de eliminar y borrar).
    contenedor.addEventListener('touchend', e => {
        touchendX = e.changedTouches[0].screenX;

        if (-(touchendX - touchstartX) > window.innerWidth / 2.5) {

            // Si ya habíamos hecho swipe a esta entrada
            if (contenedorEnFoco === contenedor){
                let idEntrada = contenedor.getAttribute('id');

                contenedor.classList.remove('animacionDerecha');
                contenedor.classList.remove('animacionIzquierda');
                document.getElementById('accionesMovil-'+idEntrada).classList.add('hidden');

                contenedor.classList.add('animacionCompletar');

                window.livewire.emit('completar-entrada', idEntrada);
            }
            // Si ya había una entrada a la que ya habíamos hecho medio swipe la volvemos a posicionar.
            else if (contenedorEnFoco != undefined && contenedorEnFoco != null) {
                contenedorEnFoco.classList.remove('animacionIzquierda');
                contenedorEnFoco.classList.add('animacionDerecha');
            }

            // La entrada seleccionada se mueve hacia la derecha, revelando el botón de borrar.
            contenedor.classList.remove('animacionDerecha');
            contenedor.classList.add('animacionIzquierda');
            contenedorEnFoco = contenedor;
        }
    });

    document.addEventListener('click', function (evento) {
        const clickDentroDeElemento = contenedor.contains(evento.target);
        // console.log("accionesMovil-"+contenedor.getAttribute("id"));

        if (evento.target.getAttribute("id") == "eliminarMovil-"+contenedor.getAttribute("id"))
            return;

        if (!clickDentroDeElemento && contenedor === contenedorEnFoco) {
            contenedor.classList.remove('animacionIzquierda');
            contenedor.classList.add('animacionDerecha');
            contenedorEnFoco = null;
        }
    });
})

// : Panel de acciones móvil (Botones de completar y borra entradas) 

const panelCentral = document.getElementById("panelCentral");
const listaEntradas = document.getElementById("listaEntradas");

let panelesAccionesMovil = [];

let anchuraPagina;
document.addEventListener('DOMContentLoaded', () =>{
    anchuraPagina = window.innerWidth || document.documentElement.clientWidth;
    
    if (anchuraPagina <= 640){
        setTimeout(generarAccionesMovil(), 1000);
        vistaMovil = true;
    }
});

window.onresize = () => {
    anchuraPagina = window.innerWidth || document.documentElement.clientWidth;

    if (anchuraPagina <= 640){
        setTimeout(generarAccionesMovil(), 1000);
        vistaMovil = true;
    }
}

function generarAccionesMovil(){
    panelesAccionesMovil.forEach(panel => {
        panel.remove();
    });
    panelesAccionesMovil = [];
    
    Array.from(listaEntradas.children).forEach((entrada) => {
        
        if (entrada.classList.contains("contenedor") && !entrada.classList.contains("hidden")){
            let entradaId = entrada.getAttribute('id');
            
            // Div con los botones de completar y eliminar (Acciones móvil).
            let accionesMovil = document.createElement('div');
            accionesMovil.setAttribute('id', 'accionesMovil-'+entradaId);
            accionesMovil.classList.add("accionesMovil", "absolute");
            accionesMovil.style.height = "164px";
            accionesMovil.style.width = "82px";
    
            // Botón de completar.
            let btnCompletar = document.createElement('div');
            btnCompletar.classList.add("absolute", "top-0", "bg-green-500", "px-5", "md:hidden", "rounded", "flex", "items-center", "cursor-pointer");
            btnCompletar.style.height = "82px";
            btnCompletar.innerHTML = "<i class='fas fa-check text-5xl text-white'></i>";
    
            btnCompletar.addEventListener("click", (evt) =>{
                 window.livewire.emit('completar-entrada', entradaId);
            });
    
            // Botón de eliminar
            let btnEliminar = document.createElement('div');
            btnEliminar.classList.add("absolute", "bg-red-500", "bottom-0", "px-5", "md:hidden", "rounded", "flex", "items-center", "cursor-pointer");
            btnEliminar.style.height = "82px";
            btnEliminar.innerHTML = "<i class='fas fa-trash-can text-5xl text-white'></i>";
    
            btnEliminar.addEventListener("click", (evt) =>{
                window.livewire.emit('eliminar-entrada', entradaId);
            });
            
            // Añado los botones al panel de acciones móvil.
            accionesMovil.appendChild(btnCompletar);
            accionesMovil.appendChild(btnEliminar);
            
            // Posiciono cada div de acciones móvil bajo su respectiva entrada.
            let posTop = entrada.offsetTop;
            let posRight = entrada.offsetLeft + entrada.offsetWidth;
    
            accionesMovil.style.top = (posTop + 20) + 'px';
            accionesMovil.style.right = (posRight - entrada.offsetWidth + 8) + 'px';
        
            // Añado el panel al DOM y a un array para manipularlo dinámicamente
            panelesAccionesMovil.push(accionesMovil);
            panelCentral.appendChild(accionesMovil);
        }
    });
}

