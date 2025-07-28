<div class="flex flex-col items-center gap-2 rounded bg-gray-100 p-2">
    <h3 class="text-lg font-bold">{{ $grupo->nome }}</h3>
    <span class="text-2xl font-black">R$

        {{ number_format($this->getValores()['total'], 2, ',', '.') }}</span>
    <div class="flex w-full justify-between gap-2">
        <div class="flex flex-col w-full gap-2">
            <div class="flex w-full flex-col items-center rounded bg-primary p-2 shadow">
                <span class="text-xs font-bold text-white">Tendência</span>
                <span class="text-xs font-bold text-white">R$
                                                {{ number_format($this->getValores()['tendencia'], 2, ',', '.') }}</span>
            </div>
            <div class="flex w-full flex-col items-center rounded bg-orange-400 p-2 shadow">
                <span class="text-xs font-bold text-white">Meta</span>
                <span class="text-xs font-bold text-white">R$
                                                {{ number_format($this->getValores()['meta_valor'], 2, ',', '.') }}</span>
            </div>
        </div>

        <div class="flex w-1/3 flex-col bg-white justify-center items-center p-2">
            <span class="text-lg font-bold">{{ number_format(floatVal($this->getValores()['projecao']),2) }}%</span>
            @if (floatVal($this->getValores()['projecao']) > 100.0)
                <x-icon class="h-6 w-6 text-green-500" name="o-arrow-trending-up"/>
            @elseif (floatVal($this->getValores()['projecao']) > 65.0 && floatVal($this->getValores()['projecao']) < 100.0)
                <x-icon class="h-6 w-6 text-blue-500" name="o-arrow-right"/>
            @else
                <x-icon class="h-6 w-6 text-red-500" name="o-arrow-trending-down"/>
            @endif
        </div>


    </div>

</div>

