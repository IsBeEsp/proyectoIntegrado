import { Sortable, Swap } from "./sortable.esm.js";

// : Abrir el gestor de Tags
document.addEventListener('abrirGestorTags', () => {
    window.livewire.emit('toggleGestor', true);
});

// : Ordenar elementos de las listas.

// $ Funcionalidad de ordenación de entradas

// Guardo el orden de las entradas al cargar (asignando un valor a orden nulos).
let arrayIdTags = [];
document.addEventListener('gestorAbierto', () => {
    ordenarListaTags(false);
});

function ordenarListaTags(entrada) {
    // Vacío el array de id de tags previamente generado.
    arrayIdTags = [];

    if (!entrada){
        // Genero un array con las id de cada tag de la lista, este estará ordenado por defecto por ID, o por cómo lo haya movido el usuario.
        Array.from(listaTags.getElementsByClassName('ordenableModal')).forEach((tag) => {
            arrayIdTags.push(tag.getAttribute('id'));
        });
    } else {
        // Genero un array con las id de cada tag de la lista, este estará ordenado por defecto por ID, o por cómo lo haya movido el usuario.
        Array.from(listaTagsEntrada.getElementsByClassName('ordenableModal')).forEach((tag) => {
            arrayIdTags.push(tag.getAttribute('id'));
        });
    }
    
    // Mando el array al back para que se actualice el orden en la base de datos.
    window.livewire.emit('ordenarTags', arrayIdTags, entrada);
}

// $ Funcionalidad Drag 'n Drop
document.addEventListener('DOMContentLoaded', () => {
    var listaTags = document.getElementById('listaTags');

    // * Lista tags general
    // Al cambiar una tag de lista, esta se reasignará con el método 'cambiarTag' del back end.
    // Si la tag se mueve dentro de la propia lista, se reordenará con el método 'ordenarTags' del back end.
    Sortable.create(listaTags, {
        animation: 150,
        draggable: ".ordenableModal",
        group: 'tags',
        handle: '.handle',
        onEnd: function (evt) {
            if (evt.from != evt.to)
                window.livewire.emit('cambiarTag', evt.item.id, true);
            else{
                ordenarListaTags(false);
            }
        }
    });

    // * Lista tags de la entrada
    // Igual pero para la lista de tags de la entrada.
    var listaTagsEntrada = document.getElementById('listaTagsEntrada');

    Sortable.create(listaTagsEntrada, {
        animation: 150,
        draggable: ".ordenableModal",
        group: 'tags',
        handle: '.handle',
        onEnd: function (evt) {
            if (evt.from != evt.to)
                window.livewire.emit('cambiarTag', evt.item.id, false);
            else
                ordenarListaTags(true);
        }
    });
});

// : Crear tags 

// Desde la lista general de tags.
let btnCrearTag_1 = document.getElementById("btnCrearTag1");

btnCrearTag_1.addEventListener('click', () => {
    let nombreNuevaTag_1 = document.getElementById("nombreNuevaTag1");
    let colorNuevaTag_1 = document.getElementById("colorNuevaTag1");

    window.livewire.emit('crearTag', nombreNuevaTag_1.value, colorNuevaTag_1.value, false);

    nombreNuevaTag_1.value = "";
    colorNuevaTag_1.value = "";
});

// Desde la lista de tags de la entrada.
let btnCrearTag_2 = document.getElementById("btnCrearTag2");

btnCrearTag_2.addEventListener('click', () => {
    let nombreNuevaTag_2 = document.getElementById("nombreNuevaTag2");
    let colorNuevaTag_2 = document.getElementById("colorNuevaTag2");

    window.livewire.emit('crearTag', nombreNuevaTag_2.value, colorNuevaTag_2.value, true);

    nombreNuevaTag_2.value = "";
    colorNuevaTag_2.value = "";
});

// Desde la lista de tags móvil.
let btnCrearTag_Movil = document.getElementById("btnCrearTagMovil");

btnCrearTag_Movil.addEventListener('click', () => {
    let nombreNuevaTag_Movil = document.getElementById("nombreNuevaTagMovil");
    let colorNuevaTag_Movil = document.getElementById("colorNuevaTagMovil");

    window.livewire.emit('crearTag', nombreNuevaTag_Movil.value, colorNuevaTag_Movil.value, true);

    nombreNuevaTag_Movil.value = "";
    colorNuevaTag_Movil.value = "";
});

