<div>
    <x-header :title="$filial->filial" subtitle="Ajustar parâmetro da filial" separator />
    <div class="flex flex-col gap-8">
        <div class="text-xl">Nome Filial: <span class="font-bold">{{ $filial->filial }}</span></div>

        <div class="flex gap-4 bg-slate-400 rounded shadow p-4 justify-between p-8">
            <div class="flex flex-col gap-4">
                <div class="text-lg">Faturamento: <span class="font-bold italic">
                        R$ {{ number_format($metas['meta_faturamento'], 2, ',', '.') }}
                    </span>
                </div>
                <div class="text-lg">Aparelhos: <span class="font-bold italic">
                        R$ {{ number_format($metas['meta_aparelhos'], 2, ',', '.') }}
                    </span>
                </div>
                <div class="text-lg">Acessórios: <span class="font-bold italic">
                        R$ {{ number_format($metas['meta_acessorios'], 2, ',', '.') }}
                    </span>
                </div>
            </div>
            <div>
                <div class="text-lg">Data Corrente: <span class="font-bold italic text-2xl">
                        {{ $meses[$metas['mes'] - 1]['name'] }}/{{ $metas['ano'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 bg-gray-200 rounded shadow mt-8 flex flex-col gap-8">
        <div class="flex justify-between gap-8">
            <x-input label="Busca" placeholder="Digite parte do nome da filial para ser feita a busca"
                icon="o-magnifying-glass" wire:model.live="search" />
            <x-button label="Nova Meta" class="btn-primary" @click="$wire.openDrawer" />
        </div>

        <x-table :headers="$this->headers" :rows="$this->getMetas" with-pagination>

            @scope('actions', $meta)
                <x-button icon="o-eye" @click="$wire.openDrawer({{ $meta->id }})" spinner
                    class="btn-sm btn-primary" />
            @endscope

        </x-table>

    </div>

    <x-modal wire:model="showDrawer" class="backdrop-blur" persistent>
        <div class="w-full flex flex-col gap-2">
            <div class="flex flex-col gap-2">
                @if ($meta)
                    <span
                        class="font-bold text-primary text-xl">{{ $meses[$meta->mes - 1]['name'] . '/' . $meta->ano }}</span>
                @else
                    <span class="font-bold text-primary text-xl">Nova Meta</span>
                    <x-select label="Selecione o Mês" placeholder="Escolha um mês" icon="o-calendar" :options="$meses"
                        wire:model="mes" />
                    <x-input label="Ano" placeholder="Digite o ano" wire:model="ano" />
                @endif

                <x-input label="Meta Faturamento" placeholder="Digite o valor da meta de faturamento"
                    wire:model="meta_faturamento" />
                <x-input label="Meta Aparelhos" placeholder="Digite o valor da meta de aparelhos"
                    wire:model="meta_aparelhos" />
                <x-input label="Meta Acessórios" placeholder="Digite o valor da meta de acessórios"
                    wire:model="meta_acessorios" />

            </div>


            <div class="w-1/2 flex gap-2">
                <x-button label="{{ $meta ? 'Atualizar' : 'Salvar' }}" class="btn-primary w-full"
                    wire:click="salvarMeta()" />
                <x-button label="Sair" wire:click="closeDrawer()" class="w-full" />
            </div>
        </div>
    </x-modal>

</div>
