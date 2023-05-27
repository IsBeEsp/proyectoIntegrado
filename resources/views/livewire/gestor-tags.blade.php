<div>
    <link href="{{ asset('css/gestor-tags.css') }}" rel="stylesheet">
    <x-dialog-modal wire:model="openTags" maxWidth="5xl">
        <x-slot name="title">
            <div class="text-gray-800 text-center font-bold text-2xl">
                Gestor de Tags
            </div>
        </x-slot>
        <x-slot name="content">

            <!-- INFO PARA LA VISTA DE ESCRITORIO -->
            <div class="cursor-help bg-blue-200 rounded shadow-lg mb-4 p-2 text-left font-bold text-md md:block hidden">
                <span class="text-lg underline"><i class="fa-solid fa-circle-info"></i> Info</span><br>
                - Arrastra una tag de una lista a otra usando <button
                    class="cursor-help text-center overflow-hidden px-2 text-gray-700 m-0 rounded bg-gray-400"><i
                        class="fa-solid fa-grip-vertical"></i></button> para agregarla o quitarla de la entrada.<br>
                - Crea una nueva tag rellenando uno de los campos del principio de una lista y pulsando el botón <span
                    class="cursor-help text-center overflow-hidden px-1 text-bold text-white text-sm m-0 rounded bg-green-500">+</span>.<br>
                - Edita una tag haciendo click sobre su nombre o su color.<br>
                - Elimina una tag haciendo click en <i class="fas fa-trash text-red-500"></i>.<br>
            </div>

            <!-- INFO PARA LA VISTA MÓVIL -->
            <div class="cursor-help bg-blue-200 rounded shadow-lg mb-4 p-2 text-left font-bold text-xs md:hidden block">
                <span class="text-lg underline"><i class="fa-solid fa-circle-info"></i> Info</span><br>
                - Usa <input type="checkbox" disabled> / <input type="checkbox" checked disabled> para añadir o quitar
                de la entrada.<br><br>
                - Usa <span
                    class="cursor-help text-center overflow-hidden px-1 text-bold text-white text-sm m-0 rounded bg-green-500">+</span>
                para crear una nueva tag.<br><br>
                - Edita tags pulsando sobre su nombre o color.<br><br>
                - Elimina una tag haciendo click en <i class="fas fa-trash text-red-500"></i>.<br>
            </div>

            <!-- LISTA DE TAGS PARA LA VISTA MÓVIL -->
            <div id="divTagsMovil"
                class="listaTags bg-gray-200 rounded p-2 mb-2 h-72 md:h-96 w-full overflow-auto md:hidden">
                <div class="text-center text-lg w-full z-10 bg-gray-200 mb-2 border-b border-b-gray-300 rounded-t py-1">
                    Lista de tags</div>

                <!-- Campo crear tag -->
                <div class="flex justify-between mx-2 my-1 rounded text-black items-center bg-green-400 shadow-lg mb-5">
                    <div class="w-3/4 flex items-center">
                        <!-- Botón crear tag -->
                        <button id="btnCrearTagMovil"
                            class="cursor-pointer text-center overflow-hidden px-2 font-bold text-white text-lg m-0 rounded-l bg-green-500 hover:bg-green-800">+</button>

                        <!-- Color tag -->
                        <input id="colorNuevaTagMovil" type="color" class="ml-2 w-8 h-4 cursor-pointer">

                        <!-- Nombre tag -->
                        <input id="nombreNuevaTagMovil" type="text"
                            class="bg-gray-300 hover:border border-gray-400 h-6 w-full m-0 border-0 bg-green-400 placeholder-white placeholder:font-bold text-xs"
                            placeholder="Nombre nueva tag" />
                    </div>
                </div>

                <!-- Tags entrada -->
                <div id="listaTagsEntradaMovil" class="listaTags mb-2 w-full overflow-auto">
                    @if ($tagsEntrada != null)
                        @php
                            $primeraEntradaMovil = true;
                        @endphp
                        @foreach ($tagsEntrada as $tag)
                            <div id="movil-{{ $tag->id }}"
                                class="ordenableModalMovil flex justify-between mx-2 my-1 rounded text-black items-center bg-lime-200">
                                <div class="w-3/4 flex items-center">
                                    <!-- Checkbox de la tag: para añadir o quitar tags de la entrada. -->
                                    <input id="checkbox-{{ $tag->id }}" type="checkbox" value=""
                                        class="checkboxMovil mx-2 w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                        checked>

                                    <!-- Color tag -->
                                    @php
                                        echo $primeraEntradaMovil ? "<label for='input-color-$tag->id' class='cursor-pointer mx-1'><i style='color:$tag->color' class='fas fa-star text-lg'></i></label> <input id='input-color-$tag->id' type='color' class='colorTag w-8 h-4 cursor-pointer hidden' value='$tag->color'>" : "<input id='color-tag-$tag->id' type='color' class='colorTag w-8 h-4 cursor-pointer' value='$tag->color'>";
                                        $primeraEntradaMovil = false;
                                    @endphp

                                    <!-- Nombre tag -->
                                    <input type="text" value="{{ $tag->nombre }}"
                                        id="nombre-tag-{{ $tag->id }}"
                                        class="nombreTag bg-gray-300 hover:border border-gray-400 h-6 w-2/3 m-0 border-0"
                                        style="background-color:#D9F99D;" />
                                </div>
                                <div class="flex">
                                    <button wire:click="$emit('borrarTag', {{ $tag->id }})"
                                        class="text-red-500 hover:text-red-700 px-2 mr-2 text-lg"><i
                                            class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- TAGS RESTANTES -->
                <div id="listaTagsMovil" class="listaTags mb-2 w-full overflow-auto">
                    @foreach ($tagsRestantes as $tag)
                        <div id="movil-{{ $tag->id }}"
                            class="ordenableModalMovil flex justify-between mx-2 my-1 rounded text-black items-center bg-gray-300">
                            <div class="w-3/4 flex items-center">
                                <!-- Checkbox de la tag: para añadir o quitar tags de la entrada. -->
                                <input id="checkbox-{{ $tag->id }}" type="checkbox" value=""
                                    class="checkboxMovil mx-2 w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">

                                <!-- Color tag -->
                                <input id="color-tag-{{ $tag->id }}" type="color"
                                    class="colorTag w-8 h-4 cursor-pointer" value="{{ $tag->color }}">

                                <!-- Nombre tag -->
                                <input type="text" value="{{ $tag->nombre }}" id="nombre-tag-{{ $tag->id }}"
                                    class="nombreTag bg-gray-300 hover:border border-gray-400 h-6 w-2/3 m-0 border-0" />
                            </div>
                            <div class="flex">
                                <button wire:click="$emit('borrarTag', {{ $tag->id }})"
                                    class="text-red-500 hover:text-red-700 px-2 mr-2 text-lg"><i
                                        class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <!-- LISTAS DE TAGS PARA LA VISTA DE ESCRITORIO -->
            <div class="md:flex hidden gap-4">
                <!-- Lista de tags -->
                <div id="listaTags" class="listaTags bg-gray-200 rounded p-2 mb-2 h-64 md:h-96 w-full overflow-auto">
                    <div
                        class="text-center text-lg w-full z-10 bg-gray-200 mb-2 border-b border-b-gray-300 rounded-t py-1">
                        Lista de tags</div>

                    <!-- Campo crear tag -->
                    <div
                        class="flex justify-between mx-2 my-1 rounded text-black items-center bg-green-400 shadow-lg mb-5">
                        <div class="w-3/4 truncate items-center">
                            <!-- Botón crear tag -->
                            <button id="btnCrearTag1"
                                class="cursor-pointer text-center overflow-hidden px-2 font-bold text-white text-lg m-0 rounded-l bg-green-500 hover:bg-green-800">+</button>

                            <!-- Color tag -->
                            <input id="colorNuevaTag1" type="color" class="w-4 h-4 cursor-pointer">

                            <!-- Nombre tag -->
                            <input id="nombreNuevaTag1" type="text"
                                class="bg-gray-300 hover:border border-gray-400 h-6 w-2/3 m-0 border-0 bg-green-400 placeholder-white placeholder:font-bold"
                                placeholder="Nombre nueva tag" />
                        </div>
                    </div>

                    <!-- Lista de tags -->
                    @foreach ($tagsRestantes as $tag)
                        <div id="{{ $tag->id }}"
                            class="ordenableModal flex justify-between mx-2 my-1 rounded text-black items-center bg-gray-300">
                            <div class="w-3/4 truncate items-center">
                                <!-- Handle de la tag: para reorganizar tags en la lista de tags. -->
                                <button
                                    class="handle cursor-grab text-center overflow-hidden px-2 text-gray-700 text-lg m-0 rounded-l bg-black bg-opacity-10"><i
                                        class="fa-solid fa-grip-vertical"></i></button>

                                <!-- Color tag -->
                                <input id="color-tag-{{ $tag->id }}" type="color"
                                    class="colorTag w-4 h-4 cursor-pointer" value="{{ $tag->color }}">

                                <!-- Nombre tag -->
                                <input type="text" value="{{ $tag->nombre }}"
                                    id="nombre-tag-{{ $tag->id }}"
                                    class="nombreTag bg-gray-300 hover:border border-gray-400 h-6 w-2/3 m-0 border-0" />
                            </div>
                            <div class="flex">
                                <button wire:click="$emit('borrarTag', {{ $tag->id }})"
                                    class="text-red-500 hover:text-red-700 px-2 mr-2 text-lg"><i
                                        class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tags de la entrada -->
                <div id="listaTagsEntrada"
                    class="listaTags bg-lime-300 rounded mb-2 p-2 w-full overflow-auto h-64 md:h-96">
                    <div class="text-center text-lg w-full z-10 mb-2 border-b border-b-lime-400 rounded-t py-1">Entrada
                    </div>

                    <!-- Campo crear tag -->
                    <div
                        class="flex justify-between mx-2 my-1 rounded text-black items-center bg-green-400 shadow-lg mb-5">
                        <div class="w-3/4 truncate items-center">
                            <!-- Botón crear tag -->
                            <button wire:click="$refresh" id="btnCrearTag2"
                                class="cursor-pointer text-center overflow-hidden px-2 font-bold text-white text-lg m-0 rounded-l bg-green-500 hover:bg-green-800">+</button>

                            <!-- Color tag -->
                            <input id="colorNuevaTag2" type="color" class="w-4 h-4 cursor-pointer">

                            <!-- Nombre tag -->
                            <input id="nombreNuevaTag2" type="text"
                                class="bg-gray-300 hover:border border-gray-400 h-6 w-2/3 m-0 border-0 bg-green-400 placeholder-white placeholder:font-bold"
                                placeholder="Nombre nueva tag" />
                        </div>
                    </div>

                    @if ($tagsEntrada != null)
                        @php
                            $primeraEntrada = true;
                        @endphp
                        @foreach ($tagsEntrada as $tag)
                            <div id="{{ $tag->id }}"
                                class="ordenableModal flex justify-between mx-2 my-1 rounded text-black items-center bg-lime-200">
                                <div class="w-3/4 truncate">
                                    <!-- Handle de la tag: para reorganizar tags en la lista de tags. -->
                                    <button
                                        class="handle cursor-grab text-center overflow-hidden px-2 text-gray-700 text-lg m-0 rounded-l bg-black bg-opacity-10"><i
                                            class="fa-solid fa-grip-vertical"></i></button>

                                    <!-- Color tag -->
                                    @php
                                        echo $primeraEntrada ? "<label for='input-color-$tag->id' class='cursor-pointer'><i style='color:$tag->color' class='fas fa-star text-md'></i></label> <input id='input-color-$tag->id' type='color' class='colorTag w-4 h-4 cursor-pointer hidden' value='$tag->color'>" : "<input id='color-tag-$tag->id' type='color' class='colorTag w-4 h-4 cursor-pointer' value='$tag->color'>";
                                        $primeraEntrada = false;
                                    @endphp

                                    <!-- Nombre tag -->
                                    <input type="text" value="{{ $tag->nombre }}"
                                        id="nombre-tag-{{ $tag->id }}"
                                        class="nombreTag bg-lime-500 hover:border border-gray-400 h-4 w-2/3 m-0 border-0"
                                        style="background-color:#D9F99D;" />
                                </div>
                                <div class="flex">
                                    <button wire:click="$emit('borrarTag', {{ $tag->id }})"
                                        class="text-red-500 hover:text-red-700 px-2 mr-2 text-lg"><i
                                            class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </x-slot>
        <x-slot name="footer">
            <button wire:click="toggleGestor(false)"
                class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i
                    class="fas fa-multiply"></i> Cerrar</button>
        </x-slot>
    </x-dialog-modal>
    <script src="{{ asset('js/gestor-tags.js') }}" type="module"></script>
</div>
