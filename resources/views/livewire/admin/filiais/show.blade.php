<div>
    <x-header :title="$filial->filial" subtitle="Ajustar parâmetro da filial" separator />
    <div class="flex flex-col gap-8">
        <div class="text-xl">Nome Filial: <span class="font-bold">{{ $filial->filial }}</span></div>

        <div class="w-full bg-gray-200 p-4 rounded">
            <span class="font-bold">Ultima Meta Lançada</span>
            <div class="flex flex-col gap-4">
                <div class="flex justify-between text-sm">
                    <span><b>Mes: </b>{{ $meses[$meta_atual->mes - 1]['name'] }}</span>
                    <span><b>Ano: </b>{{ $meta_atual->ano }}</span>
                    <span><b>Total Dias Mês: </b>{{ $meta_atual->total_dias_mes }}</span>
                    <span><b>Dias Trabalhados: </b>{{ $meta_atual->dias_trabalhado }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span><b>Faturamento: </b>R$ {{ number_format($meta_atual->meta_faturamento, 2, ',', '.') }}</span>
                    <span><b>Aparelhos: </b>R$ {{ number_format($meta_atual->meta_aparelhos, 2, ',', '.') }}</span>
                    <span><b>Acessórios: </b>R$ {{ number_format($meta_atual->meta_acessorios, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span><b>Pos: </b>R$ {{ number_format($meta_atual->meta_pos, 2, ',', '.') }}</span>
                    <span><b>Pre: </b>R$ {{ number_format($meta_atual->meta_pre, 2, ',', '.') }}</span>
                    <span><b>Controle: </b>R$ {{ number_format($meta_atual->meta_controle, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span><b>Pos Gross: </b>{{ $meta_atual->meta_gross_pos }}</span>
                    <span><b>Pre Gross: </b>{{ $meta_atual->meta_gross_pre }}</span>
                    <span><b>Controle Gross: </b>{{ $meta_atual->meta_gross_controle }}</span>
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