// : Añadir nueva tag a la lista de tags correspondiente.

// A la lista general de tags.
let listaTags = document.getElementById("listaTags");
document.addEventListener('añadirTags', (evento) => {
    let idTag = evento.detail.id;
    let nombreTag = evento.detail.nombre;
    let colorTag = evento.detail.color;

    let nuevaTag = `
    <div id="${idTag}" class="ordenableModal flex justify-between mx-2 my-1 rounded text-black items-center bg-gray-300">
        <div class="w-3/4 truncate items-center">
            <button class="handle cursor-grab text-center overflow-hidden px-2 text-gray-700 text-lg m-0 rounded-l bg-black bg-opacity-10"><i class="fa-solid fa-grip-vertical"></i></button>

            <input type="color" class="w-4 h-4 cursor-pointer" value="${colorTag}">

            <input type="text" value="${nombreTag}" id="nombre-tag-${idTag}" class="bg-gray-300 hover:border border-gray-400 h-6 w-2/3 m-0 border-0" />
        </div>
        <div class="flex">
            <button wire:click="$emit('borrarTag', ${idTag})" class="text-red-500 hover:text-red-700 px-2 mr-2 text-lg"><i class="fas fa-trash"></i></button>
        </div>
    </div>`;

    listaTags.innerHTML += nuevaTag;
});

// A la lista de tags de la entrada.
let listaTagsEntrada = document.getElementById("listaTagsEntrada");
document.addEventListener('añadirEntrada', (evento) => {
    let idTag = evento.detail.id;
    let nombreTag = evento.detail.nombre;
    let colorTag = evento.detail.color;

    let nuevaTag = `
    <div id="${idTag}" class="ordenableModal flex justify-between mx-2 my-1 rounded text-black items-center bg-lime-200">
        <div class="w-3/4 truncate items-center">
            <button class="handle cursor-grab text-center overflow-hidden px-2 text-gray-700 text-lg m-0 rounded-l bg-black bg-opacity-10"><i class="fa-solid fa-grip-vertical"></i></button>

            <input type="color" class="w-4 h-4 cursor-pointer" value="${colorTag}">

            <input type="text" value="${nombreTag}" id="nombre-tag-${idTag}" class="hover:border border-gray-400 h-6 w-2/3 m-0 border-0" style="background-color:#D9F99D;"/>
        </div>
        <div class="flex">
            <button wire:click="$emit('borrarTag', ${idTag})" class="text-red-500 hover:text-red-700 px-2 mr-2 text-lg"><i class="fas fa-trash"></i></button>
        </div>
    </div>`;

    listaTagsEntrada.innerHTML += nuevaTag;
});

// $ Desde la vista móvil
document.addEventListener('gestorAbierto', () => {
    generarCheckboxes();
});

document.addEventListener('actualizarCheckbox', () => {
    generarCheckboxes();
});

function generarCheckboxes(){
    let listaCheckBox = document.getElementsByClassName("checkboxMovil");

    Array.from(listaCheckBox).forEach(checkBox => {
        checkBox.addEventListener('change', (evt) =>{
            window.livewire.emit('cambiarTag', checkBox.getAttribute('id').split('-')[1], checkBox.checked);
        });
    });
}

// : Editar tags 

// $ Event listeners para los campos de texto.
let camposNombre = document.getElementsByClassName("nombreTag");

document.addEventListener('gestorAbierto', () => {
    Array.from(camposNombre).forEach((campo) => {
        campo.addEventListener('blur', () => {
            let idTag = campo.id.split("-")[2];
            window.livewire.emit('actualizarTag', idTag, 'nombre', campo.value);
        });
    });
});

// $ Event listeners para los campos de color.
let camposColor = document.getElementsByClassName("colorTag");

document.addEventListener('gestorAbierto', () => {
    Array.from(camposColor).forEach((campo) => {
        campo.addEventListener('input', () => {
            let idTag = campo.id.split("-")[2];
            window.livewire.emit('actualizarTag', idTag, 'color', campo.value);
        });
    });
});