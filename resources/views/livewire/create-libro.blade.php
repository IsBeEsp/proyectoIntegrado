<div class="md:col-span-6 col-span-8 mx-1 mt-1">
    <button wire:click="$set('openCrear', true)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full"><i class="fas fa-add md:invisible"></i> <span class="hidden md:inline">Nuevo Kuaderbook</span></button>
    <x-dialog-modal wire:model="openCrear">
        <x-slot name="title">
            Nuevo Kuaderbook
        </x-slot>
        <x-slot name="content">
            @wire('defer')
            <x-form-input class="text-center" type="text" name="nombre" label="Nombre" placeholder="¡Dale un nombre a tu Kuaderbook!"/>
            <x-form-input type="color" name="color" label="Color de portada"/>
            @endwire
        </x-slot>
        <x-slot name="footer">
            <div class="flex w-full flex-row-reverse justify-evenly">
                    <button wire:click="crear()" class="flex-1 mx-1 w-1/2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-add"></i> Crear
                    </button>

                    <button  wire:click="$set('openCrear', false)" class="flex-1 mx-1 w-1/2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-xmark"></i> Cancelar
                    </button>

            </div>
        </x-slot>
    </x-dialog-modal>
</div>
