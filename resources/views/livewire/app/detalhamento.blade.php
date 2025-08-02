<div class="flex flex-col gap-2 bg-white h-full p-4 rounded-lg shadow-md">
    <x-header :title="$grupo->nome" subtitle="Detalhamento" size="text-xl" separator />

    <div class="flex gap-4 justify-between items-center">
        <div class="flex flex-col w-full gap-2 bg-gray-200 p-4 rounded-lg shadow-md">
            <div>
                <span class="font-bold">Período</span>
            </div>
            {{ $data_ini }} - {{ $data_fim }}
        </div>
        <div class="flex flex-col w-full gap-2 bg-gray-200 p-4 rounded-lg shadow-md">
            <div>
                <span class="font-bold">Filiais</span>
            </div>
            <div class="flex flex-wrap gap-1">
                @foreach ($data_filiais as $filial)
                    <x-badge :value="$filial->filial" class="badge-primary" />
                @endforeach
            </div>
        </div>
        <div class="flex flex-col w-full gap-2 bg-gray-200 p-4 rounded-lg shadow-md">
            <div>
                <span class="font-bold">Vendedores</span>
            </div>
            <div class="flex flex-wrap gap-1">
                @foreach ($data_vendedores as $vendedor)
                    <x-badge :value="$vendedor->nome" class="badge-primary" />
                @endforeach
            </div>
        </div>
    </div>
    <div>
        <livewire:charts.apex-bars :data="$chartMetas" />
    </div>

    @if ($filiais)
        <div class="flex flex-col  w-full gap-4">
            <h2 class="text-lg font-bold">Total por Filial</h2>
            <hr />
            <div class="flex flex-warp  w-full gap-4">
                @foreach ($filiais as $filial)
                    <a href="#" class="hover:text-white w-full">
                        <div class="flex flex-col w-full rounded-md p-4 hover:bg-primary bg-gray-100 shadow-md">
                            @php
                                $f = App\Models\Filial::find($filial);
                            @endphp
                            <div>
                                <span class="text-lg font-bold">{{ $f->filial }}</span>
                            </div>
                            <span class="text-lg font-bold">
                                R${{ number_format($this->totalFilial($filial), 2, ',', '.') }}
                            </span>
                        </div>
                    </a>
                @endforeach

            </div>
            <div>
                <livewire:app.components.detalhamento.chart-pie-filiais :filiais="$filiais" :dt_inicio="$dt_start" :dt_fim="$dt_end" :grupo_id="$grupo_id" />
            </div>
        </div>

    @endif

    @if ($vendedores)
        <div class="flex flex-col  w-full gap-4">
            <h2 class="text-lg font-bold">Total por Vendedor</h2>
            <hr />
            <div class="flex flex-warp  w-full gap-4">
          
                @foreach ($data_vendedores as $vendedor)
                    <a href="#" class="hover:text-white w-full">
                        <div class="flex flex-col w-full rounded-md p-4 hover:bg-primary bg-gray-100 shadow-md">
                            <div>
                                <span class="text-lg font-bold">{{ $vendedor->nome }}</span>
                            </div>
                            <span class="text-lg font-bold">
                                R${{ number_format($this->totalVendedor($vendedor->id), 2, ',', '.') }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div>
                <livewire:app.components.detalhamento.chart-pie-vendedores :vendedores="$vendedores" :dt_inicio="$dt_start" :dt_fim="$dt_end" :grupo_id="$grupo_id" />
            </div>
        </div>

    @endif
</div>
