<div>
    <x-header title="{{ $name ?? 'Novo Usuário' }}" subtitle="Ajustes de usuários do Sistema" separator />
    <div class="flex justify-end gap-8">

        <x-button label="Alterar Senha" icon="o-key" class="btn-primary" @click="$wire.openModal" />
    </div>
    <div>
        @if ($errors->hasAny('invalidRegister'))
            <x-alert icon="o-exclamation-triangle" class="alert-danger mb-4">
                @error('invalidRegister')
                    <span>{{ $message }}</span>
                @enderror

            </x-alert>
        @endif

        @php
            $cargo = [
                [
                    'id' => 'admin',
                    'name' => 'Administrador',
                ],
                [
                    'id' => 'usuario',
                    'name' => 'Usuário',
                ],
            ];
        @endphp
        <x-form wire:submit.prevent="save">
            <div class="flex flex-col gap-4">
                <x-input label="Nome" placeholder="Digite o nome do usuário" wire:model.live="name" name="name" />
                <x-input label="E-mail" placeholder="Digite o e-mail do usuário" wire:model="email" name="email" />

                @if ($user === null)
                    <x-input label="Confirmação de Senha" placeholder="Digite a senha do usuário" type="password"
                        wire:model="password_confirmation" />
                @endif

                <x-select label="Escolha o Cargo" icon="o-user" :options="$cargo" wire:model="cargoSelected" />

            </div>
            <div class="flex justify-end gap-4">
                <x-button label="Salvar" class="btn-primary" type="submit" />
                <x-button label="Cancelar" class="btn-secondary" link="{{ route('admin.usuarios') }}" />
            </div>
        </x-form>
    </div>

    <x-modal wire:model="showModal" class="backdrop-blur" persistent>
        <div class="mb-5">Digite a nova senha para este Usuário</div>
        <x-input label="Senha" placeholder="Digite a senha do usuário" type="password" name="password"
            wire:model="password" />
        <div class="flex w-full gap-2 py-4">
            <div class="w-full">
                <x-button class="w-full" label="Cancel" @click="$wire.showModal = false" />
            </div>
            <div class="w-full">
                <x-button class="w-full btn-primary" label="Atualizar Senha" @click="$wire.updatePassword" />
            </div>

        </div>
    </x-modal>

</div>
