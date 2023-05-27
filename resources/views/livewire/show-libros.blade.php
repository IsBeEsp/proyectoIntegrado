<div>
    <x-kuaderbook.cabecera>
        <div class="grid grid-cols-8">
            @livewire('create-libro')
            <div class="md:col-span-2 col-span-8 mx-1 mt-1">
                <select wire:model="campoOrden"
                    class="text-center w-full bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded border-0">
                    <option value="created_at.asc">▲ Fecha de creación</option>
                    <option value="created_at.desc">▼ Fecha de creación</option>
                    <option value="updated_at.asc">▲ Última edición</option>
                    <option value="updated_at.desc">▼ Última edición</option>
                    <option value="nombre.asc">▲ Nombre</option>
                    <option value="nombre.desc">▼ Nombre</option>
                </select>
            </div>
        </div>
        <div class="flex flex-wrap justify-evenly">
            @foreach ($libros as $libro)
                <div id="contenedor-{{ $libro->id }}"
                    class="bg-stone-200 rounded-md m-4 relative contenedor shadow-lg cursor-pointer">
                    <!-- Icono y nombre de cada Kuaderbook -->
                    <canvas id="{{ $libro->id }}" class="iconoLibro" style="width:12rem;" data-color="{{ $libro->color }}"></canvas>
                    <p class="text-center pb-2">{{ $libro->nombre }}</p>
                    
                    <!-- Opciones de Edición/Eliminación de cada Kuaderbook -->
                    <div id="opciones-{{ $libro->id }}"
                        class="opciones hidden absolute bottom-0 left-0 w-full bg-stone-200 rounded-md">
                        <hr class="h-px m-1 bg-gray-400 border-0">
                        <div class="grid grid-cols-2 divide-x-2 divide-gray-400 text-center mb-1">
                            <div><button wire:click="editar({{ $libro }})" class="text-blue-500 hover:text-blue-700 font-bold py-1 px-4 rounded w-3/4"><i class="fas fa-edit"></i> </button></i></div>
                            <div><button wire:click="borrar({{ $libro }})" class="text-red-500 hover:text-red-700 font-bold py-1 px-4 rounded w-3/4"><i class="fas fa-trash"></i> </button></i></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal para editar -->
        <x-dialog-modal wire:model="openEditar">
            <x-slot name="title">
                Editar Kuaderbook
            </x-slot>
            <x-slot name="content">
                @wire('defer')
                <x-form-input class="text-center" type="text" name="libro.nombre" label="Nombre" placeholder="¡Dale un nombre a tu Kuaderbook!"/>
            <x-form-input type="color" name="libro.color" label="Color de portada"/>
                @endwire
            </x-slot>
            <x-slot name="footer">
                <div class="flex flex-row-reverse">
                    <button  wire:click="update()"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-redo"></i> Actualizar
                    </button>
                    <button  wire:click="cancelar()"
                    class="mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-xmark"></i> Cancelar
                    </button>
                </div>
            </x-slot>
        </x-dialog-modal>
    </x-kuaderbook.cabecera>

    <!-- Script para la funcionalidad -->
    <script src="{{ asset('js/show-libros.js')}}"></script>
</div>
