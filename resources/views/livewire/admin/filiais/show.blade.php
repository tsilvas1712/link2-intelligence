<div>
    <x-header :title="$filial->filial" subtitle="Ajustar parâmetro da filial" separator />
    <div class="flex flex-col gap-8">
        <div class="text-xl">Nome Filial: <span class="font-bold">{{ $filial->filial }}</span></div>


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
                <x-input label="Meta Gross Pos" placeholder="Digite o valor da meta de acessórios"
                    wire:model="meta_gross_pos" />
                <x-input label="Meta Franquia Pos" placeholder="Digite o valor da meta de acessórios"
                    wire:model="meta_franquia_pos" />
                <x-input label="Meta Gross Controle" placeholder="Digite o valor da meta de acessórios"
                    wire:model="meta_gross_controle" />
                <x-input label="Meta Franquia Controle" placeholder="Digite o valor da meta de acessórios"
                    wire:model="meta_franquia_controle" />

            </div>


            <div class="w-1/2 flex gap-2">
                <x-button label="{{ $meta ? 'Atualizar' : 'Salvar' }}" class="btn-primary w-full"
                    wire:click="salvarMeta()" />
                <x-button label="Sair" wire:click="closeDrawer()" class="w-full" />
            </div>
        </div>
    </x-modal>

</div>
