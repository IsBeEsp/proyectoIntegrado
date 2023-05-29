//: Determino el mes actual a partir de qué página del día somos redirigidos 


// : Obtengo los datos de las entradas del mes actual 
// Obtengo los datos enviados desde el back y guardados en el contenedor de calendario.
let entradasPorDia;
let mesActual;
document.addEventListener('DOMContentLoaded',() => {
  window.livewire.emit('obtenerEntradas');
});

document.addEventListener('recibirEntradas', (evento) =>{
  entradasPorDia = evento.detail.paginasEntradas;
  mesActual = evento.detail.mes;
  calcularFecha();
  generarCalendario();
});


// : Obtener fecha actual y calcular días del mes 
let fechaActual;
let numDias;
let primerDia;

function calcularFecha() {
  // Obtengo la fecha actual
  fechaActual = new Date();
  fechaActual.setMonth(mesActual - 1);

  // Obtengo el número de días del mes
  numDias = new Date(fechaActual.getFullYear(), fechaActual.getMonth() + 1, 0).getDate();

  // Obtengo el primer día del mes
  primerDia = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1).getDay();
}

// : Ajustar tamaño del calendario 
let anchuraPagina;

// Al cargar el DOM, obtengo la anchura de la ventana y aplico los estilos correspondintes.
document.addEventListener('DOMContentLoaded', () =>{
  anchuraPagina = window.innerWidth || document.documentElement.clientWidth;
  aplicarBreakpoints();
});

// Al redimensionar la ventana, obtengo la anchura de la ventana y aplico los estilos correspondintes.
window.onresize = () => {
  anchuraPagina = window.innerWidth || document.documentElement.clientWidth;
  aplicarBreakpoints();
}

// Necesito aplicar los breakpoints de esta manera porque Tailwind no está funcionando bien en el proyecto.
// Comprebo el tamaño de la ventana y aplico a todas las filas de semana una distribución grid en base al tamaño.
function aplicarBreakpoints() {
  let divSemanas = document.getElementsByClassName('semana');
  Array.from(divSemanas).forEach(div => {
    if (anchuraPagina < 640){
      div.classList.remove("grid-cols-7");
      div.classList.add("grid-cols-1");
    } else {
      div.classList.add("grid-cols-7");
      div.classList.remove("grid-cols-1");
    }
  });
}

// : Mostrar layout correspondiente al mes 
// El calendario tiene 42 'casillas' para días, esto corresponde a un calendario cuya primera semana empieza en domingo y última semana termina en lunes.
// Dependiendo de en qué día de la semana empieze el mes, mostraré u ocultaré los primeros o últimos días del mes.
function generarCalendario(){
  let divDias = document.getElementsByClassName("dia");
  let arrDias = Array.from(divDias);
  
  // La variable del bucle representa las casillas del calendario, mientras que la variable 'dia' lleva la cuenta de por qué día del mes vamos.
  let dia = 1;
  for (let i = 0; i <42; i++){
    if (i < primerDia - 1)
      arrDias[i].classList.add('invisible');
    else if (dia > numDias)
      arrDias[i].classList.add('invisible');
    else {
      // = Asigno el número de día del mes correspondiente.
      let numDia = document.createElement('div');
      numDia.innerHTML = dia;
      numDia.classList.add('top-0', 'bg-gray-300', 'font-bold', 'text-center', 'sticky', 'w-full');
      document.getElementById('divNum-'+(i+1)).appendChild(numDia);
      
      // = Si el array de entradas del backend tiene entradas para este día, las pinto en el calendario.
      let posicionDia = dia + 1;
      if (entradasPorDia.hasOwnProperty(posicionDia)){
        entradasPorDia[posicionDia].forEach(entrada => {
          let divEntrada = document.createElement('div');
    
          let idEntrada = entrada.split('-')[0];
          let colorEntrada = entrada.split('-')[1];
          let tagEntrada = entrada.split('-')[2];
          let completada = entrada.split('-')[3];

          let estilosCompletado = (completada) ? 'line-through text-gray-400' : '';

          divEntrada.innerHTML = `<div class='hover:bg-white cursor-pointer'><span class="pl-2"style='color: ${colorEntrada};'>⬤ </span><span class="${estilosCompletado}">${tagEntrada}</span></div>`;

          divEntrada.addEventListener('click', evt => {
            window.livewire.emit('verDetalles', idEntrada);
          });

          arrDias[dia].appendChild(divEntrada);
        });
      }
      dia++;
    }

  }
}

// : Funcionalidades para la edición de entrada desde el calendario. 

document.addEventListener('modal-abierto', (evt) => {
  console.log(evt.detail);
  document.getElementById('divBtnGestor').innerHTML = `<button id='btnGestor' class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full"><i class="fas fa-tag"></i> Gestionar tags</button>`;
  
  document.getElementById('btnGestor').addEventListener('click', evt2 => {
    console.log(evt.detail);
    window.livewire.emit('cancelar');
    window.livewire.emit('toggleGestor', true, evt.detail);
  });
});

document.addEventListener('modal-cerrado', evt => {
  window.livewire.emit('update');
});