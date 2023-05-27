// : Eventos para esta vista
window.onload = () => {
    tintarLibros();
    añadirOpciones();
    añadirEnlaces();
};

window.addEventListener('contenido-actualizado', evento => {
    tintarLibros();
    añadirOpciones();
    añadirEnlaces();
});

window.addEventListener('libro-actualizado', evento => {
    tintarLibro(evento.detail.id, evento.detail.color);
});

// : Variables para esta vista
const contenedores = document.getElementsByClassName("contenedor");
const canvases = document.getElementsByTagName('canvas');
const opciones = document.getElementsByClassName("opciones");

// : Funciones para esta vista.

function tintarLibro(id, color) {
    // Obtengo el canvas específico.
    const canvas = document.getElementById(id);
    const ctx = canvas.getContext('2d');

    // Crear plantilla para imagen.
    const img = new Image();
    img.src = 'storage/graphics/libro.png';

    img.onload = function() {
        canvas.width = img.width;
        canvas.height = img.height;

        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        ctx.globalCompositeOperation = 'source-in';
        ctx.fillStyle = color;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }
}

function tintarLibros(){
    for (let i = 0; i < canvases.length; i++) {
    const id = canvases[i].id;
    tintarLibro(id, canvases[i].dataset.color);
    }
}

function revelarOpciones(id) {
    Array.from(opciones).forEach((div) => {
        if (div.id == `opciones-${id}`)
            div.classList.remove("hidden");
        else
            div.classList.add("hidden");
    });
}

function ocultarOpciones(id) {
    Array.from(opciones).forEach((div) => {
        if (div.id == `opciones-${id}`)
            div.classList.add("hidden");
    });
}

// Añadir event listener a cada div para revelar opciones
function añadirOpciones(){
    for (let i = 0; i < contenedores.length; i++) {
        let idOriginal = contenedores[i].id;
        let idParametro = idOriginal.slice(11, idOriginal.length);

        // Añadir event listener para ordenadores
        contenedores[i].addEventListener("mouseover", () => {
            revelarOpciones(idParametro);
        });

        contenedores[i].addEventListener("mouseout", () => {
            ocultarOpciones(idParametro);
        });

        // Añadir event listener para móviles/tablets
        contenedores[i].addEventListener("click", () => {
            revelarOpciones(idParametro);
        });

        document.addEventListener('click', function(evento) {
            const clickDentroDeElemento = contenedores[i].contains(evento.target);

            if (!clickDentroDeElemento) {
                ocultarOpciones(idParametro);
            }
        });
    }
}

// : Añadir enlace a cada icono de libro via evento 
function añadirEnlaces(){
    let iconos = document.getElementsByClassName("iconoLibro");

    Array.from(iconos).forEach(icono => {
        icono.addEventListener('click', evt => {
            window.livewire.emit('redirigir', icono.getAttribute('id'));
        });
    });
}
