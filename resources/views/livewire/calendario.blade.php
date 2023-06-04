<div>
    <div class="w-full text-4xl font-bold mt-4 mb-2 text-center">Calendario</div>
    <div id="fechaMes" class="w-full text-2xl font-semibold  text-center"></div>
    <div class="flex justify-between w-2/3 my-4 items-center mx-auto">
        <button id="btnMesAnterior" class="bg-gray-500 hover:bg-gray-700 text-white text-xl font-bold py-2 px-4 rounded"> << <span class="md:inline hidden">Mes anterior</span></button>
        <button id="btnMesSiguiente" class="bg-gray-500 hover:bg-gray-700 text-white text-xl font-bold py-2 px-4 rounded"><span class="md:inline hidden">Mes siguiente</span> >> </button>
    </div>
    <div id="calendar-container" class="w-3/4 h-max mx-auto" wire:ignore>
        <div class="semana grid grid-cols-7 gap-4 mb-4 mb-4">
            <div id="1" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-1" class="order-first sticky top-0"></div></div>
            <div id="2" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-2" class="order-first sticky top-0"></div></div>
            <div id="3" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-3" class="order-first sticky top-0"></div></div>
            <div id="4" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-4" class="order-first sticky top-0"></div></div>
            <div id="5" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-5" class="order-first sticky top-0"></div></div>
            <div id="6" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-6" class="order-first sticky top-0"></div></div>
            <div id="7" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-7" class="order-first sticky top-0"></div></div>
        </div>
        <div class="semana grid grid-cols-7 gap-4 mb-4">
            <div id="8" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-8" class="order-first sticky top-0"></div></div>
            <div id="9" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-9" class="order-first sticky top-0"></div></div>
            <div id="10" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-10" class="order-first sticky top-0"></div></div>
            <div id="11" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-11" class="order-first sticky top-0"></div></div>
            <div id="12" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-12" class="order-first sticky top-0"></div></div>
            <div id="13" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-13" class="order-first sticky top-0"></div></div>
            <div id="14" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-14" class="order-first sticky top-0"></div></div>
        </div>
        <div class="semana grid grid-cols-7 gap-4 mb-4">
            <div id="15" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-15" class="order-first sticky top-0"></div></div>
            <div id="16" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-16" class="order-first sticky top-0"></div></div>
            <div id="17" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-17" class="order-first sticky top-0"></div></div>
            <div id="18" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-18" class="order-first sticky top-0"></div></div>
            <div id="19" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-19" class="order-first sticky top-0"></div></div>
            <div id="20" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-20" class="order-first sticky top-0"></div></div>
            <div id="21" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-21" class="order-first sticky top-0"></div></div>
        </div>
        <div class="semana grid grid-cols-7 gap-4 mb-4">
            <div id="22" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-22" class="order-first sticky top-0"></div></div>
            <div id="23" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-23" class="order-first sticky top-0"></div></div>
            <div id="24" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-24" class="order-first sticky top-0"></div></div>
            <div id="25" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-25" class="order-first sticky top-0"></div></div>
            <div id="26" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-26" class="order-first sticky top-0"></div></div>
            <div id="27" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-27" class="order-first sticky top-0"></div></div>
            <div id="28" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-28" class="order-first sticky top-0"></div></div>
        </div>
        <div class="semana grid grid-cols-7 gap-4 mb-4">
            <div id="29" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-29" class="order-first sticky top-0"></div></div>
            <div id="30" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-30" class="order-first sticky top-0"></div></div>
            <div id="31" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-31" class="order-first sticky top-0"></div></div>
            <div id="32" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-32" class="order-first sticky top-0"></div></div>
            <div id="33" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-33" class="order-first sticky top-0"></div></div>
            <div id="34" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-34" class="order-first sticky top-0"></div></div>
            <div id="35" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-35" class="order-first sticky top-0"></div></div>
        </div>
        <div class="semana grid grid-cols-7 gap-4 mb-4">
            <div id="36" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-36" class="order-first sticky top-0"></div></div>
            <div id="37" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-37" class="order-first sticky top-0"></div></div>
            <div id="38" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-38" class="order-first sticky top-0"></div></div>
            <div id="39" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-39" class="order-first sticky top-0"></div></div>
            <div id="40" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-40" class="order-first sticky top-0"></div></div>
            <div id="41" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-41" class="order-first sticky top-0"></div></div>
            <div id="42" class="dia flex flex-col w-full bg-gray-100 h-48 shadow-lg relative overflow-y-scroll"><div id="divNum-42" class="order-first sticky top-0"></div></div>
        </div>
    </div>

    <!-- Modal para ver entrada -->
    <x-dialog-modal wire:model="openEntrada">
        <x-slot name="title">
            Resumen de la entrada
        </x-slot>
        <x-slot name="content">
            @wire('defer')
            <x-form-textarea id="contenidoEntrada" name="entrada.contenido" class="h-48"/>
            @endwire
            <div class="my-3">
                <span class="text-lg font-bold">Tags:</span>
                <div id="panelTags" class="h-12 w-full flex flex-row overflow-auto gap-2">
                    @if($tags != null)
                        @php $primeraTag = true; @endphp
                        @foreach ($tags as $tag)
                        <div class="ordenable my-1 shrink-0 rounded-full border border-gray-400 px-2 text-sm flex gap-2 items-center pb-1">
                            <span style="color:{{ $tag->color }}">@if($primeraTag) <i class='fas fa-star'></i> @else ⬤ @endif</span>
                            <span>{{ $tag->nombre }}</span>
                        </div>
                        @php $primeraTag = false; @endphp
                        @endforeach
                    @endif
                </div>
            </div>
            <div id="divBtnGestor">
                
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="flex flex-row-reverse w-full">
                <button wire:click="update()"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">
                <i class="fa-solid fa-save"></i> Guardar 
                </button>
                <button  wire:click="cancelar()"
                class="mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full">
                <i class="fas fa-xmark"></i> Cerrar
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
    
    <!-- Gestor de Tags -->
    <div>
        @livewire('gestor-tags', ['libroId' => $book, 'tags' => $tags])
    </div>
    <script src="{{ asset('js/calendario.js') }}" type="module"></script>
</div>
