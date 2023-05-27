<div>
    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <!-- Título y navegación entre páginas -->
            <div class="w-2/3 bg-gray-200 bg-opacity-50 rounded mx-auto p-2 mb-2 flex flex-col items-center justify-between">
                <div class="text-center mb-2">
                    <span class="font-bold text-2xl">Página del día</span><br>
                    <span class="text-xl">{{ $pagina->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between w-full items-center">
                    <button id="btnDiaAnterior" class="bg-gray-500 hover:bg-gray-700 text-white text-xl font-bold py-2 px-4 rounded"> << <span class="md:inline hidden">Día anterior</span></button>
                    <div class="flex flex-col">
                        <span class="ml-3 text-sm font-semibold text-gray-900 dark:text-gray-300 text-center my-1">Mostrar completadas</span>
                        <label class="relative inline-flex items-center mx-auto cursor-pointer">
                            <input id="toggleMostrarCompletadas" type="checkbox" value="" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <button id="btnDiaSiguiente" class="bg-gray-500 hover:bg-gray-700 text-white text-xl font-bold py-2 px-4 rounded"><span class="md:inline hidden">Día siguiente</span> >> </button>
                </div>
            </div>

            <!-- Panel de opciones -->
            {{-- <div class="w-2/3 mb-2 mx-auto bg-gray-200 bg-opacity-50">
                <div class="flex justify-center">
                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative float-left -ml-[1.5rem] mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]" type="checkbox" id="inlineCheckbox1" value="option1" />
                      <label class="inline-block pl-[0.15rem] hover:cursor-pointer" for="inlineCheckbox1">Mostrar/Ocultar botones</label>
                    </div>
                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                        <input class="relative float-left -ml-[1.5rem] mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]" type="checkbox" id="inlineCheckbox1" value="option1" />
                        <label class="inline-block pl-[0.15rem] hover:cursor-pointer" for="inlineCheckbox1">1</label>
                      </div>
                  
                  </div>
            </div> --}}

            <!-- Lista de entradas y paneles laterales -->
            <div class="grid grid-cols-12 mx-3">
                <!-- Panel completar -->
                <div id="completar"
                    class="md:col-span-2 col-span-0 mx-auto w-full h-full hidden md:flex justify-center items-center h-24 my-4 text-2xl text-green-500 font-bold bg-green-100 bg-opacity-50 border-dashed border-4 border-green-300 rounded"
                    style="opacity: 0" wire:ignore>
                    Completar
                </div>

                <!-- Panel central -->
                <div id="panelCentral" class="md:col-span-8 col-span-12 w-11/12 bg-gray-200 bg-opacity-50 rounded mx-auto p-2 relative">

                    <!-- Lista de entradas -->
                    <div class="relative" id="listaEntradas" wire:ignore>
                        @foreach ($entradas as $entrada)
                            @livewire('entrada', ['entrada' => $entrada])
                        @endforeach
                    </div>
                    

                    <!-- Plantilla nueva entrada -->
                    <div id="plantillaNuevaEntrada" class="hidden">
                        @livewire('entrada')
                    </div>

                    <!-- Botón añadir entrada -->
                    <div id="btnAñadir"
                        class="sticky bottom-2 flex justify-center items-center h-24 my-4 bg-gray-100 hover:bg-gray-200 cursor-pointer border-dashed border-4 border-gray-300 rounded z-20">
                        <span class="text-5xl text-gray-400">+</span>
                    </div>
                </div>

                <!-- Panel eliminar -->
                <div id="eliminar"
                    class="md:col-span-2 col-span-0 hidden md:flex mx-auto w-full h-full justify-center items-center h-24 my-4 text-2xl text-red-500 font-bold bg-red-100 bg-opacity-50 border-dashed border-4 border-red-300 rounded"
                    style="opacity: 0" wire:ignore>
                    <span class="absolute top-auto left-auto z-0">Eliminar</span>
                </div>
            </div>

            <!-- Gestor de Tags -->
            <div>
                @livewire('gestor-tags', ['libroId' => $book, 'tags' => $tags])
            </div>
        </div>
    </div>
    <script src="{{ asset('js/show-pagina.js')}}" type="module"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</div>
