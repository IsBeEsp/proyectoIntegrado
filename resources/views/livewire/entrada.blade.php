<div id="{{ $idEntrada }}" class="contenedor flex items-center md:my-4 my-12 relative shadow-sm @if($compleated_at != null) completado hidden @endif" wire:ignore> <!-- md:my-4 my-12 -->
    <link href="{{ asset('css/entrada.css') }}" rel="stylesheet">
    <button id=""
        class="handle cursor-grab text-4xl text-center overflow-hidden h-10 px-2 text-gray-400 bg-gray-300 md:hidden absolute -top-9 w-full z-20"><i
            class="fa-solid fa-grip-horizontal text-md"></i></button>
    <button id="{{ $idEntrada }}-handle"
        class="handle cursor-grab text-4xl text-center overflow-hidden h-48 px-2 text-gray-400 bg-gray-300 md:inline hidden"><i
            class="fa-solid fa-grip-vertical"></i></button>
    <textarea id="{{ $idEntrada }}-contenido" wire:model="contenido" wire:initial-data="{'entrada': {{ $entrada }}"
        class="entrada z-10 md:text-lg text-sm overflow-y-scroll w-full md:h-48 h-32 bg-gray-200 focus:bg-gray-100 cursor-text border-4 rounded border-gray-300"
        readonly wire:ignore></textarea>
    <button id="{{ $idEntrada }}-dropdown"
        class="handle text-4xl overflow-hidden h-48 px-2 text-gray-400 bg-gray-300 hidden"><i
            class="fa-solid fa-caret-right"></i></button>
    

    <!-- Tags -->
    <div id="lista-tags-{{ $idEntrada }}"
        class="listaTags z-10 md:absolute md:left-12 absolute ml-2 bottom-2 w-full md:w-auto flex flex-nowrap overflow-hidden gap-1">
        <!-- Lista de tags -->
        @if ($tags != null)
            @php  $numTags = 0; @endphp
            @foreach ($tags as $tag)
                @if ($numTags < 3)
                    <div id="entrada-{{ $idEntrada }}-tag-{{ $tag->id }}"
                        class="ordenable w-auto cursor-grab bg-gray-200 shrink-0 rounded-full border border-gray-400 px-2 text-sm truncate @if ($numTags != 0) hidden md:flex @else flex @endif gap-2 items-center pb-1">
                        <span style="color:{{ $tag->color }}">@php echo (!$numTags) ? "<i class='fas fa-star'></i>" : "⬤";@endphp </span>
                        <span class="text-xs md:text-sm truncate"
                            data-tooltip-target="tooltip-entrada-{{ $idEntrada }}-tag-{{ $tag->id }}">{{ $tag->nombre }}</span>
                        <button class="hover:text-red-500 ml-auto"
                            wire:click="$emit('cambiarTag', {{ $tag->id }}, false, {{ $idEntrada }})"><i
                                class="fas fa-multiply"></i></button>
                    </div>
                    @php $numTags++ @endphp
                @endif
            @endforeach
        @endif

        <!-- Botón popover tags extra -->
        @if ($tags != null && sizeof($tags) > 3)
            <button data-popover-target="popover-tags-{{ $idEntrada }}" data-popover-placement="bottom"
                data-popover-trigger="click" type="button"
                class="bg-gray-200 w-4/8 shrink-0 rounded-full border border-gray-400 px-2 text-sm truncate hidden md:flex gap-2 items-center pb-1">
                <span class="text-gray-500"><i class="fas fa-angle-down"></i></span>
                <span class="truncate text-gray-500">
                    +{{ sizeof($tags) - 3 }} tags
                </span>
            </button>
        @endif

        <!-- Botón modal gestor de tags -->
        <div>
            <div id="btnGestor-{{ $idEntrada }}" wire:click="$emit('toggleGestor', true, {{ $idEntrada }})"
                type="button"
                class="shrink-0 p-1 rounded-full bg-gray-500 w-8 h-8 hover:bg-gray-400 text-white hover:text-black cursor-pointer text-center text-md relative">
                <i class="fas fa-tag"></i>
            </div>
        </div>
    </div>

    <!-- Tooltip nombre tags -->
    @if ($tags != null)
        @php  $numNombreTags = 0; @endphp
        @foreach ($tags as $tag)
            @if ($numNombreTags < 3)
                <div id="tooltip-entrada-{{ $idEntrada }}-tag-{{ $tag->id }}" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    {{ $tag->nombre }}
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                @php $numNombreTags++ @endphp
            @endif
        @endforeach
    @endif

    <!-- Popover tags extra -->
    <div data-popover id="popover-tags-{{ $idEntrada }}" role="tooltip"
        class="absolute z-30 invisible inline-block w-64 text-sm text-black transition-opacity duration-300 bg-gray-300 rounded-lg shadow-xl opacity-0">
        <div id="lista-tagsRestantes-{{ $idEntrada }}" class="listaTags px-3 py-2">
            @if ($tags != null)
                @php  $numTagsTooltip = 0; @endphp
                @foreach ($tags as $tag)
                    @if ($numTagsTooltip >= 3)
                        <div id="entrada-{{ $idEntrada }}-tag-{{ $tag->id }}"
                            class="ordenable w-full my-1 cursor-grab shrink-0 rounded-full border border-gray-400 px-2 text-sm truncate flex gap-2 items-center pb-1">
                            <span style="color:{{ $tag->color }}">⬤ </span>
                            <span class="truncate">{{ $tag->nombre }}</span>
                            <button class="hover:text-red-500 ml-auto"
                                wire:click="$emit('cambiarTag', {{ $tag->id }}, false, {{ $idEntrada }})"><i
                                    class="fas fa-multiply"></i></button>
                        </div>
                    @endif
                    @php $numTagsTooltip++ @endphp
                @endforeach
            @endif
        </div>
        <div data-popper-arrow></div>
    </div>

    {{-- @if ($entrada->entradas()->count() > 0)
        @foreach ($entrada->entradas() as $entradaHija)
            <p style="color:red"> ENTRADA ENCAPSULADA</p>
        @endforeach

    @endif --}}
</div>
