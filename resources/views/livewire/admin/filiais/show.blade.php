<div>
    <x-header :title="$filial->filial" subtitle="Ajustar parâmetro da filial" separator />
    <div class="flex flex-col gap-8">
        <div class="text-xl">Nome Filial: <span class="font-bold">{{ $filial->filial }}</span></div>

        <div class="w-full bg-gray-200 p-4 rounded">
            <span class="font-bold">Ultima Meta Lançada</span>
            @if ($meta_atual)
                <div class="flex flex-col gap-4 mt-4">
                    <div class="flex justify-between text-sm p-2 bg-white rounded shadow-sm">
                        <span><b>Mes:
                            </b>{{ $meses[$meta_atual->mes - 1 < 0 ? 0 : $meta_atual->mes - 1]['name'] }}</span>
                        <span><b>Ano: </b>{{ $meta_atual->ano }}</span>
                        <span><b>Total Dias Mês: </b>{{ $meta_atual->total_dias_mes }}</span>
                        <span><b>Dias Trabalhados: </b>{{ $meta_atual->dias_trabalhado }}</span>
                    </div>
                    <div class="flex justify-between">
                        <div class="flex flex-col justify-between text-sm gap-4">
                            <span><b>Faturamento: </b>R$
                                {{ number_format($meta_atual->meta_faturamento, 2, ',', '.') }}</span>
                            <span><b>Aparelhos: </b>R$
                                {{ number_format($meta_atual->meta_aparelhos, 2, ',', '.') }}</span>
                            <span><b>Acessórios: </b>R$
                                {{ number_format($meta_atual->meta_acessorios, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex flex-col justify-between text-sm gap-4">
                            <span><b>Pos: </b>R$ {{ number_format($meta_atual->meta_pos, 2, ',', '.') }}</span>
                            <span><b>Pre: </b>R$ {{ number_format($meta_atual->meta_pre, 2, ',', '.') }}</span>
                            <span><b>Controle: </b>R$
                                {{ number_format($meta_atual->meta_controle, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex flex-col justify-between text-sm gap-4">
                            <span><b>Pos Gross: </b>{{ $meta_atual->meta_gross_pos }}</span>
                            <span><b>Pre Gross: </b>{{ $meta_atual->meta_gross_pre }}</span>
                            <span><b>Controle Gross: </b>{{ $meta_atual->meta_gross_controle }}</span>
                        </div>
                    </div>
                </div>
            @endif

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
            <div class="flex flex-col gap-2 mb-8 w-full">
                @if ($meta)
                    <span
                        class="font-bold text-primary text-xl">{{ $meses[$meta->mes - 1 < 0 ? 0 : $meta->mes - 1]['name'] . '/' . $meta->ano }}</span>
                @else
                    <span class="font-bold text-primary text-xl">Nova Meta</span>
                    <x-select label="Selecione o Mês" placeholder="Escolha um mês" icon="o-calendar" :options="$meses"
                        wire:model="mes" />
                    <x-input label="Ano" placeholder="Digite o ano" wire:model="ano" />
                @endif
                <div class="flex gap-2 w-full">
                    <div class="w-full">
                        <x-input label="Totais de Dias Mês" placeholder="Digite o Total de Dias Trabalhado"
                            wire:model="total_dias_mes" class="w-full" />
                    </div>
                    <div class="w-full">
                        <x-input label="Dia" placeholder="Dia Atual" wire:model="dias_trabalhado" />
                    </div>
                </div>

                <div class="flex gap-2 w-full">
                    <div class="w-full">
                        <x-input label="Meta Faturamento" placeholder="Digite o valor da meta de faturamento"
                            wire:model="meta_faturamento" class="w-full" />
                    </div>
                    <div class="w-full">
                        <x-input label="Meta Aparelhos" placeholder="Digite o valor da meta de aparelhos"
                            wire:model="meta_aparelhos" />
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-full">
                        <x-input label="Meta Acessórios" placeholder="Digite o valor da meta de acessórios"
                            wire:model="meta_acessorios" />
                    </div>
                    <div class="w-full">
                        <x-input label="Meta Pos" placeholder="Digite o valor da meta de acessórios"
                            wire:model="meta_franquia_pos" />
                    </div>
                    <div class="w-full">
                        <x-input label="Meta Controle" placeholder="Digite o valor da meta de acessórios"
                            wire:model="meta_franquia_controle" />
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-full">
                        <x-input label="Meta Gross Pos" placeholder="Digite o valor da meta de acessórios"
                            wire:model="meta_gross_pos" />
                    </div>
                    <div class="w-full">
                        <x-input label="Meta Gross Controle" placeholder="Digite o valor da meta de acessórios"
                            wire:model="meta_gross_controle" />
                    </div>


                </div>



            </div>


            <div class="w-1/2 flex gap-2">
                <x-button label="{{ $meta ? 'Atualizar' : 'Salvar' }}" class="btn-primary w-full"
                    wire:click="salvarMeta()" />
                <x-button label="Sair" wire:click="closeDrawer()" class="w-full" />
            </div>
        </div>
    </x-modal>

</div>
